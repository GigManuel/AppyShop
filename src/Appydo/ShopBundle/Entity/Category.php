<?php

namespace Appydo\ShopBundle\Entity;

use Appydo\TestBundle\Entity\Project;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Appydo\ShopBundle\Entity\Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
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
     * @ORM\ManyToOne(targetEntity="\Appydo\TestBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *
     * @var Project $project
     */
    private $project;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string $string
     *
     * @ORM\Column(name="string", type="text")
     */
    private $description;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var boolean $hide
     *
     * @ORM\Column(name="hide", type="boolean")
     */
    private $hide;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    public $path;
    
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().$this->id.'/'.$this->path;
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/categories/';
    }

    public function getUploadRootDir($id)
    {
        if (null === $id) {
            return;
        }
        return __DIR__.'/../../../../www/uploads/categories/'.$id;
    }
    
    public function upload($id)
    {
        if (null === $this->file) {
            return;
        }
        
        $image = new SimpleImage();
        $image->load($this->file);
        if ($image->getWidth()) {
            $image->resizeToWidth(100);
            $image->save($this->file);
        }
        
        $this->file->move($this->getUploadRootDir($this->id), $this->file->getClientOriginalName());

        $this->path = $this->file->getClientOriginalName();
        $this->file = null;
    }

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
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set project
     *
     * @param string $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * Get project
     *
     * @return string 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created
     *
     * @param date $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
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
    
    public function __toString() {
        return $this->name;
    }
}