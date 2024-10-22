<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Sales\Setup\SalesSetupFactory;

class IsBestOrderItemPatch implements DataPatchInterface
{
    public const ENTITY_TYPE = 'order_item';
    public const ATTRIBUTE_CODE = IsBestProductPatch::ATTRIBUTE_CODE;
    public const ATTRIBUTE_TYPE = IsBestProductPatch::ATTRIBUTE_TYPE;
    public const DEFAULT_VALUE = IsBestProductPatch::DEFAULT_VALUE;

    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var SalesSetupFactory
     */
    private SalesSetupFactory $salesSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param SalesSetupFactory        $salesSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        SalesSetupFactory $salesSetupFactory,
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $salesSetup = $this->salesSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $salesSetup->addAttribute(
            self::ENTITY_TYPE,
            self::ATTRIBUTE_CODE,
            [
                'type'     => self::ATTRIBUTE_TYPE,
                'default'  => self::DEFAULT_VALUE,
                'visible'  => true,
                'required' => false,
            ]
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
