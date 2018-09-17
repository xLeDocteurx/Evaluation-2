<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjectsRepository")
 */
class Objects
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="givedObjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $giver;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Users", inversedBy="pretendingObjects")
     */
    private $pretenders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="takenObjects")
     * @ORM\JoinColumn(nullable=true)
     */
    private $taker;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $categorie;

    /**
     * @ORM\Column(type="boolean")
     */
    private $received;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageURL;

    public function __construct()
    {
        $this->pretenders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGiver(): ?Users
    {
        return $this->giver;
    }

    public function setGiver(?Users $giver): self
    {
        $this->giver = $giver;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getPretenders(): Collection
    {
        return $this->pretenders;
    }

    public function addPretender(Users $pretender): self
    {
        if (!$this->pretenders->contains($pretender)) {
            $this->pretenders[] = $pretender;
        }

        return $this;
    }

    public function removePretender(Users $pretender): self
    {
        if ($this->pretenders->contains($pretender)) {
            $this->pretenders->removeElement($pretender);
        }

        return $this;
    }

    public function getTaker(): ?Users
    {
        return $this->taker;
    }

    public function setTaker(?Users $taker): self
    {
        $this->taker = $taker;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getReceived(): ?bool
    {
        return $this->received;
    }

    public function setReceived(bool $received): self
    {
        $this->received = $received;

        return $this;
    }

    public function getImageURL(): ?string
    {
        return $this->imageURL;
    }

    public function setImageURL(?string $imageURL): self
    {
        $this->imageURL = $imageURL;

        return $this;
    }
}
