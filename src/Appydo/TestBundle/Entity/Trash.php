<?php

namespace Appydo\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appydo\TestBundle\Entity\Trash
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\PreUpdate
 */
class Trash
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var boolean $hide
     *
     * @ORM\Column(name="hide", type="boolean", nullable=true)
     */
    private $hide;

    /**
     * @var boolean $rss
     *
     * @ORM\Column(name="rss", type="boolean", nullable=true)
     */
    private $rss;

    /**
     * @var boolean $comment
     *
     * @ORM\Column(name="comment", type="boolean", nullable=true)
     */
    private $comment;

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
     * @var bigint $translate_index
     *
     * @ORM\Column(name="translate_index", type="bigint", nullable=true)
     */
    private $translate_index;

    /**
     * @var string $language
     *
     * @ORM\Column(name="language", type="string", length=255, nullable=true)
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *
     * @var Project $project
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User $author
     */
    private $author;

    
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
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
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
     * Set rss
     *
     * @param boolean $rss
     */
    public function setRss($rss)
    {
        $this->rss = $rss;
    }

    /**
     * Get rss
     *
     * @return boolean 
     */
    public function getRss()
    {
        return $this->rss;
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
     * Set created
     *
     * @param datetime $created
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
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set translate_index
     *
     * @param bigint $translateIndex
     */
    public function setTranslateIndex($translateIndex)
    {
        $this->translate_index = $translateIndex;
    }

    /**
     * Get translate_index
     *
     * @return bigint 
     */
    public function getTranslateIndex()
    {
        return $this->translate_index;
    }

    /**
     * Set language
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
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
        return $this->author;
    }
    
    public function __toString() {
        return $this->name;
    }
}