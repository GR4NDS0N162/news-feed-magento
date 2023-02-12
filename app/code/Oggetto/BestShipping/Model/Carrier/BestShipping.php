<?php

declare(strict_types=1);

namespace Oggetto\BestShipping\Model\Carrier;

use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Oggetto\BestProduct\Model\Config\Source\YesNoMaybe;
use Oggetto\BestProduct\Setup\Patch\Data\IsBest;
use Psr\Log\LoggerInterface;

class BestShipping extends AbstractCarrier implements CarrierInterface
{
    public const CARRIER_CODE = 'best';

    /**
     * @var ResultFactory
     */
    private ResultFactory $rateResultFactory;

    /**
     * @var MethodFactory
     */
    private MethodFactory $resultMethodFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory         $rateErrorFactory
     * @param LoggerInterface      $logger
     * @param ResultFactory        $rateResultFactory
     * @param MethodFactory        $resultMethodFactory
     * @param array                $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $resultMethodFactory,
        array $data = []
    ) {
        $this->_code = self::CARRIER_CODE;
        $this->rateResultFactory = $rateResultFactory;
        $this->resultMethodFactory = $resultMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @inheritDoc
     */
    public function collectRates(RateRequest $request): ?Result
    {
        if (!$this->isActive() || !$this->isAvailable($request->getAllItems())) {
            return null;
        }

        $shippingPrice = $this->getShippingPrice($request->getAllItems());

        $result = $this->rateResultFactory->create();

        $method = $this->createResultMethod($shippingPrice);
        $result->append($method);

        return $result;
    }

    private function createResultMethod(float $shippingPrice): Method
    {
        $method = $this->resultMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);

        return $method;
    }

    /**
     * @inheritDoc
     */
    public function getAllowedMethods(): array
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * @param ItemInterface[] $allItems
     * @return bool
     */
    private function isAvailable(array $allItems): bool
    {
        $allMaybe = true;
        foreach ($allItems as $item) {
            $product = $item->getProduct();
            $isBest = $product->getData(IsBest::ATTRIBUTE_CODE) ?? IsBest::DEFAULT_VALUE;
            if ($isBest == YesNoMaybe::VALUE_YES) {
                return true;
            }
            if ($isBest != YesNoMaybe::VALUE_MAYBE) {
                $allMaybe = false;
            }
        }
        return $allMaybe;
    }

    /**
     * @param ItemInterface[]|CartItemInterface[] $allItems
     * @return float
     */
    private function getShippingPrice(array $allItems): float
    {
        $weightsSum = 0;
        $count = 0;
        foreach ($allItems as $item) {
            $product = $item->getProduct();
            $productWeight = $product->getData('weight') ?? 0;
            $weightsSum += $productWeight * $item->getQty();
            $count += $item->getQty();
        }

        return $count != 0
            ? $weightsSum / $count * $this->getPriceCoefficient()
            : 0;
    }

    private function getPriceCoefficient(): float
    {
        return (float) $this->getConfigData('price_coefficient');
    }
}
