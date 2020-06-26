<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient
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
    private $socialNumber;

    /**
     * @ORM\ManyToMany(targetEntity=Disease::class, inversedBy="patients")
     */
    private $disease;

    /**
     * @ORM\ManyToMany(targetEntity=Drugs::class, inversedBy="patients")
     */
    private $drugs;

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
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="patient")
     */
    private $reports;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="patient", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Messaging::class, mappedBy="patient")
     */
    private $messagings;

    /**
     * @ORM\ManyToMany(targetEntity=Doctor::class, inversedBy="patients")
     */
    private $doctor;

    /*
     * @ORM\OneToMany(targetEntity=Notifications::class, mappedBy="patient")
     */
    private $notifications;

    public function __construct()
    {
        $this->disease = new ArrayCollection();
        $this->drugs = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->messagings = new ArrayCollection();
        $this->doctor = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSocialNumber(): ?string
    {
        return $this->socialNumber;
    }

    public function setSocialNumber(string $socialNumber): self
    {
        $this->socialNumber = $socialNumber;

        return $this;
    }

    /**
     * @return Collection|Disease[]
     */
    public function getDisease(): Collection
    {
        return $this->disease;
    }

    public function addDisease(Disease $disease): self
    {
        if (!$this->disease->contains($disease)) {
            $this->disease[] = $disease;
        }

        return $this;
    }

    public function removeDisease(Disease $disease): self
    {
        if ($this->disease->contains($disease)) {
            $this->disease->removeElement($disease);
        }

        return $this;
    }

    /**
     * @return Collection|Drugs[]
     */
    public function getDrugs(): Collection
    {
        return $this->drugs;
    }

    public function addDrug(Drugs $drug): self
    {
        if (!$this->drugs->contains($drug)) {
            $this->drugs[] = $drug;
        }

        return $this;
    }

    public function removeDrug(Drugs $drug): self
    {
        if ($this->drugs->contains($drug)) {
            $this->drugs->removeElement($drug);
        }

        return $this;
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

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setPatient($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->contains($report)) {
            $this->reports->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getPatient() === $this) {
                $report->setPatient(null);
            }
        }

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
    public function getMessagings(): Collection
    {
        return $this->messagings;
    }

    public function addMessaging(Messaging $messaging): self
    {
        if (!$this->messagings->contains($messaging)) {
            $this->messagings[] = $messaging;
            $messaging->setPatient($this);
        }

        return $this;
    }

    public function removeMessaging(Messaging $messaging): self
    {
        if ($this->messagings->contains($messaging)) {
            $this->messagings->removeElement($messaging);
            // set the owning side to null (unless already changed)
            if ($messaging->getPatient() === $this) {
                $messaging->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Doctor[]
     */
    public function getDoctor(): Collection
    {
        return $this->doctor;
    }

    public function addDoctor(Doctor $doctor): self
    {
        if (!$this->doctor->contains($doctor)) {
            $this->doctor[] = $doctor;
        }
        return $this;
    }
  
    /*
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
            $notification->setPatient($this);
        }

        return $this;
    }


    public function removeDoctor(Doctor $doctor): self
    {
        if ($this->doctor->contains($doctor)) {
            $this->doctor->removeElement($doctor);
            // set the owning side to null (unless already changed)
              if ($doctor->getPatient() === $this) {
                  $doctor->setPatient(null);
              }
        }
    }
          
    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getPatient() === $this) {
                $notification->setPatient(null);
            }
        }
        return $this;
    }
}
