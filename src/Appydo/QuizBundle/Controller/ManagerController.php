<?php

namespace Appydo\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Appydo\QuizBundle\Entity\Manager;
use Appydo\QuizBundle\Form\ManagerType;

/**
 * Manager controller.
 *
 * @Route("/manager")
 */
class ManagerController extends Controller
{
    /**
     * Lists all Manager entities.
     *
     * @Route("/", name="manager")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $entities = $em->getRepository('AppydoQuizBundle:Manager')->findBy(
                    array('hide' => false),
                    array('id'   => 'ASC')
                );

        return array('entities' => $entities);
    }
    
    /**
     * Lists all Manager entities.
     *
     * @Route("/trash", name="manager_trash")
     * @Template()
     */
    public function trashAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $entities = $em->getRepository('AppydoQuizBundle:Manager')->findBy(
                    array('hide' => true),
                    array('id'   => 'ASC')
                );

        return array('entities' => $entities);
    }
    
    /**
     * Export
     *
     * @Route("/zip/{id}", name="zip")
     * @Template()
     */
    public function zipAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppydoQuizBundle:Manager')->find($id);
        $query = $em->createQuery('SELECT s, q
            FROM AppydoQuizBundle:Subject s
            INNER JOIN s.quizz q
            WHERE s.manager=?1');
        $query->setParameter(1, $entity->getId());
        $subjects = $query->getResult();
        $entity->count++;
        $em->persist($entity);
        $em->flush();
        $zip = new \ZipArchive();
        // if ($zip->open('/Users/jimmyy/Sites/Symfony2/www/zip/export.zip',\ZipArchive::CREATE) !== TRUE) {
        if ($zip->open('/home/backend2/www/zip/export.zip',\ZipArchive::CREATE) !== TRUE) {
            die ("Could not open archive");
        }
        
        $content = file_get_contents('http://91.121.8.188/~backend2/app_dev.php/quizz/manager/export/'.$id.'?'.microtime());
        
        // $content = file_get_contents('http://127.0.0.1/~jimmyy/Symfony2/www/app_dev.php/quizz/manager/export/'.$id);
        
        foreach($subjects as $subject) {
            $zip->addFile(
                $this->container->getParameter('kernel.root_dir').'/../www/uploads/usinedoc/'.$subject->getId().'/'.$subject->path,
                'images/upload/'.$subject->path
            );
            $zip->addFile(
                $this->container->getParameter('kernel.root_dir').'/../www/uploads/usinedoc/'.$subject->getId().'/'.$subject->path_explication,
                'images/upload/'.$subject->path_explication
            );
        }
        
        if ($entity->theme=="essonne") {
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export_essonne/quiz.css','quiz.css');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export_essonne/js/jquery-1.6.4.min.js','js/jquery-1.6.4.min.js');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export_essonne/js/quiz-1.0.js','js/quiz-1.0.js');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export_essonne/images/coupe.png','images/coupe.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export_essonne/images/arrows.png','images/arrows.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export_essonne/images/podium.png','images/podium.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export_essonne/images/ruban.png','images/ruban.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export_essonne/images/header-essonne.png','images/header-essonne.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export_essonne/images/fleche_question.png','images/fleche_question.png');
        }
        else {
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/quiz.css','quiz.css');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/js/jquery-1.6.4.min.js','js/jquery-1.6.4.min.js');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/js/quiz-1.0.js','js/quiz-1.0.js');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/bg.jpg','images/bg.jpg');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/bg2.jpg','images/bg2.jpg');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/coupe.png','images/coupe.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/arrows.png','images/arrows.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/arrows2.png','images/arrows2.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/bg-reponses.png','images/bg-reponses.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/fond.png','images/fond.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/podium.png','images/podium.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/ruban.png','images/ruban.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/grid-lite.png','images/grid-lite.png');
            $zip->addFile($this->container->getParameter('kernel.root_dir').'/../src/Appydo/QuizBundle/Resources/public/export/images/fleche_question.png','images/fleche_question.png');
        }
        
        $zip->addFromString('index.html', $content);
        
        $zip->close();
        
        return array();
    }
    /**
     * Export
     *
     * @Route("/export/{id}", name="export")
     * @Template()
     */
    public function exportAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Manager')->find($id);
        $query = $em->createQuery('SELECT s, q
            FROM AppydoQuizBundle:Subject s
            INNER JOIN s.quizz q
            WHERE s.manager=?1');
        $query->setParameter(1, $entity->getId());
        $subjects = $query->getResult();

        /*
        $subjects = $em->getRepository('AppydoQuizBundle:Subject')->findBy(
                    array('manager' => $entity->getId(), 'hide' => 'false'),
                    array('id'      => 'ASC')
                );
        $quizz = $em->getRepository('AppydoQuizBundle:Quiz')->findBy(
                    array('hide' => 'false'),
                    array('id'      => 'ASC')
                );
        */

        return array(
            'entity' => $entity,
            'subjects' => $subjects
            );
        
    }

    /**
     * Finds and displays a Manager entity.
     *
     * @Route("/{id}/show", name="manager_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Manager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Manager entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $subjects = $em->getRepository('AppydoQuizBundle:Subject')->findBy(
                    array('manager' => $entity->getId(), 'hide' => 'false'),
                    array('id'      => 'ASC')
                );

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'subjects' => $subjects,
            );
    }

    /**
     * Displays a form to create a new Manager entity.
     *
     * @Route("/new", name="manager_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Manager();
        $form   = $this->createForm(new ManagerType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Manager entity.
     *
     * @Route("/create", name="manager_create")
     * @Method("post")
     * @Template("AppydoQuizBundle:Manager:new.html.twig")
     */
    public function createAction()
    {

        $entity  = new Manager();
        $request = $this->getRequest();
        $form    = $this->createForm(new ManagerType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity->theme = $_POST['theme'];
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('subject_new', array('manager' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Manager entity.
     *
     * @Route("/{id}/edit", name="manager_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Manager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Manager entity.');
        }

        $editForm = $this->createForm(new ManagerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Manager entity.
     *
     * @Route("/{id}/update", name="manager_update")
     * @Method("post")
     * @Template("AppydoQuizBundle:Manager:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Manager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Manager entity.');
        }

        $editForm   = $this->createForm(new ManagerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('manager_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Manager entity.
     *
     * @Route("/{id}/delete", name="manager_delete")
     */
    public function deleteAction($id)
    {
        /*
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
         * 
         */
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoQuizBundle:Manager')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Manager entity.');
            }

            $em->remove($entity);
            $em->flush();
        // }

        return $this->redirect($this->generateUrl('manager'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
