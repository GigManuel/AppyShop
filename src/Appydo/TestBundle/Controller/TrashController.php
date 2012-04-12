<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\Trash;
use Appydo\TestBundle\Form\TrashType;

/**
 * Trash controller.
 *
 * @Route("/trash")
 */
class TrashController extends Controller
{
    /**
     * Lists all Trash entities.
     *
     * @Route("/", name="trash")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AppydoTestBundle:Trash')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Trash entity.
     *
     * @Route("/{id}/show", name="trash_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:Trash')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trash entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Trash entity.
     *
     * @Route("/new", name="trash_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Trash();
        $form   = $this->createForm(new TrashType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Trash entity.
     *
     * @Route("/create", name="trash_create")
     * @Method("post")
     * @Template("AppydoTestBundle:Trash:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Trash();
        $request = $this->getRequest();
        $form    = $this->createForm(new TrashType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('trash_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Trash entity.
     *
     * @Route("/{id}/edit", name="trash_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:Trash')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trash entity.');
        }

        $editForm = $this->createForm(new TrashType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Trash entity.
     *
     * @Route("/{id}/update", name="trash_update")
     * @Method("post")
     * @Template("AppydoTestBundle:Trash:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:Trash')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trash entity.');
        }

        $editForm   = $this->createForm(new TrashType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('trash_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Trash entity.
     *
     * @Route("/{id}/delete", name="trash_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoTestBundle:Trash')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Trash entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('trash'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
