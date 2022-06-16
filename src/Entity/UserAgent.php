<?php

namespace App\Entity;

use App\Repository\UserAgentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserAgentRepository::class)
 */
class UserAgent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $os;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $architecture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $browser;

    /**
     * @ORM\OneToMany(targetEntity=Log::class, mappedBy="userAgent")
     */
    private $log;

    public function __construct()
    {
        $this->log = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOs(): ?string
    {
        return $this->os;
    }

    public function setOs(?string $os): self
    {
        $this->os = $os;

        return $this;
    }

    public function getArchitecture(): ?string
    {
        return $this->architecture;
    }

    public function setArchitecture(string $architecture): self
    {
        $this->architecture = $architecture;

        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function setBrowser(string $browser): self
    {
        $this->browser = $browser;

        return $this;
    }

    /**
     * @return Collection<int, Log>
     */
    public function getLog(): Collection
    {
        return $this->log;
    }

    public function addLog(Log $log): self
    {
        if (!$this->log->contains($log)) {
            $this->log[] = $log;
            $log->setUserAgent($this);
        }

        return $this;
    }

    public function removeLog(Log $log): self
    {
        if ($this->log->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getUserAgent() === $this) {
                $log->setUserAgent(null);
            }
        }

        return $this;
    }
}
