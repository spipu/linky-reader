<?php
declare(strict_types = 1);

namespace App\Service;

use Spipu\UiBundle\Entity\Menu\Item;
use Spipu\UiBundle\Service\Menu\DefinitionInterface;

class MenuDefiniton implements DefinitionInterface
{
    /**
     * @var Item
     */
    private $mainItem;

    /**
     * @return void
     */
    private function build(): void
    {
        $this->mainItem = new Item('Linky Reader', '', 'app_home');

        $this->mainItem
            ->addChild('spipu.ui.page.home', 'home', 'app_home')->getParentItem()
        ;
    }

    /**
     * @return Item
     */
    public function getDefinition(): Item
    {
        if (!$this->mainItem) {
            $this->build();
        }

        return $this->mainItem;
    }
}