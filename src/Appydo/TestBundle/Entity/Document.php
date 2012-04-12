<?php

namespace Appydo\TestBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Appydo\TestBundle\Entity\Document
 */
class Document
{
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
}