<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Api;

interface BestOrderItemRenderer
{
    public function getIsBest(): string;
}
