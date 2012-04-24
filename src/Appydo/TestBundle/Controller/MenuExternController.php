<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\MenuExtern;
use Appydo\TestBundle\Form\MenuExternType;

/**
 * MenuExtern controller.
 *
 * @Route("/admin/menuextern")
 */
class MenuExternController extends Controller
{
    /**
     * Lists all MenuExtern entities.
     *
     * @Route("/", name="menuextern")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AppydoTestBundle:MenuExtern')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a MenuExtern entity.
     *
     * @Route("/{id}/show", name="menuextern_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:MenuExtern')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuExtern entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new MenuExtern entity.
     *
     * @Route("/{id}/new", name="menuextern_new")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new MenuExtern();
        $form   = $this->createForm(new MenuExternType(), $entity);

        return array(
            'id' => $id,
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new MenuExtern entity.
     *
     * @Route("/{id}/create", name="menuextern_create")
     * @Method("post")
     * @Template("AppydoTestBundle:MenuExtern:new.html.twig")
     */
    public function createAction($id)
    {
        $entity  = new MenuExtern();
        $request = $this->getRequest();
        $form    = $this->createForm(new MenuExternType(), $entity);
        $form->bindRequest($request);
        $user = $this->get('security.context')->getToken()->getUser();

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $entity->setMenu($id);
            $entity->setProject($user->getCurrentId());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('menuextern_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing MenuExtern entity.
     *
     * @Route("/{id}/edit", name="menuextern_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:MenuExtern')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuExtern entity.');
        }

        $editForm = $this->createForm(new MenuExternType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing MenuExtern entity.
     *
     * @Route("/{id}/update", name="menuextern_update")
     * @Method("post")
     * @Template("AppydoTestBundle:MenuExtern:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:MenuExtern')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuExtern entity.');
        }

        $editForm   = $this->createForm(new MenuExternType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('menuextern_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a MenuExtern entity.
     *
     * @Route("/{id}/delete", name="menuextern_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoTestBundle:MenuExtern')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MenuExtern entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('menuextern'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
