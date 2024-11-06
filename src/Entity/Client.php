<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $surname = null;

    #[ORM\Column(length: 50)]
    private ?string $telephone = null;

    #[ORM\Column(length: 50)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Dette>
     */
    #[ORM\OneToMany(targetEntity: Dette::class, mappedBy: 'client', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $dettes;

    public function __construct()
    {
        $this->dettes = new ArrayCollection();
        $this->createAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Dette>
     */
    public function getDettes(): Collection
    {
        return $this->dettes;
    }

    public function addDette(Dette $dette): static
    {
        if (!$this->dettes->contains($dette)) {
            $this->dettes->add($dette);
            $dette->setClient($this);
        }

        return $this;
    }
    // Client.php

    public function getTotalDette(): float
    {
        $total = 0.0;
        foreach ($this->dettes as $dette) {
            $total += ($dette->getMontant() - $dette->getMontantVerser());
        }
        return $total;
    }


    public function removeDette(Dette $dette): static
    {
        if ($this->dettes->removeElement($dette)) {
            // set the owning side to null (unless already changed)
            if ($dette->getClient() === $this) {
                $dette->setClient(null);
            }
        }

        return $this;
    }
}
