<?php

declare(strict_types=1);

namespace App\Entity;

class LinkyData
{
    public ?string $linkyIdentifier = null;
    public ?string $pricingOption = null;
    public ?int $subscribedIntensity = null;
    public ?int $consumptionOffPeakHour = null;
    public ?int $consumptionPeakHour = null;
    public bool $offPeakHour = false;
    public ?int $instantaneousIntensity = null;
    public ?int $maxIntensity = null;
    public ?int $apparentPower = null;
    public ?string $timeGroup = null;
    public ?string $stateWord = null;

    public function getLinkyIdentifier(): ?string
    {
        return $this->linkyIdentifier;
    }

    public function setLinkyIdentifier(string $linkyIdentifier): void
    {
        $this->linkyIdentifier = $linkyIdentifier;
    }

    public function getPricingOption(): ?string
    {
        return $this->pricingOption;
    }

    public function setPricingOption(string $pricingOption): void
    {
        $this->pricingOption = $pricingOption;
    }

    public function getSubscribedIntensity(): ?int
    {
        return $this->subscribedIntensity;
    }

    public function setSubscribedIntensity(int $subscribedIntensity): void
    {
        $this->subscribedIntensity = $subscribedIntensity;
    }

    public function getConsumptionOffPeakHour(): ?int
    {
        return $this->consumptionOffPeakHour;
    }

    public function setConsumptionOffPeakHour(int $consumptionOffPeakHour): void
    {
        $this->consumptionOffPeakHour = $consumptionOffPeakHour;
    }

    public function getConsumptionPeakHour(): ?int
    {
        return $this->consumptionPeakHour;
    }

    public function setConsumptionPeakHour(int $consumptionPeakHour): void
    {
        $this->consumptionPeakHour = $consumptionPeakHour;
    }

    public function isOffPeakHour(): bool
    {
        return $this->offPeakHour;
    }

    public function setOffPeakHour(bool $offPeakHour): void
    {
        $this->offPeakHour = $offPeakHour;
    }

    public function getInstantaneousIntensity(): ?int
    {
        return $this->instantaneousIntensity;
    }

    public function setInstantaneousIntensity(int $instantaneousIntensity): void
    {
        $this->instantaneousIntensity = $instantaneousIntensity;
    }

    public function getMaxIntensity(): ?int
    {
        return $this->maxIntensity;
    }

    public function setMaxIntensity(int $maxIntensity): void
    {
        $this->maxIntensity = $maxIntensity;
    }

    public function getApparentPower(): ?int
    {
        return $this->apparentPower;
    }

    public function setApparentPower(int $apparentPower): void
    {
        $this->apparentPower = $apparentPower;
    }

    public function getTimeGroup(): ?string
    {
        return $this->timeGroup;
    }

    public function setTimeGroup(string $timeGroup): void
    {
        $this->timeGroup = $timeGroup;
    }

    public function getStateWord(): ?string
    {
        return $this->stateWord;
    }

    public function setStateWord(string $stateWord): void
    {
        $this->stateWord = $stateWord;
    }
}
