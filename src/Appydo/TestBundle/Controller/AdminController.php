<?php
namespace Appydo\TestBundle\Controller;

use Appydo\TestBundle\Entity\Topic;
use Appydo\TestBundle\Entity\Stat;
use Appydo\TestBundle\Entity\User;
use Appydo\TestBundle\Entity\Log;
use Appydo\TestBundle\Entity\Project;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Form\ContactType;

use Appydo\TestBundle\Form\ProjectNote;

/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", defaults={"id"="0"}, name="_appydo_admin")
     * @Route("/{id}", name="_appydo_admin_note", requirements = {"id" = "\d+"})
     * @Template()
     */
    public function indexAction($id)
    {   
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if (!$user or $user=="anon.") {
            throw $this->createNotFoundException('Unable to find user.');
        }
        
        if ($this->getRequest()->getMethod() === 'POST') {
            $this->noteSave($id);
        }
        
        $project = AdminController::getProject($em, $user);
        
        if (!$project) {
            $topics = null;
            // throw $this->createNotFoundException('Unable to find project.');
        } else {

            // Select all the topics for the default website
            $topics = $em->getRepository('AppydoTestBundle:Topic')->findBy(
                    array('project' => $project->getId(), 'hide'    => false),
                    array('id'      => 'DESC')
                );
            $note_form = $this->createForm(new ProjectNote(), $project);
        }
        
        return array(
            'project'   => $project,
            'topics'    => $topics,
            'theme'     => ($project)?$project->getTheme():'',
            'note_form' => $note_form->createView()
            );
    }

    public function noteSave($id)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('AppydoTestBundle:Project')->find($id);
        $create = $entity->getCreated();

        if ($create==null) {
            $create = new \DateTime();
        }
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $editForm   = $this->createForm(new ProjectNote(), $entity);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $entity->setAuthor($user);
            $entity->setCreated($create);
            $entity->setUpdated(new \DateTime());
            $em->persist($entity);
            $log = new Log("Update Project", "Update note of project ".$entity->getId()." (".$entity->getName().")", $user);
            $em->persist($log);
            $em->flush();
        }
    }
    
    /**
     * @Route("/info", name="_appydo_admin_info")
     * @Template()
     */
    public function infoAction()
    {
        ob_start();
        phpinfo();
        $info = ob_get_contents();
        ob_get_clean();
        return array(
            'info' => str_replace ( '<table', '<table class="table table-striped"', preg_replace ( '%^.*<body>(.*)</body>.*$%ms', '$1', $info ))
            );
    }
    
    /**
     * @Route("/visitors", name="_appydo_admin_visitors")
     * @Template("AppydoTestBundle:Admin:iframe.html.twig")
     */
    public function visitorsAction()
    {
        return array(
            'url' => 'http://demo.piwik.org/index.php'
            );
    }
    
    /**
     * @Route("/server", name="_appydo_admin_server")
     * @Template("AppydoTestBundle:Admin:iframe.html.twig")
     */
    public function serverAction()
    {
        return array(
            'url' => 'http://127.0.0.1/~jimmyy/phpsysinfo'
            );
    }
    
    
    public static function getProject($em, $user) {
        if ($user!="anon.") {
            $query = $em->createQuery('SELECT p FROM AppydoTestBundle:Project p WHERE p.id=?1');
            $query->setParameter(1, $user->getCurrent());
            return $query->getSingleResult();
        } else {
            return null;
        }
        
    }
    
}
