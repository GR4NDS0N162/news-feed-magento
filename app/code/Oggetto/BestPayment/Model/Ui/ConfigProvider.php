<?php

declare(strict_types=1);

namespace Oggetto\BestPayment\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

abstract class ConfigProvider implements ConfigProviderInterface
{
    public const CODE = 'best';
}
