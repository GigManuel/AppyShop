<?php

namespace Appydo\QuizBundle\Controller;

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
 * @Route("/admin/quiz/files")
 */
class DocumentController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @Route("/", name="document")
     * @Template()
     */
    public function indexAction()
    {
        $em       = $this->getDoctrine()->getEntityManager();
        $user     = $this->get('security.context')->getToken()->getUser();
        $document = new Document();
        $form     = $this->createForm(new DocumentType(),$document);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $document->upload($user->getCurrentId());
                $log = new Log("Upload file", "File", $user);
                $em->persist($log);
                $em->flush();
            }
        }
        
        $dir = $document->getUploadRootDir($user->getCurrentId());
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        
        $tab = array();
        if ($handle = opendir($dir)) {

            while (false !== ($entry = readdir($handle))) {
                if (!in_array($entry,array(".", "..")))
                    array_push($tab,$entry);
            }

            closedir($handle);
        }
        
        return array(
            'form'    => $form->createView(),
            'project' => AdminController::getProject($em, $user),
            'listFiles' => $tab
            );
    }

}
