<?php

namespace Facebook\FamousBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $app_id = "374508785922632";

        $canvas_page = "http://127.0.0.1/~jimmyy/Symfony2/www/app_dev.php/facebook/famous/";

        $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            . $app_id . "&redirect_uri=" . urlencode($canvas_page);

        $signed_request = $_REQUEST["signed_request"];

        list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        if (empty($data["user_id"])) {
            echo("<script> top.location.href='" . $auth_url . "'</script>");
        } else {
            echo ("Welcome User: " . $data["user_id"]);
        }
        
        return array('name' => $data["user_id"]);
    }
    
    /**
     * @Route("/result", name="facebook_famous_result")
     * @Template()
     */
    public function resultAction()
    {
        $famous = array(
            'un peu',
            'beaucoup',
            'enormement',
            'pas du tout',
        );
        $time = array(
            'bientôt.',
            'très vite.',
            'dans quelques temps encore.',
            'dans quelques années, mais cela va venir vite !',
            'après sa mort.',
        );
        return array(
            'famous' => $famous,
            'time'   => $time
            );
    }
}
