<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Api;

use Magento\Framework\DataObject;

interface BestBundleOrderItem
{
    public function getIsBest(DataObject $item): string;
}
