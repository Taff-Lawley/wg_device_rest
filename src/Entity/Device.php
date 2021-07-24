<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeviceRepository::class)
 */
class Device
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", name="device_id")
     */
    private int $deviceId;

    /**
     * @ORM\Column(type="string", length=255, name="device_type")
     */
    private string $deviceType;

    /**
     * @ORM\Column(type="boolean", name="damage_possible")
     */
    private bool $damagePossible;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceType(): ?string
    {
        return $this->deviceType;
    }

    public function setDeviceType(string $deviceType): self
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    public function getDamagePossible(): ?bool
    {
        return $this->damagePossible;
    }

    public function setDamagePossible(bool $damagePossible): self
    {
        $this->damagePossible = $damagePossible;

        return $this;
    }

    public function getDeviceId(): ?int
    {
        return $this->deviceId;
    }

    public function setDeviceId(int $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }
}
