<?php

namespace Appydo\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\ShopBundle\Entity\Payment;
use Appydo\ShopBundle\Form\PaymentType;

/**
 * Payment controller.
 *
 * @Route("/payment")
 */
class PaymentController extends Controller
{
    /**
     * Lists all Payment entities.
     *
     * @Route("/", name="payment")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AppydoShopBundle:Payment')->findAll();

        return array('entities' => $entities);
    }
    
    /**
     * payment_validation
     *
     * @Route("/payment/validation", name="payment_validation")
     * @Template()
     */
    public function validationAction()
    {
        
        $logger = $this->get('logger');
        $logger->info('-------------------------');
        foreach ($_POST as $key => $value) {
            $logger->info('PAYMENT VALIDATION : '.$key=$value);
        }
        $logger->info('-------------------------');
        exit();
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AppydoShopBundle:Payment')->findAll();

        return array('entities' => $entities);
    }
    
    /**
     * Lists all Payment entities.
     *
     * @Route("/payment/confirm", name="payment_confirm")
     * @Template()
     */
    public function confirmAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AppydoShopBundle:Payment')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Payment entity.
     *
     * @Route("/{id}/show", name="payment_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoShopBundle:Payment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Payment entity.
     *
     * @Route("/new", name="payment_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Payment();
        $form   = $this->createForm(new PaymentType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Payment entity.
     *
     * @Route("/create", name="payment_create")
     * @Method("post")
     * @Template("AppydoShopBundle:Payment:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Payment();
        $request = $this->getRequest();
        $form    = $this->createForm(new PaymentType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('payment_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Payment entity.
     *
     * @Route("/{id}/edit", name="payment_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoShopBundle:Payment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }

        $editForm = $this->createForm(new PaymentType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Payment entity.
     *
     * @Route("/{id}/update", name="payment_update")
     * @Method("post")
     * @Template("AppydoShopBundle:Payment:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoShopBundle:Payment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }

        $editForm   = $this->createForm(new PaymentType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('payment_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Payment entity.
     *
     * @Route("/{id}/delete", name="payment_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoShopBundle:Payment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Payment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('payment'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
