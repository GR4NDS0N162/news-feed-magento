<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Block\Order\Item\Renderer;

use Magento\Downloadable\Block\Sales\Order\Item\Renderer\Downloadable as DownloadableRenderer;
use Oggetto\BestProduct\Api\BestOrderItemRenderer;

class Downloadable extends DownloadableRenderer implements BestOrderItemRenderer
{
    public function getIsBest(): string
    {
        return 'Maybe'; // TODO: Implement getIsBest() method.
    }
}
