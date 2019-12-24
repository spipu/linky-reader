<?php
declare(strict_types = 1);

namespace App\Entity;

class LinkyData
{
    /**
     * @var string|null
     */
    private $linkyIdentifier;

    /**
     * @var string|null
     */
    private $pricingOption;

    /**
     * @var int|null
     */
    private $subscribedIntensity;

    /**
     * @var int|null
     */
    private $consumptionOffPeakHour;

    /**
     * @var int|null
     */
    private $consumptionPeakHour;

    /**
     * @var bool
     */
    private $offPeakHour = false;

    /**
     * @var int|null
     */
    private $instantaneousIntensity;

    /**
     * @var int|null
     */
    private $maxIntensity;

    /**
     * @var int|null
     */
    private $apparentPower;

    /**
     * @var string|null
     */
    private $timeGroup;

    /**
     * @var string|null
     */
    private $stateWord;

    /**
     * @return string|null
     */
    public function getLinkyIdentifier(): ?string
    {
        return $this->linkyIdentifier;
    }

    /**
     * @param string $linkyIdentifier
     */
    public function setLinkyIdentifier(string $linkyIdentifier): void
    {
        $this->linkyIdentifier = $linkyIdentifier;
    }

    /**
     * @return string|null
     */
    public function getPricingOption(): ?string
    {
        return $this->pricingOption;
    }

    /**
     * @param string $pricingOption
     */
    public function setPricingOption(string $pricingOption): void
    {
        $this->pricingOption = $pricingOption;
    }

    /**
     * @return int|null
     */
    public function getSubscribedIntensity(): ?int
    {
        return $this->subscribedIntensity;
    }

    /**
     * @param int $subscribedIntensity
     */
    public function setSubscribedIntensity(int $subscribedIntensity): void
    {
        $this->subscribedIntensity = $subscribedIntensity;
    }

    /**
     * @return int|null
     */
    public function getConsumptionOffPeakHour(): ?int
    {
        return $this->consumptionOffPeakHour;
    }

    /**
     * @param int $consumptionOffPeakHour
     */
    public function setConsumptionOffPeakHour(int $consumptionOffPeakHour): void
    {
        $this->consumptionOffPeakHour = $consumptionOffPeakHour;
    }

    /**
     * @return int|null
     */
    public function getConsumptionPeakHour(): ?int
    {
        return $this->consumptionPeakHour;
    }

    /**
     * @param int $consumptionPeakHour
     */
    public function setConsumptionPeakHour(int $consumptionPeakHour): void
    {
        $this->consumptionPeakHour = $consumptionPeakHour;
    }

    /**
     * @return bool
     */
    public function isOffPeakHour(): bool
    {
        return $this->offPeakHour;
    }

    /**
     * @param bool $offPeakHour
     */
    public function setOffPeakHour(bool $offPeakHour): void
    {
        $this->offPeakHour = $offPeakHour;
    }

    /**
     * @return int|null
     */
    public function getInstantaneousIntensity(): ?int
    {
        return $this->instantaneousIntensity;
    }

    /**
     * @param int $instantaneousIntensity
     */
    public function setInstantaneousIntensity(int $instantaneousIntensity): void
    {
        $this->instantaneousIntensity = $instantaneousIntensity;
    }

    /**
     * @return int|null
     */
    public function getMaxIntensity(): ?int
    {
        return $this->maxIntensity;
    }

    /**
     * @param int $maxIntensity
     */
    public function setMaxIntensity(int $maxIntensity): void
    {
        $this->maxIntensity = $maxIntensity;
    }

    /**
     * @return int|null
     */
    public function getApparentPower(): ?int
    {
        return $this->apparentPower;
    }

    /**
     * @param int $apparentPower
     */
    public function setApparentPower(int $apparentPower): void
    {
        $this->apparentPower = $apparentPower;
    }

    /**
     * @return string|null
     */
    public function getTimeGroup(): ?string
    {
        return $this->timeGroup;
    }

    /**
     * @param string $timeGroup
     */
    public function setTimeGroup(string $timeGroup): void
    {
        $this->timeGroup = $timeGroup;
    }

    /**
     * @return string|null
     */
    public function getStateWord(): ?string
    {
        return $this->stateWord;
    }

    /**
     * @param string $stateWord
     */
    public function setStateWord(string $stateWord): void
    {
        $this->stateWord = $stateWord;
    }
}