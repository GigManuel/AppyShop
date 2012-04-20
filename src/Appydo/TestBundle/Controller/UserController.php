<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\User;
use Appydo\TestBundle\Entity\Project;
use Appydo\TestBundle\Form\UserType;
use Appydo\TestBundle\Form\UserSignup;
use Appydo\TestBundle\Form\UserEdit;

/**
 * User controller.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AppydoTestBundle:User')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", name="user_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('AppydoTestBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'project'  => AdminController::getProject($em, $user)
            );
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/signup/", defaults={"name"=""}, name="user_new"),
     * @Route("/signup/{name}", name="user_new_")
     * @Template()
     */
    public function newAction($name)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        
        $entity = new User();
        $form   = $this->createForm(new UserSignup(), $entity);
<<<<<<< HEAD
        
        $query = $em->createQuery('SELECT p FROM AppydoTestBundle:Project p WHERE LOWER(p.name)=?1');
        $query->setParameter(1, $name);
        $project = $query->getSingleResult();
=======
		if (!empty($name)) {
        	$query = $em->createQuery('SELECT p FROM AppydoTestBundle:Project p WHERE LOWER(p.name)=?1');
        	$query->setParameter(1, $name);
        	$project = $query->getSingleResult();
		} else {
			$project = null;
		}
>>>>>>> 854df3933620601437d3a532f88850f2ed26ecc1

        return array(
            'entity'  => $entity,
            'form'    => $form->createView(),
            'project' => $project,
<<<<<<< HEAD
            'theme'   => (isset($project))?$project->getTheme():'default',
=======
            'theme'   => (isset($project) and $project->getTheme()!='') ? $project->getTheme() : 'default',
>>>>>>> 854df3933620601437d3a532f88850f2ed26ecc1
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="user_create")
     * @Method("post")
     * @Template("AppydoTestBundle:User:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new User();
        
        $request = $this->getRequest();
        $form    = $this->createForm(new UserSignup(), $entity);
        $form->bindRequest($request);
        $errors  = $this->get('validator')->validate($entity, array('registration'));
        if ($form->isValid()) {
            
            $factory = $this->container->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $entity->setPassword($encoder->encodePassword($entity->getPassword(), $entity->getSalt()));
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);

            /*
             * Create a project for the user
             */
            $project = new Project();
            $project->setName($entity->getUsername());
            $project->setAuthor($entity);
            $project->setCreated(new \DateTime());
            $project->setUpdated(new \DateTime());
            $em->persist($project);
            
            $entity->setCurrent($project);
            $em->persist($entity);
            
            $em->flush();
            
            // create the authentication token
            $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
                $entity,
                null,
                'main',
                $entity->getRoles()
            );
            // give it to the security context
            // $this->get('session')->set('_security_'.'main', serialize($token));
            $this->container->get('security.context')->setToken($token);
                
            return $this->redirect($this->generateUrl('user_edit', array('id' => $entity->getId())));
        }

        return array(
            // 'project' => $project,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('AppydoTestBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserEdit(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'project'  => AdminController::getProject($em, $user)
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/update", name="user_update")
     * @Method("post")
     * @Template("AppydoTestBundle:User:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppydoTestBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm   = $this->createForm(new UserEdit(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($entity);
        
        $request = $this->getRequest();
        
        $data = $request->request->get('appydo_testbundle_usertype');
        if (!empty($data['oldPassword'])) {
            $data['oldPassword'] = $encoder->encodePassword($data['oldPassword'], $entity->getSalt());
            $request->request->set('appydo_testbundle_usertype',$data);
        }

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            if (!empty($entity->oldPassword)) {
                $entity->setPassword($encoder->encodePassword($entity->newPassword, $entity->getSalt()));
            }
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}/delete", name="user_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoTestBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
