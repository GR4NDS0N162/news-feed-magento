<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class YesNoMaybe extends AbstractSource
{
    public const VALUE_NO = 0;
    public const VALUE_YES = 1;
    public const VALUE_MAYBE = 2;

    /**
     * @inheritDoc
     */
    public function getAllOptions(): array
    {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('No'), 'value' => self::VALUE_NO],
                ['label' => __('Yes'), 'value' => self::VALUE_YES],
                ['label' => __('Maybe'), 'value' => self::VALUE_MAYBE],
            ];
        }
        return $this->_options;
    }
}
