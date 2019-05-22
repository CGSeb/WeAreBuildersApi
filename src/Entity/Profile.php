<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     */
    private $email;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $birthDate;

    /**
     * @ORM\ManyToMany(targetEntity="Profile", mappedBy="following")
     **/
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="Profile", inversedBy="followers")
     * @ORM\JoinTable(name="followers",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="following_user_id", referencedColumnName="id")}
     * )
     */
    private $following;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getFollowers(): array
    {
        return $this->followers;
    }

    public function setFollowers(array $followers): self
    {
        $this->followers = $followers;

        return $this;
    }

    public function getFollowing(): array
    {
        return $this->following;
    }

    public function setFollowing(array $following): void
    {
        $this->following = $following;
    }

    public function follow(Profile $profile): void
    {
        $this->following[] = $profile;
        $profile->followedBy($this);
    }

    /**
     * @param Profile $profile
     */
    private function followedBy(Profile $profile): void
    {
        $this->followers[] = $profile;
    }

    /**
     * @param Profile $profile
     */
    public function unfollow(Profile $profile): void
    {
        $this->following->removeElement($profile);
        $profile->unfollowedBy($this);
    }

    /**
     * @param Profile $profile
     */
    private function unfollowedBy(Profile $profile):void
    {
        $this->followers->removeElement($profile);
    }

    public function getFullName(): string
    {
        return $this->$this->firstName . ' ' . $this->lastName;
    }
}
