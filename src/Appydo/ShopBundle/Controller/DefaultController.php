<?php

namespace Appydo\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Appydo\ShopBundle\Entity\Category;

class DefaultController extends Controller
{
    /**
     * @Route("/{name}/shop/{id}/{category}", name="_appydo_shop_category", requirements = {"id" = "\d+","name" = "^((?!(admin)).)*$"})
     * @Template()
     */
    public function indexAction($name, $id, $category)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $query = $em->createQuery('SELECT p FROM AppydoTestBundle:Project p WHERE LOWER(p.name)=?1');
        $query->setParameter(1, $name);
        $project = $query->getSingleResult();
 
        $entities = $em->getRepository('AppydoShopBundle:Product')->findBy(
                    array('hide' => false, 'category' => $id),
                    array('id'   => 'ASC')
                );

        return array(
            'entities' => $entities,
            'project'  => $project,
            'theme'    => (isset($project))?$project->getTheme():'default',
            );
    }
}
