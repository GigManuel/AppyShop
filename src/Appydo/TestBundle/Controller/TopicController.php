<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\Topic;
use Appydo\TestBundle\Entity\Document;
use Appydo\TestBundle\Entity\Log;
use Appydo\TestBundle\Form\TopicType;

/**
 * Topic controller.
 *
 * @Route("/admin/topic")
 */
class TopicController extends Controller
{
    /**
     * Lists all Topic entities.
     *
     * @Route("/", defaults={"page"="1"}, name="topic"),
     * @Route("/{page}", name="_topic_list", requirements = {"page" = "\d+"})
     * @Template()
     */
    public function indexAction($page)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if ($this->getRequest()->getMethod() == 'POST') {
            // $form->bindRequest($this->getRequest());
        }

        $max = 10;
        $em  = $this->getDoctrine()->getEntityManager();
        // ->findBy($form->getData()->toArray())
        $qb = $em->createQueryBuilder();

        $qb->select('t')
            ->from('AppydoTestBundle:Topic', 't')
            ->where('t.project=:current')
            ->andWhere('t.author=:user')
			->andWhere('t.hide=false')
            ->orderBy('t.id', 'ASC')
            ->setFirstResult(($page-1)*$max)
            ->setMaxResults($max)
            ->setParameter('current',$user->getCurrent())
            ->setParameter('user',$user);

        $query = $qb->getQuery();
        $entities = $query->getResult();
        
        $topic = new Topic();
        $form = $this->createFormBuilder($topic)
            ->add('name')
            // ->add('created')
            ->add('author')
            ->getForm();
        
        return array(
            'previous' => ($page==1)?$page:$page-1,
            'next' => ($page==10)?$page:$page+1,
            'entities' => $entities,
            'filter' => $form->createView(),
            'project'  => AdminController::getProject($em, $user)
            );
    }
    
    /**
     * Lists all Topic entities.
     *
     * @Route("/trash", defaults={"page"="1"}, name="topic_trash"),
     * @Route("/trash/{page}", name="_topic_trash_list", requirements = {"page" = "\d+"})
     * @Template()
     */
    public function trashAction($page)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if ($this->getRequest()->getMethod() == 'POST') {
            // $form->bindRequest($this->getRequest());
        }

        $max = 10;
        $em  = $this->getDoctrine()->getEntityManager();
        // ->findBy($form->getData()->toArray())
        $qb = $em->createQueryBuilder();

        $qb->select('t')
            ->from('AppydoTestBundle:Topic', 't')
            ->where('t.project=:current')
            ->andWhere('t.author=:user')
			->andWhere('t.hide=true')
            ->orderBy('t.id', 'ASC')
            ->setFirstResult(($page-1)*$max)
            ->setMaxResults($max)
            ->setParameter('current',$user->getCurrent())
            ->setParameter('user',$user);

        $query = $qb->getQuery();
        $entities = $query->getResult();
        
        $topic = new Topic();
        $form = $this->createFormBuilder($topic)
            ->add('name')
            // ->add('created')
            ->add('author')
            ->getForm();
        
        return array(
            'previous' => ($page==1)?$page:$page-1,
            'next' => ($page==10)?$page:$page+1,
            'entities' => $entities,
            'filter' => $form->createView(),
            'project'  => AdminController::getProject($em, $user)
            );
    }

    /**
     * Finds and displays a Topic entity.
     *
     * @Route("/{id}/show", name="topic_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:Topic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Topic entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            );
    }

    /**
     * Displays a form to create a new Topic entity.
     *
     * @Route("/new", name="topic_new")
     * @Template("AppydoTestBundle:Topic:tinymce/new.html.twig")
     */
    public function newAction()
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $entity = new Topic();
        $form   = $this->createForm(new TopicType(), $entity);
        
        $document = new Document();
        
        $dir = $document->getUploadRootDir($user->getCurrentId());

        if (!is_dir($dir)) {
            @mkdir($dir);
            $uploadDirExit = is_dir($dir);
        } else {
            $uploadDirExit = true;
        }
        
        $tab = array();
        $sizes = array();
        if ($uploadDirExit) {

            if ($handle = opendir($dir)) {

                while (false !== ($entry = readdir($handle))) {
                    if (!in_array($entry, array(".", ".."))) {
                        array_push($tab, $entry);
                        array_push($sizes, round(filesize($dir . '/' . $entry) / 1024));
                    }
                }

                closedir($handle);
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'project'  => AdminController::getProject($em, $user),
            'listFiles' => $tab,
            'sizes' => $sizes,
            'dir_exists' => $uploadDirExit
        );
    }

    /**
     * Creates a new Topic entity.
     *
     * @Route("/create", name="topic_create")
     * @Method("post")
     * @Template("AppydoTestBundle:Topic:tinymce/new.html.twig")
     */
    public function createAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }
        $entity  = new Topic();
        $request = $this->getRequest();
        
        // $topic = $request->request->get('appydo_testbundle_topictype');
        // $topic['author'] = ;
        // $request->request->set('appydo_testbundle_topictype',$topic);
        
        $form    = $this->createForm(new TopicType(), $entity);
        
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity->setAuthor($user);
            $entity->setProject($user->getCurrent());
            $entity->setCreated(new \DateTime());
            $entity->setUpdated(new \DateTime());
            $em->persist($entity);
            $log = new Log("Create Topic", "Topic ".$entity->getId()." (".$entity->getName().")", $user);
            $em->persist($log);
            $em->flush();
            return $this->redirect($this->generateUrl('topic_edit', array('id' => $entity->getId())));
            // return $this->redirect($this->generateUrl('topic'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Topic entity.
     *
     * @Route("/{id}/edit", name="topic_edit")
     * @Template("AppydoTestBundle:Topic:tinymce/edit.html.twig")
     */
    public function editAction($id)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('AppydoTestBundle:Topic')->find($id);
        
        $document = new Document();
        $dir = $document->getUploadRootDir($user->getCurrentId());

        if (!is_dir($dir)) {
            @mkdir($dir);
            $uploadDirExit = is_dir($dir);
        } else {
            $uploadDirExit = true;
        }
        
        $tab = array();
        $sizes = array();
        if ($uploadDirExit) {

            if ($handle = opendir($dir)) {

                while (false !== ($entry = readdir($handle))) {
                    if (!in_array($entry, array(".", ".."))) {
                        array_push($tab, $entry);
                        array_push($sizes, round(filesize($dir . '/' . $entry) / 1024));
                    }
                }

                closedir($handle);
            }
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Topic entity.');
        }

        $editForm = $this->createForm(new TopicType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'project'  => AdminController::getProject($em, $user),
            'listFiles' => $tab,
            'sizes' => $sizes,
            'dir_exists' => $uploadDirExit
        );
    }

    /**
     * Edits an existing Topic entity.
     *
     * @Route("/{id}/update", name="topic_update")
     * @Method("post")
     * @Template("AppydoTestBundle:Topic:tinymce/edit.html.twig")
     */
    public function updateAction($id)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('AppydoTestBundle:Topic')->find($id);
        $create = $entity->getCreated();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Topic entity.');
        }

        $editForm   = $this->createForm(new TopicType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $entity->setAuthor($user);
            $entity->setProject($user->getCurrent());
            $entity->setCreated($create);
            $entity->setUpdated(new \DateTime());
            $em->persist($entity);
            $log = new Log("Update Topic", "Topic ".$entity->getId()." (".$entity->getName().")", $user);
            $em->persist($log);
            $em->flush();

            return $this->redirect($this->generateUrl('topic_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Topic entity.
     *
     * @Route("/{id}/delete", name="topic_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AppydoTestBundle:Topic')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Topic entity.');
            }

            $em->remove($entity);
            $log = new Log("Delete topic", "Topic ".$entity->getId()." (".$entity->getName().")", $user);
            $em->persist($log);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('topic'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
