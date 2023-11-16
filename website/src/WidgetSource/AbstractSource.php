<?php

declare(strict_types=1);

namespace App\WidgetSource;

use Spipu\DashboardBundle\Source\SourceDefinitionInterface;

abstract class AbstractSource implements SourceDefinitionInterface
{
    /**
     * @return string[]
     */
    public function getRolesNeeded(): array
    {
        return [];
    }
}
