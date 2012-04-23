<?php

namespace Appydo\ContactBundle\Controller;

use Appydo\ContactBundle\Entity\Contact;

use Appydo\TestBundle\Entity\Project;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\ContactBundle\Form\ContactType;

class DefaultController extends Controller
{
    /**
     * @Route("/{name}", name="_appydo_contact")
     * @Template()
     */
    public function indexAction($name)
    {
        
        $contact = new Contact();
        $form    = $this->createForm(new ContactType(),$contact);
        $em      = $this->getDoctrine()->getEntityManager();
        
        $query = $em->createQuery('SELECT p FROM AppydoTestBundle:Project p WHERE LOWER(p.name)=?1');
        $query->setParameter(1, $name);
        $project = $query->getSingleResult();

        return array(
            'project' => $project,
            'contact' => $form->createView(),
            'theme'   => (isset($project) and $project->getTheme()!='')?$project->getTheme():'default',
            );
    }

    /**
     * @Route("/send", name="_appydo_send")
     * @Method("post")
     * @Template()
     */
    public function sendAction()
    {
        $request = $this->getRequest();
        $contact = $request->request->get('contact');
        $entity  = new Contact();
        $form    = $this->createForm(new ContactType(), $entity);
        $form->bindRequest($request);
        if ($form->isValid()) {
            $message = \Swift_Message::newInstance()
            ->setSubject($contact['subject'])
            ->setFrom($contact['email'])
            ->setTo('recipient@example.com')
            ->setBody($this->renderView('ContactBundle:Mail:email.txt.twig', array('name' => $contact['name'])));
            // $this->get('mailer')->send($message);
            
            // Send a copy to the author
            if (isset($contact['copy'])) {
                $message = \Swift_Message::newInstance()
                ->setSubject($contact['subject'])
                ->setFrom($contact['email'])
                ->setTo($contact['email'])
                ->setBody($this->renderView('ContactBundle:Mail:email.txt.twig', array('name' => $contact['name'])));
            } 
            
            return array(
                'name'    => $contact['name'],
                'subject' => $contact['subject'],
                'message' => $contact['message'],
                'email'   => $contact['email'],
                'copy'    => isset($contact['copy']),
                'theme'   => (isset($project) and $project->getTheme()!='') ? $project->getTheme() : 'default',
                );
        }
        return $this->render("ContactBundle:Default:index.html.twig", array(
            'entity'  => $entity,
            'contact' => $form->createView(),
            'theme'   => (isset($project) and $project->getTheme()!='')?$project->getTheme():'default',
        ));
    }
}
