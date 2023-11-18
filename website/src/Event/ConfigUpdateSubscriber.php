<?php

declare(strict_types=1);

namespace App\Event;

use Spipu\ConfigurationBundle\Event\ConfigurationEvent;
use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConfigUpdateSubscriber implements EventSubscriberInterface
{
    private ConfigurationManager $configurationManager;

    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConfigurationEvent::PREFIX_NAME . 'linky.history.keep' => 'onUpdate',
        ];
    }

    public function onUpdate(ConfigurationEvent $event): void
    {
        $value = (int) $this->configurationManager->get('linky.history.keep');
        if ($value < 2) {
            $this->configurationManager->set('linky.history.keep', 2);
        }
    }
}
