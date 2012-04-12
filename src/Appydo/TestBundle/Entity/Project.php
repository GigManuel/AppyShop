<?php

namespace Appydo\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appydo\TestBundle\Entity\Project
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Project
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
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text",nullable=true)
     */
    private $description;
    
    /**
     * @var text $information
     *
     * @ORM\Column(name="information", type="text",nullable=true)
     */
    private $information;

    /**
     * @var string $theme
     *
     * @ORM\Column(name="theme", type="string", length=50, nullable=true)
     */
    private $theme;

    /**
     * @var text $keywords
     *
     * @ORM\Column(name="keywords", type="text", nullable=true)
     */
    private $keywords;

    /**
     * @var string $banner
     *
     * @ORM\Column(name="banner", type="string", length=255, nullable=true)
     */
    private $banner;

    /**
     * @var text $note
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var string $footer
     *
     * @ORM\Column(name="footer", type="string", length=255, nullable=true)
     */
    private $footer;

    /**
     * @var string $subtitle
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @var integer $hits
     *
     * @ORM\Column(name="hits", type="boolean", nullable=true)
     */
    private $hits;

    /**
     * @var boolean $menu
     *
     * @ORM\Column(name="menu", type="boolean", nullable=true)
     */
    private $menu;

    /**
     * @var boolean $comment
     *
     * @ORM\Column(name="comment", type="boolean", nullable=true)
     */
    private $comment;

    /**
     * @var boolean $contact
     *
     * @ORM\Column(name="contact", type="boolean", nullable=true)
     */
    private $contact;

    /**
     * @var boolean $hide
     *
     * @ORM\Column(name="hide", type="boolean", nullable=true)
     */
    private $hide;
    
    /**
     * @var boolean $ban
     *
     * @ORM\Column(name="ban", type="boolean", nullable=true)
     */
    private $ban;

    /**
     * @var boolean $config
     *
     * @ORM\Column(name="config", type="boolean", nullable=true)
     */
    private $config;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User $author
     */
    private $author;
    
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
     * @ORM\OneToOne(targetEntity="\Milliweb\AppCenterBundle\Entity\Project", mappedBy="base")
     */
    #private $admin;
    
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set theme
     *
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * Get theme
     *
     * @return string 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set keywords
     *
     * @param text $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Get keywords
     *
     * @return text 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set banner
     *
     * @param string $banner
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;
    }

    /**
     * Get banner
     *
     * @return string 
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Set note
     *
     * @param text $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Get note
     *
     * @return text 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set footer
     *
     * @param string $footer
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
    }

    /**
     * Get footer
     *
     * @return string 
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set hits
     *
     * @param boolean $hits
     */
    public function setHits($hits)
    {
        $this->hits = $hits;
    }

    /**
     * Get hits
     *
     * @return boolean 
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set menu
     *
     * @param boolean $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get menu
     *
     * @return boolean 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set comment
     *
     * @param boolean $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return boolean 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set contact
     *
     * @param boolean $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get contact
     *
     * @return boolean 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set ban
     *
     * @param boolean $ban
     */
    public function setBan($ban)
    {
        $this->ban = $ban;
    }

    /**
     * Get ban
     *
     * @return boolean 
     */
    public function getBan()
    {
        return $this->ban;
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

    /**
     * Set config
     *
     * @param boolean $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Get config
     *
     * @return boolean 
     */
    public function getConfig()
    {
        return $this->config;
    }    

    public function getAdmin() {
        return $this->admin;
    }

    public function setAdmin($admin) {
        $this->admin = $admin;
    }
    
    public function __toString() {
        return $this->name;
    }
}
