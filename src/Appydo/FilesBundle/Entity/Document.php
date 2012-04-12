<?php

namespace Appydo\FilesBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Appydo\FilesBundle\Entity\Document
 */
class Document
{
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    public function getUploadRootDir()
    {
        return __DIR__.'/../../../../www/uploads/appyfiles/';
    }
    
    public function upload()
    {
        if (null === $this->file) {
            return;
        }
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        // $this->file = null;
    }
}