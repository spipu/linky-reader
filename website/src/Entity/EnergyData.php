<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EnergyDataRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Spipu\UiBundle\Entity\EntityInterface;

/**
 * @SuppressWarnings(PMD.TooManyFields)
 * @SuppressWarnings(PMD.ExcessivePublicCount)
 * @SuppressWarnings(PMD.ExcessiveClassComplexity)
 */
#[ORM\Entity(repositoryClass: EnergyDataRepository::class)]
#[ORM\Table(name: "energy_data")]
#[ORM\UniqueConstraint(name: "ENERGY_DATA_UNIQ_TIME", columns: ["time"])]
#[ORM\HasLifecycleCallbacks]
class EnergyData implements EntityInterface
{
    public const PUSH_STATUS_WAITING = 'waiting';
    public const PUSH_STATUS_ERROR = 'error';
    public const PUSH_STATUS_PUSHED = 'pushed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\Column(length: 32)]
    private ?string $pushStatus = null;

    #[ORM\Column]
    private ?int $pushNbTry = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pushLastError = null;

    #[ORM\Column(length: 32)]
    private ?string $pricingOption = null;

    #[ORM\Column]
    private ?int $subscribedIntensity = null;

    #[ORM\Column(length: 32)]
    private ?string $timeGroup = null;

    #[ORM\Column(length: 32)]
    private ?string $stateWord = null;

    #[ORM\Column]
    private ?int $consumptionOffPeakHour = null;

    #[ORM\Column]
    private ?int $consumptionPeakHour = null;

    #[ORM\Column]
    private ?int $consumptionTotal = null;

    #[ORM\Column]
    private ?int $consumptionDelta = null;

    #[ORM\Column]
    private ?bool $offPeakHour = null;

    #[ORM\Column]
    private ?int $instantaneousIntensity = null;

    #[ORM\Column(nullable: true)]
    private ?int $instantaneousIntensity1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $instantaneousIntensity2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $instantaneousIntensity3 = null;

    #[ORM\Column]
    private ?int $maxIntensity = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxIntensity1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxIntensity2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxIntensity3 = null;

    #[ORM\Column]
    private ?int $apparentPower = null;

    #[ORM\Column(type: "datetime", nullable: false)]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\Column(type: "datetime", nullable: false)]
    private ?DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getPushStatus(): ?string
    {
        return $this->pushStatus;
    }

    public function setPushStatus(string $pushStatus): static
    {
        $this->pushStatus = $pushStatus;

        return $this;
    }

    public function getPushNbTry(): ?int
    {
        return $this->pushNbTry;
    }

    public function setPushNbTry(int $pushNbTry): static
    {
        $this->pushNbTry = $pushNbTry;

        return $this;
    }

    public function getPushLastError(): ?string
    {
        return $this->pushLastError;
    }

    public function setPushLastError(?string $pushLastError): static
    {
        $this->pushLastError = $this->protectValue($pushLastError);

        return $this;
    }

    public function getPricingOption(): ?string
    {
        return $this->pricingOption;
    }

    public function setPricingOption(string $pricingOption): static
    {
        $this->pricingOption = $pricingOption;

        return $this;
    }

    public function getSubscribedIntensity(): ?int
    {
        return $this->subscribedIntensity;
    }

    public function setSubscribedIntensity(int $subscribedIntensity): static
    {
        $this->subscribedIntensity = $subscribedIntensity;

        return $this;
    }

    public function getTimeGroup(): ?string
    {
        return $this->timeGroup;
    }

    public function setTimeGroup(string $timeGroup): static
    {
        $this->timeGroup = $timeGroup;

        return $this;
    }

    public function getStateWord(): ?string
    {
        return $this->stateWord;
    }

    public function setStateWord(string $stateWord): static
    {
        $this->stateWord = $stateWord;

        return $this;
    }

    public function getConsumptionOffPeakHour(): ?int
    {
        return $this->consumptionOffPeakHour;
    }

    public function setConsumptionOffPeakHour(int $consumptionOffPeakHour): static
    {
        $this->consumptionOffPeakHour = $consumptionOffPeakHour;

        return $this;
    }

    public function getConsumptionPeakHour(): ?int
    {
        return $this->consumptionPeakHour;
    }

    public function setConsumptionPeakHour(int $consumptionPeakHour): static
    {
        $this->consumptionPeakHour = $consumptionPeakHour;

        return $this;
    }

    public function getConsumptionTotal(): ?int
    {
        return $this->consumptionTotal;
    }

    public function setConsumptionTotal(int $consumptionTotal): static
    {
        $this->consumptionTotal = $consumptionTotal;

        return $this;
    }
    public function getConsumptionDelta(): ?int
    {
        return $this->consumptionDelta;
    }

    public function setConsumptionDelta(int $consumptionDelta): static
    {
        $this->consumptionDelta = $consumptionDelta;

        return $this;
    }

    public function isOffPeakHour(): ?bool
    {
        return $this->offPeakHour;
    }

    public function setOffPeakHour(bool $offPeakHour): static
    {
        $this->offPeakHour = $offPeakHour;

        return $this;
    }

    public function getInstantaneousIntensity(): ?int
    {
        return $this->instantaneousIntensity;
    }

    public function setInstantaneousIntensity(int $instantaneousIntensity): static
    {
        $this->instantaneousIntensity = $instantaneousIntensity;

        return $this;
    }

    public function getInstantaneousIntensity1(): ?int
    {
        return $this->instantaneousIntensity1;
    }

    public function setInstantaneousIntensity1(?int $instantaneousIntensity1): static
    {
        $this->instantaneousIntensity1 = $instantaneousIntensity1;

        return $this;
    }

    public function getInstantaneousIntensity2(): ?int
    {
        return $this->instantaneousIntensity2;
    }

    public function setInstantaneousIntensity2(?int $instantaneousIntensity2): static
    {
        $this->instantaneousIntensity2 = $instantaneousIntensity2;

        return $this;
    }

    public function getInstantaneousIntensity3(): ?int
    {
        return $this->instantaneousIntensity3;
    }

    public function setInstantaneousIntensity3(?int $instantaneousIntensity3): static
    {
        $this->instantaneousIntensity3 = $instantaneousIntensity3;

        return $this;
    }

    public function getMaxIntensity(): ?int
    {
        return $this->maxIntensity;
    }

    public function setMaxIntensity(int $maxIntensity): static
    {
        $this->maxIntensity = $maxIntensity;

        return $this;
    }

    public function getMaxIntensity1(): ?int
    {
        return $this->maxIntensity1;
    }

    public function setMaxIntensity1(?int $maxIntensity1): static
    {
        $this->maxIntensity1 = $maxIntensity1;

        return $this;
    }

    public function getMaxIntensity2(): ?int
    {
        return $this->maxIntensity2;
    }

    public function setMaxIntensity2(?int $maxIntensity2): static
    {
        $this->maxIntensity2 = $maxIntensity2;

        return $this;
    }

    public function getMaxIntensity3(): ?int
    {
        return $this->maxIntensity3;
    }

    public function setMaxIntensity3(?int $maxIntensity3): static
    {
        $this->maxIntensity3 = $maxIntensity3;

        return $this;
    }

    public function getApparentPower(): ?int
    {
        return $this->apparentPower;
    }

    public function setApparentPower(int $apparentPower): static
    {
        $this->apparentPower = $apparentPower;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist()]
    #[ORM\PreUpdate()]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getDataToPush(): array
    {
        return [
            'time'                      => $this->getTime(),
            'pricingOption'             => $this->getPricingOption(),
            'subscribedIntensity'       => $this->getSubscribedIntensity(),
            'consumptionOffPeakHour'    => $this->getConsumptionOffPeakHour(),
            'consumptionPeakHour'       => $this->getConsumptionPeakHour(),
            'offPeakHour'               => $this->isOffPeakHour(),
            'instantaneousIntensity'    => $this->getInstantaneousIntensity(),
            'maxIntensity'              => $this->getMaxIntensity(),
            'apparentPower'             => $this->getApparentPower(),
            'timeGroup'                 => $this->getTimeGroup(),
            'stateWord'                 => $this->getStateWord(),
        ];
    }

    public function getDataToDisplay(): array
    {
        return [
            'time'                      => $this->getTime(),
            'pricingOption'             => $this->getPricingOption(),
            'subscribedIntensity'       => $this->getSubscribedIntensity(),
            'consumptionOffPeakHour'    => $this->getConsumptionOffPeakHour(),
            'consumptionPeakHour'       => $this->getConsumptionPeakHour(),
            'consumptionTotal'          => $this->getConsumptionTotal(),
            'consumptionDelta'          => $this->getConsumptionDelta(),
            'offPeakHour'               => $this->isOffPeakHour(),
            'instantaneousIntensity'    => $this->getInstantaneousIntensity(),
            'instantaneousIntensity1'   => $this->getInstantaneousIntensity1(),
            'instantaneousIntensity2'   => $this->getInstantaneousIntensity2(),
            'instantaneousIntensity3'   => $this->getInstantaneousIntensity3(),
            'maxIntensity'              => $this->getMaxIntensity(),
            'maxIntensity1'             => $this->getMaxIntensity1(),
            'maxIntensity2'             => $this->getMaxIntensity2(),
            'maxIntensity3'             => $this->getMaxIntensity3(),
            'apparentPower'             => $this->getApparentPower(),
            'timeGroup'                 => $this->getTimeGroup(),
            'stateWord'                 => $this->getStateWord(),
        ];
    }

    private function protectValue(?string $pushLastError): ?string
    {
        if ($pushLastError === null) {
            return null;
        }
        return substr($pushLastError, 0, 250);
    }
}
