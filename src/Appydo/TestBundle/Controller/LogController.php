<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\Log;

/**
 * Log controller.
 *
 * @Route("/admin/log")
 */
class LogController extends Controller
{
    /**
     * Lists all Log entities.
     *
     * @Route("/", name="log")
     * @Template()
     */
    public function indexAction()
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $entities = $em->getRepository('AppydoTestBundle:Log')->findBy(
                    array('project' => $user->getCurrentId()),
                    array('id'      => 'DESC')
                );

        return array(
            'entities' => $entities,
            'project'  => AdminController::getProject($em, $user)
            );
    }

    /**
     * Finds and displays a Log entity.
     *
     * @Route("/{id}/show", name="log_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:Log')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Log entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
    
    /**
     * Empty logs
     *
     * @Route("/empty", name="log_empty")
     * @Template()
     */
    public function emptyAction()
    {
        
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $project = AdminController::getProject($em, $user);

        if ($project->getAuthor()!=$user) {
            throw $this->createNotFoundException('User is not admin.');
        }
        
        $entities = $em->getRepository('AppydoTestBundle:Log')->findBy(
                    array('project' => $user->getCurrentId()),
                    array('id'      => 'DESC')
                );
        
        if (!$entities) {
            throw $this->createNotFoundException('Unable to find Log entities.');
        }
        
        foreach ($entity as $entities) {
            $em->remove($entity);
        }
        
        $log = new Log("Empty logs", "Empty logs", $user);
        $em->persist($log);
        $em->flush();

        return $this->redirect($this->generateUrl('log_show'));

    }

}
