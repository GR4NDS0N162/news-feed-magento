<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Block\Order\Item\Renderer;

use Magento\GroupedProduct\Block\Order\Item\Renderer\Grouped as GroupedRenderer;
use Oggetto\BestProduct\Api\BestOrderItemRenderer;

class Grouped extends GroupedRenderer implements BestOrderItemRenderer
{
    public function getIsBest(): string
    {
        return 'Maybe'; // TODO: Implement getIsBest() method.
    }
}
