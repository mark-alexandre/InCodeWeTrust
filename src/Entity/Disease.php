<?php

namespace App\Entity;

use App\Repository\DeseaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaladieRepository::class)
 */
class Disease
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
     * @ORM\Column(type="boolean")
     */
    private $duration;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="disease")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Advice::class)
     */
    private $advice;

    /**
     * @ORM\ManyToMany(targetEntity=Patient::class, mappedBy="disease")
     */
    private $patients;

    public function __construct()
    {
        $this->advice = new ArrayCollection();
        $this->patients = new ArrayCollection();
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

    public function getDuration(): ?bool
    {
        return $this->duration;
    }

    public function setDuration(bool $duration): self
    {
        $this->duration = $duration;

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
            $user->addDisease($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeDisease($this);
        }

        return $this;
    }
    /**
     * @return Collection|Advice[]
     */
    public function getAdvice(): Collection
    {
        return $this->advice;
    }

    public function addAdvice(Advice $advice): self
    {
        if (!$this->advice->contains($advice)) {
            $this->advice[] = $advice;
        }

        return $this;
    }

    public function removeAdvice(Advice $advice): self
    {
        if ($this->advice->contains($advice)) {
            $this->advice->removeElement($advice);
        }

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->addDisease($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->contains($patient)) {
            $this->patients->removeElement($patient);
            $patient->removeDisease($this);
        }

        return $this;
    }
}
