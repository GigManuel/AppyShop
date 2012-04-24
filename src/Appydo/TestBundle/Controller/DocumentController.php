<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\Document;
use Appydo\TestBundle\Entity\Directory;
use Appydo\TestBundle\Form\DocumentType;
use Appydo\TestBundle\Form\DirectoryType;
use Appydo\TestBundle\Entity\Log;

/**
 * Project controller.
 *
 * @Route("/admin/files")
 */
class DocumentController extends Controller {

    /**
     * Lists all Project entities.
     *
     * @Route("/", name="document")
     * @Template()
     */
    public function indexAction() {
        
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $document  = new Document();
        $directory = new Directory();
        $form = $this->createForm(new DocumentType(), $document);
        $form_dir = $this->createForm(new DirectoryType(), $directory);
        $dir = $document->getUploadRootDir($user->getCurrentId());
        
        if ($this->getRequest()->getMethod() === 'POST') {
            $form_dir->bindRequest($this->getRequest());
            if ($form_dir->isValid()) {
                mkdir($dir.'/'.$directory->name);
            }
            
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $document->upload($user->getCurrentId());
                $log = new Log("Upload file", "File", $user);
                $em->persist($log);
                $em->flush();
            }
        }

        if (!is_dir($dir)) {
            @mkdir($dir);
            $uploadDirExit = is_dir($dir);
        } else {
            $uploadDirExit = true;
        }

        $tab = array();
        $sizes = array();
        if ($uploadDirExit) {

            if ($handle = opendir($dir)) {

                while (false !== ($entry = readdir($handle))) {
                    if (!in_array($entry, array(".", ".."))) {
                        array_push($tab, $entry);
                        array_push($sizes, round(filesize($dir . '/' . $entry) / 1024));
                    }
                }

                closedir($handle);
            }
        }
        
        return array(
            'uploadDirExit' => $uploadDirExit,
            'form'      => $form->createView(),
            'form_dir'  => $form_dir->createView(),
            'project'   => AdminController::getProject($em, $user),
            'listFiles' => $tab,
            'sizes'     => $sizes
        );
    }
    
    function delete($filename)
    {
        
    }
    
    /**
     *
     * @Route("/do", name="document_do")
     * @Template()
     */
    function doAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $document = new Document();
        $dir = $document->getUploadRootDir($user->getCurrentId());
        $request  = $this->get('request');
        $delete = $request->request->get('delete');
        if (!empty($delete)) {
            unlink($dir.'/'.$delete);
        }
        
        $rename = $request->request->get('rename');
        if (!empty($rename)) rename($dir.'/'.$rename, $dir.'/'.$rename.'-');
        return $this->redirect($this->generateUrl('document'));
    }
}
