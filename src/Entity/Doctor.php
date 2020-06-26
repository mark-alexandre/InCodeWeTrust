<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numberLicense;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="doctor", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Messaging::class, mappedBy="doctor")
     */
    private $messagesFromPatients;

    /**
     * @ORM\ManyToMany(targetEntity=Patient::class, mappedBy="doctor", cascade={"persist", "remove"})
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity=Notifications::class, mappedBy="doctor")
     */
    private $notifications;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->messagesFromPatients = new ArrayCollection();
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

    public function getNumberLicense(): ?string
    {
        return $this->numberLicense;
    }

    public function setNumberLicense(?string $numberLicense): self
    {
        $this->numberLicense = $numberLicense;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $patient->addDoctor($this);
        }
        return $this;
    }

    /**
     * @return Collection|Notifications[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setDoctor($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->contains($patient)) {
            $this->patients->removeElement($patient);

            $patient->removeDoctor($this);
            if ($patient->getDoctor() === $this) {
                  $patient->setDoctor(null);
              }
        }
        return $this;
    }

    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getDoctor() === $this) {
                $notification->setDoctor(null);
            }
        }

        return $this;
    }
}
