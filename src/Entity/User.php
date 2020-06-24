<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Messaging::class, mappedBy="author")
     */
    private $messagings;

    /**
     * @ORM\OneToMany(targetEntity=Messaging::class, mappedBy="destinator")
     */
    private $destinatorMessages;

    public function __construct()
    {
        $this->messagings = new ArrayCollection();
        $this->destinatorMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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
            $messaging->setAuthor($this);
        }

        return $this;
    }

    public function removeMessaging(Messaging $messaging): self
    {
        if ($this->messagings->contains($messaging)) {
            $this->messagings->removeElement($messaging);
            // set the owning side to null (unless already changed)
            if ($messaging->getAuthor() === $this) {
                $messaging->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messaging[]
     */
    public function getDestinatorMessages(): Collection
    {
        return $this->destinatorMessages;
    }

    public function addDestinatorMessage(Messaging $destinatorMessage): self
    {
        if (!$this->destinatorMessages->contains($destinatorMessage)) {
            $this->destinatorMessages[] = $destinatorMessage;
            $destinatorMessage->setDestinator($this);
        }

        return $this;
    }

    public function removeDestinatorMessage(Messaging $destinatorMessage): self
    {
        if ($this->destinatorMessages->contains($destinatorMessage)) {
            $this->destinatorMessages->removeElement($destinatorMessage);
            // set the owning side to null (unless already changed)
            if ($destinatorMessage->getDestinator() === $this) {
                $destinatorMessage->setDestinator(null);
            }
        }

        return $this;
    }
}
