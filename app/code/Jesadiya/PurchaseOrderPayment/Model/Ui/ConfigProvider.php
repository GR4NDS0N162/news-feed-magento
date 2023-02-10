<?php

declare(strict_types=1);

namespace Jesadiya\PurchaseOrderPayment\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

abstract class ConfigProvider implements ConfigProviderInterface
{
    public const CODE = 'custompurchaseorder';
}
