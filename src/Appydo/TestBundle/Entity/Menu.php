<?php
namespace Appydo\TestBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Appydo\TestBundle\Entity\Menu
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Menu
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\ManyToOne(targetEntity="Menu")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", nullable=true)
     *
     * @var Menu $menu
     */
    private $menu;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User $author
     */
    private $author;
    
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *
     * @var Project $project
     */
    private $project;
    
    /**
     * @var boolean $hide
     *
     * @ORM\Column(name="hide", type="boolean", nullable=true)
     */
    private $hide;
    
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set string
     *
     * @param string $string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get string
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set menu
     *
     * @param menu $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }
    
    /**
     * Set author
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    
    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        $this->author;
    }

    /**
     * Get menu
     *
     * @return menu 
     */
    public function getMenu()
    {
        return $this->menu;
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
    
    public function setCreated($created)
    {
        $this->created = $created;
    }
    /**
     * Get created
     *
     * @return date 
     */
    public function getCreated()
    {
        $this->created;
    }
    
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
        $this->updated;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
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