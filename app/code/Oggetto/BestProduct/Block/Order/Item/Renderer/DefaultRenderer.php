<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Block\Order\Item\Renderer;

use Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer as DefaultItemRenderer;
use Oggetto\BestProduct\Api\BestOrderItemRenderer;

class DefaultRenderer extends DefaultItemRenderer implements BestOrderItemRenderer
{
    public function getIsBest(): string
    {
        return 'Maybe'; // TODO: Implement getIsBest() method.
    }
}
