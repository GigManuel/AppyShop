<?php

namespace Appydo\ContactBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Appydo\TestBundle\Entity\Contact
 */
class Contact
{

    /**
     * @var string $name
     * 
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;
    
    /**
     * @var string $phone
     * 
     * @Assert\MaxLength(15)
     */
    public $phone;

    public $subject;
    
    /**
     * @var string $message
     * 
     * @Assert\NotBlank()
     */
    public $message;
    
    /**
     * @var boolean $copy
     */
    public $copy;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    /**
     * @Assert\True(message = "Invalid Captcha.")
     */
    public function isValidCaptcha() {
        return (\Appydo\CaptchaBundle\Controller\DefaultController::validCaptcha());
    }

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../../www/uploads';
    }
    
    public function upload()
    {
        if (null === $this->file) {
            return;
        }
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        $this->file = null;
    }
}