<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\Mail;
use Appydo\TestBundle\Form\MailType;

/**
 * Mail controller.
 *
 * @Route("/admin/mail")
 */
class MailController extends Controller
{
    /**
     * Lists all Mail entities.
     *
     * @Route("/", name="mail")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AppydoTestBundle:Mail')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Mail entity.
     *
     * @Route("/{id}/show", name="mail_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:Mail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mail entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Mail entity.
     *
     * @Route("/new", name="mail_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Mail();
        $form   = $this->createForm(new MailType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Mail entity.
     *
     * @Route("/create", name="mail_create")
     * @Method("post")
     * @Template("AppydoTestBundle:Mail:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Mail();
        $request = $this->getRequest();
        $form    = $this->createForm(new MailType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mail_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Mail entity.
     *
     * @Route("/{id}/edit", name="mail_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:Mail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mail entity.');
        }

        $editForm = $this->createForm(new MailType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Mail entity.
     *
     * @Route("/{id}/update", name="mail_update")
     * @Method("post")
     * @Template("AppydoTestBundle:Mail:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:Mail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mail entity.');
        }

        $editForm   = $this->createForm(new MailType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mail_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Mail entity.
     *
     * @Route("/{id}/delete", name="mail_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoTestBundle:Mail')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Mail entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mail'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
