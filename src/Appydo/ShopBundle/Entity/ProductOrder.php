<?php

namespace Appydo\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appydo\ShopBundle\Entity\ProductOrder
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProductOrder
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var date $created
     *
     * @ORM\Column(name="created", type="date")
     */
    private $created;

    /**
     * @var date $updated
     *
     * @ORM\Column(name="updated", type="date")
     */
    private $updated;
    
    /**
     * @var boolean $hide
     *
     * @ORM\Column(name="hide", type="boolean")
     */
    private $hide;

    public function __construct() {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }
 
    public function preUpdate() {
        $this->updated = new \DateTime();    
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /** 
     * Get created
     *
     * @return date 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param date $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get updated
     *
     * @return date 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set hide
     *
     * @param boolean $hide
     */
    public function setHide($hide)
    {
        $this->hide = $hide;
    }

    /**
     * Get hide
     *
     * @return boolean 
     */
    public function getHide()
    {
        return $this->hide;
    }
}