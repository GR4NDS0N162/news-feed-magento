<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Plugin;

use Magento\Framework\DataObject;
use Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer;
use Oggetto\BestProduct\Model\Config\Source\YesNoMaybe;
use Oggetto\BestProduct\Setup\Patch\Data\IsBestOrderItemPatch;

class IsBestColumnHtmlPlugin
{
    /**
     * @var YesNoMaybe
     */
    private YesNoMaybe $source;

    /**
     * @param YesNoMaybe $source
     */
    public function __construct(
        YesNoMaybe $source,
    ) {
        $this->source = $source;
    }

    /**
     * @param DefaultRenderer $subject
     * @param string          $result
     * @param DataObject      $item
     * @param string          $column
     * @param null            $field
     * @return string
     */
    public function afterGetColumnHtml(
        DefaultRenderer $subject,
        $result,
        DataObject $item,
        $column,
    ): string {
        if ($column == 'is-best') {
            $result = (string) $this->source->getOptionText(
                $item->getData(IsBestOrderItemPatch::ATTRIBUTE_CODE)
            );
        }
        return $result;
    }
}
