<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 */
class Users implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="boolean")
     */
    private $confirmed;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Posts", mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="author", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="integer")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Objects", mappedBy="giver", orphanRemoval=true)
     */
    private $givedObjects;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Objects", mappedBy="pretenders")
     */
    private $pretendingObjects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Objects", mappedBy="taker")
     */
    private $takenObjects;

    public function __construct()
    {
        $this->bottlesSents = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->bottles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->givedObjects = new ArrayCollection();
        $this->pretendingObjects = new ArrayCollection();
        $this->takenObjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

 

    /**
     * @return Collection|Posts[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Posts $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Posts $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }
    
    public function getConfirmed(): ?bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }



    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }
    
    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, array('allowed_classes' => false));
    }

    public function getAuthor(): ?int
    {
        return $this->author;
    }

    public function setAuthor(int $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Objects[]
     */
    public function getGivedObjects(): Collection
    {
        return $this->givedObjects;
    }

    public function addGivedObject(Objects $givedObject): self
    {
        if (!$this->givedObjects->contains($givedObject)) {
            $this->givedObjects[] = $givedObject;
            $givedObject->setGiver($this);
        }

        return $this;
    }

    public function removeGivedObject(Objects $givedObject): self
    {
        if ($this->givedObjects->contains($givedObject)) {
            $this->givedObjects->removeElement($givedObject);
            // set the owning side to null (unless already changed)
            if ($givedObject->getGiver() === $this) {
                $givedObject->setGiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Objects[]
     */
    public function getPretendingObjects(): Collection
    {
        return $this->pretendingObjects;
    }

    public function addPretendingObject(Objects $pretendingObject): self
    {
        if (!$this->pretendingObjects->contains($pretendingObject)) {
            $this->pretendingObjects[] = $pretendingObject;
            $pretendingObject->addPretender($this);
        }

        return $this;
    }

    public function removePretendingObject(Objects $pretendingObject): self
    {
        if ($this->pretendingObjects->contains($pretendingObject)) {
            $this->pretendingObjects->removeElement($pretendingObject);
            $pretendingObject->removePretender($this);
        }

        return $this;
    }

    /**
     * @return Collection|Objects[]
     */
    public function getTakenObjects(): Collection
    {
        return $this->takenObjects;
    }

    public function addTakenObject(Objects $takenObject): self
    {
        if (!$this->takenObjects->contains($takenObject)) {
            $this->takenObjects[] = $takenObject;
            $takenObject->setTaker($this);
        }

        return $this;
    }

    public function removeTakenObject(Objects $takenObject): self
    {
        if ($this->takenObjects->contains($takenObject)) {
            $this->takenObjects->removeElement($takenObject);
            // set the owning side to null (unless already changed)
            if ($takenObject->getTaker() === $this) {
                $takenObject->setTaker(null);
            }
        }

        return $this;
    }
}
