<?php
namespace Appydo\TestBundle\Controller;

use Appydo\TestBundle\Entity\Topic;
use Appydo\TestBundle\Entity\Menu;
use Appydo\TestBundle\Entity\Stat;
use Appydo\TestBundle\Entity\User;
use Appydo\TestBundle\Entity\Project;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Form\ContactType;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_appydo")
     * @Route("/welcome", name="_appydo_welcome")
     * @Template()
     */
    public function indexAction()
    {   
        // $request = Request::createFromGlobals();
        // echo $request->query->get('foo');
        
        $em      = $this->getDoctrine()->getEntityManager();
        $user    = $this->getUser();
        $project = $em->getRepository('AppydoTestBundle:Project')->findOneBy(array('id'=>'1'));
 
        
        if (!$project) {
            $topics = null;
            $menus = null;
            // throw $this->createNotFoundException('Unable to find project.');
        } else {
            // hits + 1
            $project->setHits($project->getHits()+1);
            $em->persist($project);
            
            $stat = new Stat($this->currentPageURL(), $project, $this->getIp());
            $em->persist($stat);
            $em->flush();
            
            // Select all the topics for the default website
            $topics = $em->getRepository('AppydoTestBundle:Topic')->findBy(
                    array('project' => $project->getId(), 'hide'    => false),
                    array('id'      => 'DESC')
                );
            $menus = $em->getRepository('AppydoTestBundle:Menu')->findBy(
                    array('project' => $project->getId(), 'hide'    => false),
                    array('id'      => 'ASC')
                );
        }

	    /*
	    $stat = new Stat();
	    $stat->setHits($global->getHits()+1);
	    $em->persist($global);
	    $em->flush();
	    */
	    return array(
	        'project' => $project,
	        'topics'  => $topics,
	        'theme'   => (isset($project) and $project->getTheme()!='')?$project->getTheme():'default',
	        'menus'   => $menus,
	        );
	}
	
	/**
     * @Route("/user/profil/{id}/{name}", requirements = {"id" = "\d+"}, name="_appydo_profil")
     * @Template()
     */
    public function profilAction($id, $name)
    {
		$em     = $this->getDoctrine()->getEntityManager();
		$user   = $this->getUser();
		$profil = $em->getRepository('AppydoTestBundle:User')->findOneBy(array('id'=>$id));

		return array(
		   'profil' => $profil,
		   'theme'   => (isset($project) and $project->getTheme()!='')?$project->getTheme():'default',
		   );
    }

    /**
     * @Route("/{name}", requirements={"name" = "^((?!(admin|login|contact)).)([\w]|[\s])*"}, name="_appydo_project")
     * @Template()
     */
     public function projectAction($name) {

        if (empty($name)) return $this->index();
        $name = strtolower($name);
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();

        
        $query = $em->createQuery('SELECT p FROM AppydoTestBundle:Project p WHERE LOWER(p.name)=?1');
        $query->setParameter(1, $name);
        $project = $query->getSingleResult();
        
        if (!isset($project)) {
            throw $this->createNotFoundException('Unable to find project '.$name.'.');
        }
        
        if ($project->getHide()==true && ($user==null || $user!=$project->getAuthor())) {
            throw $this->createNotFoundException('This project is not public.');
        }
        
        // hits + 1
        $project->setHits($project->getHits()+1);
        $em->persist($project);
        
        $stat = new Stat($this->currentPageURL(), $project, $this->getIp());
        $em->persist($stat);
        
        $em->flush();

        // Select all the topics for the default website
        $topics = $em->getRepository('AppydoTestBundle:Topic')->findBy(
                array('project' => $project->getId(), 'hide'    => false),
                array('id'      => 'DESC')
            );
        $menus = $em->getRepository('AppydoTestBundle:Menu')->findBy(
                array('project' => $project->getId(), 'hide'    => false),
                array('id'      => 'ASC')
            );

        return $this->render('AppydoTestBundle:Default:index.html.twig',array(
            'project' => $project,
            'topics'  => $topics,
            'theme'   => ($project->getTheme()!='')?$project->getTheme():'default',
            'menus'   => $menus
            ));
    }
    
    /**
     * @Route("/{name}/{id}/{topic}/", name="_appydo_topic", requirements = {"id" = "\d+"}, name="_appydo_topic")
     * @Template()
     */
     public function topicAction($name, $id, $topic) {
        if (empty($name)) return $this->index();
        $name = strtolower($name);
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();

        $query = $em->createQuery('SELECT p FROM AppydoTestBundle:Project p WHERE LOWER(p.name)=?1');
        $query->setParameter(1, $name);
        $project = $query->getSingleResult();
        if (!isset($project)) {
            throw $this->createNotFoundException('Unable to find project '.$name.'.');
        }
        
        if ($project->getHide()==true && ($user==null || $user!=$project->getAuthor())) {
            throw $this->createNotFoundException('This project is not public.');
        }
        
        // hits + 1
        $project->setHits($project->getHits()+1);
        $em->persist($project);
        
        $stat = new Stat($this->currentPageURL(), $project, $this->getIp());
        $em->persist($stat);
        
        $em->flush();

        // Select the topic
        $topic = $em->getRepository('AppydoTestBundle:Topic')->findOneBy(
                array('project' => $project->getId(), 'id'    => $id, 'hide'    => false)
            );
        
        // Get the menu
        $menus = $em->getRepository('AppydoTestBundle:Menu')->findBy(
                array('project' => $project->getId(), 'hide'    => false),
                array('id'      => 'ASC')
            );
        
        /*
        List<Comment> comments = null;
        if (t != null && t.global != null && t.global.comment && t.comment) {
            comments = Comment.find("topic.id=?", id).fetch();
            System.out.println("comments");
            for (Comment comment : comments) {
                try {
                    Pattern p = Pattern.compile("<[^(em|b|u)]>(.*)</[^(em|b|u)]>");
                    Matcher m = p.matcher(comment.content);
                    while (m.find()) {
                        comment.content = m.replaceFirst(m.group(1));
                        m = p.matcher(comment.content);
                    }
                } catch (PatternSyntaxException pse) {
                }
            }
        }
         * 
         */

        return $this->render('AppydoTestBundle:Default:topic.html.twig',array(
            'project' => $project,
            'topic'   => $topic,
            'theme'   => ($project)?$project->getTheme():'default',
            'menus'   => $menus
            ));
    }
    
    /*
     * Gestion utilisateur
     */
    public function getUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }

    /**
     * @Route("/login/{name}", defaults={"name"=""}, name="_appydo_login"),
     * @Template()
     */
    public function loginAction($name)
    {
        $em      = $this->getDoctrine()->getEntityManager();
		if (!empty($name)) {
	        $query = $em->createQuery('SELECT p FROM AppydoTestBundle:Project p WHERE LOWER(p.name)=?1');
	        $query->setParameter(1, $name);
	        $project = $query->getSingleResult();
		} else {
			$project = null;
		}
        
        $request = $this->getRequest();
        $session = $request->getSession();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'project' => $project,
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
            'theme' => (isset($project) and $project->getTheme()!='') ? $project->getTheme() : 'default',
        );
    }

    /**
     * @Route("/admin/login_check", name="_appydo_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/admin/logout", name="_appydo_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/api/public", name="project_test")
     * @Template()
     */
    public function testAction()
    {
        $data = array(
            'status' => 'ok'
            );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    }
 
    function currentPageURL()
    {
        $pageURL = 'http';
        if (!empty($_SERVER['HTTPS'])) {$pageURL .= "s";}  
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    function getIP()
    { 
        if (getenv("HTTP_CLIENT_IP")) 
            $ip = getenv("HTTP_CLIENT_IP"); 
        else if(getenv("HTTP_X_FORWARDED_FOR")) 
            $ip = getenv("HTTP_X_FORWARDED_FOR"); 
        else if(getenv("REMOTE_ADDR")) 
            $ip = getenv("REMOTE_ADDR"); 
        else 
            $ip = "UNKNOWN";
        return $ip;
    }
}
