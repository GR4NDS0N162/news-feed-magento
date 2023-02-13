<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Oggetto\BestProduct\Model\Config\Source\YesNoMaybe;
use Psr\Log\LoggerInterface;
use Zend_Validate_Exception as ValidateException;

class IsBestProductPatch implements DataPatchInterface
{
    public const ATTRIBUTE_CODE = 'is_best';
    public const DEFAULT_VALUE = YesNoMaybe::VALUE_NO;

    /**
     * @var EavSetup
     */
    private EavSetup $eavSetup;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param EavSetupFactory          $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param LoggerInterface          $logger
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup,
        LoggerInterface $logger,
    ) {
        $this->eavSetup = $eavSetupFactory->create(['setup' => $moduleDataSetup]);
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
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
    public function apply()
    {
        try {
            $this->eavSetup->addAttribute(
                Product::ENTITY,
                self::ATTRIBUTE_CODE,
                [
                    'type'                    => 'int',
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
    }
}
