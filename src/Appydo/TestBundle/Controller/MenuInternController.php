<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\MenuIntern;
use Appydo\TestBundle\Form\MenuInternType;

/**
 * MenuIntern controller.
 *
 * @Route("/admin/menuintern")
 */
class MenuInternController extends Controller
{
    /**
     * Lists all MenuIntern entities.
     *
     * @Route("/", name="menuintern")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('AppydoTestBundle:MenuIntern')->findAll();
        return array('entities' => $entities);
    }

    /**
     * Finds and displays a MenuIntern entity.
     *
     * @Route("/{id}/show", name="menuintern_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppydoTestBundle:MenuIntern')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuIntern entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new MenuIntern entity.
     *
     * @Route("/new/{id}", name="menuintern_new")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new MenuIntern();
        $form   = $this->createForm(new MenuInternType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id'     => $id
        );
    }

    /**
     * Creates a new MenuIntern entity.
     *
     * @Route("/create", name="menuintern_create")
     * @Method("post")
     * @Template("AppydoTestBundle:MenuIntern:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new MenuIntern();
        $request = $this->getRequest();
        $form    = $this->createForm(new MenuInternType(), $entity);
        $form->bindRequest($request);
        $user = $this->get('security.context')->getToken()->getUser();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity->setProject($user->getCurrent());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('menuintern_edit', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing MenuIntern entity.
     *
     * @Route("/{id}/edit", name="menuintern_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:MenuIntern')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuIntern entity.');
        }

        $editForm = $this->createForm(new MenuInternType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing MenuIntern entity.
     *
     * @Route("/{id}/update", name="menuintern_update")
     * @Method("post")
     * @Template("AppydoTestBundle:MenuIntern:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:MenuIntern')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuIntern entity.');
        }

        $editForm   = $this->createForm(new MenuInternType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('menuintern_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a MenuIntern entity.
     *
     * @Route("/{id}/delete", name="menuintern_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoTestBundle:MenuIntern')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MenuIntern entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('menuintern'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
