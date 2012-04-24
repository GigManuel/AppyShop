<?php
namespace Appydo\TestBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Appydo\TestBundle\Entity\User
 *
 * @ORM\Table(name="appydo_users")
 * @ORM\Entity(repositoryClass="Appydo\TestBundle\Entity\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $username
     * 
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     * @Assert\NotBlank(groups={"Default","registration"})
     * @Assert\MinLength(limit=3, groups={"Default","registration"})
     */
    private $username;

    /**
     * @ORM\Column(name="salt", type="string", length=40)
     */
    private $salt;
    
    public $oldPassword;

    /**
     * @ORM\Column(name="password", type="string", length=40)
     * @Assert\NotBlank(message = "Empty password", groups={"Default","registration"})
     * @Assert\MinLength(limit=3, groups={"Default","registration"})
     */
    private $password;
    
    /**
     * @ORM\Column(name="newPassword", type="string", length=40, nullable=true)
     * @Assert\MinLength(limit=3, groups={"edit"})
     */
    public $newPassword;
    
    /**
     * @Assert\NotBlank(groups={"Default","registration"})
     * @Assert\MinLength(limit=3, groups={"Default","registration"})
     */
    private $confirmPassword;
    
    /**
     * @ORM\OneToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     *
     * @var Project $current
     */
    private $current;

    /**
     * @var string $email
     * 
     * @ORM\Column(name="email", type="string", length=60, unique=true)
     * @Assert\MinLength(limit=3, groups={"Default","registration","edit"})
     * @Assert\NotBlank(groups={"Default","registration","edit"})
     * @Assert\Email(groups={"Default","registration","edit"})
     */
    private $email;

    /**
     * @Assert\True(message = "The password not match your confirm password.", groups={"registration"})
     */
    public function isPasswordLegal()
    {
        return ($this->password == $this->confirmPassword);
    }
    
    /**
     * @Assert\True(message = "The password not match your confirm password.", groups={"edit"})
     */
    public function isNewPasswordLegal()
    {
        return ($this->newPassword == $this->confirmPassword);
    }
    
    /**
     * @Assert\True(message = "The new password is empty.", groups={"edit"})
     */
    public function isNewPasswordEmpty()
    {
        if (!empty($this->newPassword)) return true;
        else return (empty($this->oldPassword) and empty($this->newPassword));
    }
    
    /**
     * @Assert\True(message = "The old password is empty.", groups={"edit"})
     */
    public function isOldPasswordEmpty()
    {
        if (empty($this->newPassword)) return true;
        else return (!empty($this->oldPassword) and !empty($this->newPassword));
    }
    
    /**
     * @Assert\True(message = "Wrong old password.", groups={"edit"})
     */
    public function isOldPasswordLegal()
    {
        return (empty($this->oldPassword) or ($this->oldPassword == $this->password));
    }
    
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->isActive = true;
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function equals(UserInterface $user)
    {
        return $user->getUsername() === $this->username;
    }

    public function eraseCredentials()
    {
    }
    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getSalt()
    {
        return $this->salt;
    }
    public function getCurrent()
    {
        return $this->current;
    }
    public function getCurrentId()
    {
        return $this->current->getId();
    }
    public function getIsActive()
    {
        return $this->isActive;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
    public function setCurrent($current)
    {
        $this->current = $current;
    }
    public function __toString() {
        return $this->username;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }
    public function isEnabled() {
        return true;
    }
    public function serialize() {
        return serialize($this->id);
    }
    public function unserialize($data) {
        $this->id = unserialize($data); 
    }
    public function getData() {
        return $this->data;
    }
}
