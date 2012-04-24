<?php
namespace Appydo\CaptchaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\CaptchaBundle\Controller\SimpleCaptcha;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * Display captcha.jpg
     *
     * @Route("/image.jpg", name="captcha")
     */
    public function indexAction()
    {
        $headers = array('Content-Type'     => 'image/png', 'Content-Disposition' => 'inline; filename="image.png"');
        
        //session_start();
        $captcha = new SimpleCaptcha();

        // OPTIONAL Change configuration...
        //$captcha->wordsFile = 'words/es.php';
        //$captcha->session_var = 'secretword';
        //$captcha->imageFormat = 'png';
        //$captcha->scale = 3; $captcha->blur = true;
        $captcha->resourcesPath = __DIR__.'/../Resources/';

        // OPTIONAL Simple autodetect language example
        /*
        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $langs = array('en', 'es');
            $lang  = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (in_array($lang, $langs)) {
                $captcha->wordsFile = "words/$lang.php";
            }
        }
        */
        // Image generation
        return new Response($captcha->CreateImage(), 200, $headers);
    }
    public static function validCaptcha() {
        /** Validate captcha */
        return !(empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']);
    }
}
