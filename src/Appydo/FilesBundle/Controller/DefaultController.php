<?php
    
    namespace Appydo\FilesBundle\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    
    use Appydo\FilesBundle\Entity\Document;
    use Appydo\FilesBundle\Form\DocumentType;
    
    class DefaultController extends Controller
    {
        /**
         * @Route("/", name="_appydo_files")
         * @Template()
         */
        public function indexAction()
        {
            $document = new Document();
            $form     = $this->createForm(new DocumentType(),$document);
            
            return array(
                'form'    => $form->createView(),
                'theme' => 'default',
                );
        }
        
        /**
         * @Route("/upload", name="_appydo_files_upload")
         * @Method("post")
         * @Template()
         */
        public function uploadAction()
        {
            $document = new Document();
            $form     = $this->createForm(new DocumentType(),$document);
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $filename = $document->file->getClientOriginalName();
                $size     = $document->file->getClientSize() / 1000;
                $mimeType = $document->file->guessExtension();
                $document->upload();
                $log = new Log("Upload file", "File "+$document->file->getClientOriginalName(), $user);
                $em->persist($log);
                $em->flush();
            }

            $url  = 'http://127.0.0.1/~jimmyy/Symfony2/www/uploads/appyfiles/';
            $url .= $filename;
            
            return array(
                'form'  => $form->createView(),
                'url'   => $url,
                'theme' => 'default',
                'type'  => $mimeType,
                'size'  => $size,
                );
        }
    }