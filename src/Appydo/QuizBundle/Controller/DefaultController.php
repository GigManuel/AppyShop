<?php

namespace Appydo\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_appydo_quiz")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('manager'));
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $entities = $em->getRepository('AppydoQuizBundle:Manager')->findBy(
                    array('hide' => 'false'),
                    array('id'      => 'DESC')
                );
        
        return array('entities' => $entities);
    }
    
    /**
     * @Route("/history", name="_appydo_quiz_history")
     * @Template()
     */
    public function historyAction()
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $entities = $em->getRepository('AppydoTestBundle:Log')->findBy(
                    array('project' => $user->getCurrentId()),
                    array('id'      => 'DESC')
                );
        
        return array('name' => '$name');
    }
    
    /**
     * @Route("/trash", name="_appydo_quiz_trash")
     * @Template()
     */
    public function trashAction()
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $entities = $em->getRepository('AppydoTestBundle:Log')->findBy(
                    array('project' => $user->getCurrentId()),
                    array('id'      => 'DESC')
                );
        
        return array('name' => '$name');
    }
}
