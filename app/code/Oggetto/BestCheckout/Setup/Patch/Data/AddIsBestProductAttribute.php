<?php

declare(strict_types=1);

namespace Oggetto\BestCheckout\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Oggetto\BestCheckout\Model\Product\Source\IsBest;
use Psr\Log\LoggerInterface;
use Zend_Validate_Exception as ValidateException;

class AddIsBestProductAttribute implements DataPatchInterface
{
    public const ATTRIBUTE_CODE = 'oggetto_is_best';

    /**
     * @var EavSetup
     */
    private EavSetup $eavSetup;

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
        $this->eavSetup = $eavSetupFactory->create(['setup' => $moduleDataSetup]);
        $this->logger = $logger;
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
                    'type'     => 'int',
                    'label'    => 'Is Best',
                    'input'    => 'select',
                    'required' => false,
                    'source'   => IsBest::class,
                    'default'  => IsBest::VALUE_NO,
                    'global'   => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'system'   => 1,
                    'group'    => 'General',
                    'visible'  => true,
                    'unique'   => false,
                ]
            );
        } catch (LocalizedException | ValidateException $e) {
            $this->logger->error($e->getMessage());
        }
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
}
