<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\Document;
use Appydo\TestBundle\Form\DocumentType;
use Appydo\TestBundle\Entity\Log;

/**
 * Project controller.
 *
 * @Route("/admin/design")
 */
class DesignController extends Controller {

    /**
     * Lists all Project entities.
     *
     * @Route("/", name="design")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $request = $this->getRequest();
        
        if ($request->getMethod() === 'POST')
        {
            $design  = $request->request->get('design');
            $project = $em->getRepository('AppydoTestBundle:Project')->find($user->getCurrent()->getId());
            $project->setTheme($design);
            $em->persist($project);
            $em->flush();
        }

        $dir = $this->container->getParameter('kernel.root_dir') . '/Resources/views/';
        $tab = array();
        $descriptions = array();
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if (!in_array($entry, array(".", "..")))
                    array_push($tab, $entry);
                if (file_exists($dir . '/' . $entry . '/description.txt')) {
                    array_push($descriptions, file_get_contents($dir . '/' . $entry . '/description.txt'));
                }
            }
            closedir($handle);
        }

        return array(
            'project' => AdminController::getProject($em, $user),
            'listFiles' => $tab,
            'descriptions' => $descriptions,
        );
    }

}
