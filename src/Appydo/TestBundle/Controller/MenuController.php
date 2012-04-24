<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\Menu;
use Appydo\TestBundle\Entity\Log;
use Appydo\TestBundle\Form\MenuType;

/**
 * Menu controller.
 *
 * @Route("/admin/menu")
 */
class MenuController extends Controller
{
    /**
     * Lists all Menu entities.
     *
     * @Route("/", name="menu")
     * @Template()
     */
    public function indexAction()
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $entities = $em->getRepository('AppydoTestBundle:Menu')->findBy(
                    array('project' => $user->getCurrentId()),
                    array('id'      => 'ASC')
                );

        return array(
            'entities' => $entities,
            'project'  => AdminController::getProject($em, $user)
            );
    }

    /**
     * Finds and displays a Menu entity.
     *
     * @Route("/{id}/show", name="menu_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $entity = $em->getRepository('AppydoTestBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }
        
        $menuinterns = $em->getRepository('AppydoTestBundle:MenuIntern')->findBy(
                    array(
                        'project' => $user->getCurrentId(),
                        'menu' => $entity->getId(),
                        ),
                    array('id'      => 'ASC')
                );
        
        $menuexterns = $em->getRepository('AppydoTestBundle:MenuExtern')->findBy(
                    array(
                        'project' => $user->getCurrentId(),
                        'menu' => $entity->getId(),
                        ),
                    array('id'      => 'ASC')
                );

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'menuexterns' => $menuexterns,
            'menuinterns' => $menuinterns
            );
    }

    /**
     * Displays a form to create a new Project entity.
     *
     * @Route("/new", name="menu_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Menu();
        $form   = $this->createForm(new MenuType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Menu entity.
     *
     * @Route("/create", name="menu_create")
     * @Method("post")
     * @Template("AppydoTestBundle:Menu:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Menu();
        $user = $this->get('security.context')->getToken()->getUser();
        $request = $this->getRequest();
        $form    = $this->createForm(new MenuType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity->setAuthor($user);
            $entity->setProject($user->getCurrent());
            $entity->setCreated(new \DateTime());
            $entity->setUpdated(new \DateTime());
            $entity->setHide(false);
            $em->persist($entity);
            
            $log = new Log("Create Menu", "Menu ".$entity->getId()." (".$entity->getName().")", $user);
            $em->persist($log);
            
            $em->flush();

            return $this->redirect($this->generateUrl('menu'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Menu entity.
     *
     * @Route("/{id}/edit", name="menu_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('AppydoTestBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        $editForm = $this->createForm(new MenuType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'project'     => AdminController::getProject($em, $user)
        );
    }

    /**
     * Edits an existing Menu entity.
     *
     * @Route("/{id}/update", name="menu_update")
     * @Method("post")
     * @Template("AppydoTestBundle:Menu:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('AppydoTestBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        $editForm   = $this->createForm(new MenuType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $entity->setUpdated(new \DateTime());
            $em->persist($entity);

            $log = new Log("Update Menu", "Menu ".$entity->getId()." (".$entity->getName().")", $user);
            $em->persist($log);
            $em->flush();
            return $this->redirect($this->generateUrl('menu_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Menu entity.
     *
     * @Route("/{id}/delete", name="menu_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoTestBundle:Menu')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Menu entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('menu'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
