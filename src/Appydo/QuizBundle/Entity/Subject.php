<?php
namespace Appydo\QuizBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Appydo\QuizBundle\SimpleImage as SimpleImage;

/**
 * Appydo\QuizBundle\Entity\Subject
 * 
 * @ORM\Table()
 * @ORM\Entity()
 */
class Subject
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
     * @ORM\ManyToOne(targetEntity="Manager", inversedBy="subjects")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     *
     * @var Manager $manager
     */
    public $manager;
    
    /**
     * @ORM\OneToMany(targetEntity="Quiz", mappedBy="subject")
     */
    public $quizz;
    
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
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file_explication;
    
    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    public $path;
    
    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    public $path_explication;
    
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().$this->id.'/'.$this->path;
    }
    
    public function getWebPathExplication()
    {
        return null === $this->path ? null : $this->getUploadDir().$this->id.'/'.$this->path_explication;
    }
    
    public function getUploadRootDir($id)
    {
        if (null === $id) {
            return;
        }
        return __DIR__.'/../../../../www/uploads/usinedoc/'.$id;
    }
    
    public function upload($id)
    {
        if (null === $this->file) {
            return;
        }
        
        $image = new SimpleImage();
        $image->load($this->file);
        if ($image->getWidth()) {
            $image->resizeToWidth(700);
            $image->save($this->file);
        }
        
        $this->file->move($this->getUploadRootDir($this->id), $this->file->getClientOriginalName());

        $this->path = $this->file->getClientOriginalName();
        $this->file = null;
    }
    
    public function uploadExplication($id)
    {
        if (null === $this->file_explication) {
            return;
        }
        $this->file_explication->move($this->getUploadRootDir($id), $this->file_explication->getClientOriginalName());
        $this->path_explication = $this->file_explication->getClientOriginalName();
        $this->file_explication = null;
    }
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/usinedoc/';
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