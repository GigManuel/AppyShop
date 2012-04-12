<?php

namespace Appydo\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appydo\TestBundle\Entity\Stat;

/**
 * Stat controller.
 *
 * @Route("/admin/stat")
 */
class StatController extends Controller
{
    /**
     * Lists all Stat entities.
     *
     * @Route("/", defaults={"page"="1"}, name="stat")
     * @Route("/{page}", name="stat_list", requirements = {"page" = "\d+"})
     * @Template()
     */
    public function indexAction($page)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $max = 10;
        $em  = $this->getDoctrine()->getEntityManager();
        // ->findBy($form->getData()->toArray())
        $qb = $em->createQueryBuilder();

        $qb->select('s')
            ->from('AppydoTestBundle:Stat', 's')
            ->where('s.project=:current')
            ->orderBy('s.id', 'ASC')
            ->setFirstResult(($page-1)*$max)
            ->setMaxResults($max)
            ->setParameter('current',$user->getCurrent())
            ;

        $query = $qb->getQuery();
        $entities = $query->getResult();

        return array(
            'previous' => ($page==1)?$page:$page-1,
            'next' => ($page==10)?$page:$page+1,
            'entities' => $entities,
            'project'  => AdminController::getProject($em, $user)
            );
    }

    /**
     * Finds and displays a Stat entity.
     *
     * @Route("/{id}/show", name="stat_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppydoTestBundle:Stat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stat entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

}
