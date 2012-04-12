<?php

namespace Appydo\QuizBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * Appydo\TestBundle\Entity\Quiz
 * 
 * @ORM\Table()
 * @ORM\Entity()
 */
class Quiz
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
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="quizz")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     *
     * @var Subject $subject
     */
    public $subject;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="text")
     */
    public $name;
    
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
    
    /**
     * @var boolean $correct
     *
     * @ORM\Column(name="correct", type="boolean", nullable=true)
     */
    public $correct;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    public function getUploadRootDir($project_id)
    {
        if (null === $project_id) {
            return;
        }
        return __DIR__.'/../../../../www/uploads/'.$project_id;
    }
    
    public function upload($project_id)
    {
        if (null === $this->file) {
            return;
        }
        $this->file->move($this->getUploadRootDir($project_id), $this->file->getClientOriginalName());
        $this->file = null;
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