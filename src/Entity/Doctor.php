<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctorRepository::class)
 */
class Doctor
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="doctor")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Messaging::class, mappedBy="doctor")
     */
    private $messagesFromPatients;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->messagesFromPatients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setDoctor($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getDoctor() === $this) {
                $user->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messaging[]
     */
    public function getMessagesFromPatients(): Collection
    {
        return $this->messagesFromPatients;
    }

    public function addMessagesFromPatient(Messaging $messagesFromPatient): self
    {
        if (!$this->messagesFromPatients->contains($messagesFromPatient)) {
            $this->messagesFromPatients[] = $messagesFromPatient;
            $messagesFromPatient->setDoctor($this);
        }

        return $this;
    }

    public function removeMessagesFromPatient(Messaging $messagesFromPatient): self
    {
        if ($this->messagesFromPatients->contains($messagesFromPatient)) {
            $this->messagesFromPatients->removeElement($messagesFromPatient);
            // set the owning side to null (unless already changed)
            if ($messagesFromPatient->getDoctor() === $this) {
                $messagesFromPatient->setDoctor(null);
            }
        }

        return $this;
    }
}
