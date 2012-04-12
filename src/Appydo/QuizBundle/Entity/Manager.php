<?php

namespace Appydo\QuizBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * Appydo\TestBundle\Entity\Manager
 * 
 * @ORM\Table()
 * @ORM\Entity()
 */
class Manager
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    public $name;
    
    /**
     * @var string $theme
     *
     * @ORM\Column(name="theme", type="string", length=50)
     */
    public $theme;
    
    /**
     * @var integer $count
     *
     * @ORM\Column(name="count", type="integer")
     */
    public $count;
    
    /**
     * @ORM\OneToMany(targetEntity="Subject", mappedBy="manager")
     */
    public $subjects;
    
    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text",nullable=true)
     */
    public $description;
    
    /**
     * @var boolean $hide
     *
     * @ORM\Column(name="hide", type="boolean", nullable=true)
     */
    public $hide;
    
    public function __construct()
    {
        $this->count = 0;
        $this->theme = "";
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
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
    
    public function __toString() {
        return $this->name;
    }
}