<?php

namespace Appydo\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\QuizBundle\Entity\Quiz;
use Appydo\QuizBundle\Form\QuizType;

/**
 * Quiz controller.
 *
 * @Route("/quiz")
 */
class QuizController extends Controller
{
    /**
     * Lists all Quiz entities.
     *
     * @Route("/", name="quiz")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AppydoQuizBundle:Quiz')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Quiz entity.
     *
     * @Route("/{id}/show", name="quiz_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Quiz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quiz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            );
    }

    /**
     * Displays a form to create a new Quiz entity.
     *
     * @Route("/{subject}/new", name="quiz_new")
     * @Template()
     */
    public function newAction($subject)
    {
        $entity = new Quiz();
        $form   = $this->createForm(new QuizType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'subject' => $subject
        );
    }

    /**
     * Creates a new Quiz entity.
     *
     * @Route("/{subject}/create", name="quiz_create")
     * @Method("post")
     * @Template("AppydoQuizBundle:Quiz:new.html.twig")
     */
    public function createAction($subject)
    {
        $entity  = new Quiz();
        $request = $this->getRequest();
        $form    = $this->createForm(new QuizType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('subject_show', array('id' => $entity->subject->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'subject' => $subject
        );
    }

    /**
     * Displays a form to edit an existing Quiz entity.
     *
     * @Route("/{id}/edit", name="quiz_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Quiz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quiz entity.');
        }

        $editForm = $this->createForm(new QuizType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Quiz entity.
     *
     * @Route("/{id}/update", name="quiz_update")
     * @Method("post")
     * @Template("AppydoQuizBundle:Quiz:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoQuizBundle:Quiz')->find($id);
        $subject = $entity->subject;
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quiz entity.');
        }

        $editForm   = $this->createForm(new QuizType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $entity->subject = $subject;
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('quiz_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Quiz entity.
     *
     * @Route("/{id}/delete", name="quiz_delete")
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
            $entity = $em->getRepository('AppydoQuizBundle:Quiz')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Quiz entity.');
            }
            $id_subject = $entity->subject->getId();
            $em->remove($entity);
            $em->flush();
//        }

        return $this->redirect($this->generateUrl('subject_show', array('id' => $id_subject)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
