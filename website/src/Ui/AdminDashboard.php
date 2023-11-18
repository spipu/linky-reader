<?php

declare(strict_types=1);

namespace App\Ui;

use Spipu\DashboardBundle\Entity\Dashboard as Dashboard;
use Spipu\DashboardBundle\Service\Ui\Definition\DashboardDefinitionInterface;

class AdminDashboard implements DashboardDefinitionInterface
{
    private ?Dashboard\Dashboard $definition = null;

    public function getDefinition(): Dashboard\Dashboard
    {
        if ($this->definition === null) {
            $this->definition = $this->prepareDefinition();
        }

        return $this->definition;
    }

    private function prepareDefinition(): Dashboard\Dashboard
    {
        return (new Dashboard\Dashboard('admin'))
            ->setTemplateShowMain('dashboard/dashboard-show.html.twig')
            ->setTemplateConfigureMain('dashboard/dashboard-configure.html.twig')
        ;
    }

    public function getDefaultConfig(): array
    {
        return [
            "rows" => [
                [
                    "title" => "",
                    "nbCol" => 3,
                    "cols" => [
                        [
                            "widgets" => [
                                [
                                    "source" => "log-linky-reader",
                                    "type" => "specific",
                                    "period" => null,
                                    "width" => 3,
                                    "height" => 2,
                                ]
                            ]
                        ],
                        [
                            "widgets" => []
                        ],
                        [
                            "widgets" => []
                        ]
                    ]
                ]
            ]
        ];
    }
}
