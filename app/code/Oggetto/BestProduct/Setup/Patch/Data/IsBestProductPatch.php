<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Oggetto\BestProduct\Model\Config\Source\YesNoMaybe;
use Psr\Log\LoggerInterface;
use Zend_Validate_Exception as ValidateException;

class IsBestProductPatch implements DataPatchInterface
{
    public const ENTITY_TYPE = Product::ENTITY;
    public const ATTRIBUTE_CODE = 'is_best';
    public const ATTRIBUTE_TYPE = Table::TYPE_INTEGER;
    public const DEFAULT_VALUE = YesNoMaybe::VALUE_NO;

    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory          $eavSetupFactory
     * @param LoggerInterface          $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        LoggerInterface $logger,
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        try {
            $eavSetup->addAttribute(
                self::ENTITY_TYPE,
                self::ATTRIBUTE_CODE,
                [
                    'type'                    => self::ATTRIBUTE_TYPE,
                    'label'                   => 'Is Best',
                    'input'                   => 'select',
                    'source'                  => YesNoMaybe::class,
                    'global'                  => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => true,
                    'default'                 => self::DEFAULT_VALUE,
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => true,
                    'used_in_product_listing' => true,
                    'unique'                  => false,
                    'system'                  => 1,
                    'group'                   => 'General',
                ]
            );
        } catch (LocalizedException | ValidateException $e) {
            $this->logger->error($e->getMessage());
        }

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
