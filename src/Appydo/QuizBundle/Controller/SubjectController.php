<?php
namespace Appydo\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\QuizBundle\Entity\Subject;
use Appydo\QuizBundle\Entity\Quiz;
use Appydo\QuizBundle\Form\SubjectType;

/**
 * Subject controller.
 *
 * @Route("/subject")
 */
class SubjectController extends Controller
{
    /**
     * Lists all Subject entities.
     *
     * @Route("/", name="subject")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AppydoQuizBundle:Subject')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Subject entity.
     *
     * @Route("/{id}/show", name="subject_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Subject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Subject entity.');
        }
        
        $quizs = $em->getRepository('AppydoQuizBundle:Quiz')->findBy(
                    array('subject' => $entity->getId(), 'hide' => 'false'),
                    array('id'      => 'ASC')
                );
        
        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'quizs'       => $quizs,
            'cpt_quiz'    => count($quizs)
            );
    }

    /**
     * Displays a form to create a new Subject entity.
     *
     * @Route("/{manager}/new", name="subject_new")
     * @Template()
     */
    public function newAction($manager)
    {
        $entity = new Subject();
        $em = $this->getDoctrine()->getEntityManager();
        $form   = $this->createForm(new SubjectType(), $entity);
        
        $subjects = $em->getRepository('AppydoQuizBundle:Subject')->findBy(
                    array('manager' => $manager, 'hide' => 'false'),
                    array('id'      => 'ASC')
                );

        return array(
            'cpt' => count($subjects)+1,
            'entity' => $entity,
            'form'   => $form->createView(),
            'manager' => $manager
        );
    }

    /**
     * Creates a new Subject entity.
     *
     * @Route("/create", name="subject_create")
     * @Method("post")
     * @Template("AppydoQuizBundle:Subject:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Subject();
        $request = $this->getRequest();

        $form    = $this->createForm(new SubjectType(), $entity);
        $form->bindRequest($request);
        $em = $this->getDoctrine()->getEntityManager();
       
        if ($form->isValid()) {
            
            $em->persist($entity);
            $quiz     = $request->request->get('quiz');
            $correct  = $request->request->get('correct');
            $cpt_quiz = count($quiz);

            for ($i=0;$i<$cpt_quiz;$i++) {
                if (isset($quiz[$i]) and !empty($quiz[$i])) {
                    $q          = new Quiz();
                    $q->name    = $quiz[$i];
                    $q->subject = $entity;
                    $q->hide    = false;
                    if (isset($correct))
                        $q->correct = (in_array($i+1,$correct));
                    else
                        $q->correct = false;
                    $em->persist($q);
                }
            }

            $em->flush();

            $dir = $entity->getUploadRootDir($entity->getId());

            if (!is_dir($dir)) {
                @mkdir($dir);
                $uploadDirExit = is_dir($dir);
            } else {
                $uploadDirExit = true;
            }

            $entity->upload($entity->getId());
            $entity->uploadExplication($entity->getId());
            
            $em->persist($entity);
            $em->flush();
            
            $subjects = $em->getRepository('AppydoQuizBundle:Subject')->findBy(
                array('manager' => $entity->getId(), 'hide' => 'false'),
                array('id'      => 'ASC')
            );

            return $this->redirect($this->generateUrl('subject_new', array(
                'manager' => $entity->manager->getId(),
                'cpt' => count($subjects)+1,
                )));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'cpt' => 1,
        );
    }

    /**
     * Displays a form to edit an existing Subject entity.
     *
     * @Route("/{id}/edit", name="subject_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Subject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Subject entity.');
        }

        $editForm = $this->createForm(new SubjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Subject entity.
     *
     * @Route("/{id}/update", name="subject_update")
     * @Method("post")
     * @Template("AppydoQuizBundle:Subject:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Subject')->find($id);
        $manager = $entity->manager;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Subject entity.');
        }

        $editForm   = $this->createForm(new SubjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $entity->manager = $manager;
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('subject_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Subject entity.
     *
     * @Route("/{id}/delete", name="subject_delete")
     * @Method("get")
     */
    public function deleteAction($id)
    {
//        $form = $this->createDeleteForm($id);
//        $request = $this->getRequest();
//
//        $form->bindRequest($request);
//
//        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoQuizBundle:Subject')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Subject entity.');
            }
            $id_manager = $entity->manager->getId();
            $em->remove($entity);
            $em->flush();
//        }

        return $this->redirect($this->generateUrl('manager_show', array('id' => $id_manager)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
