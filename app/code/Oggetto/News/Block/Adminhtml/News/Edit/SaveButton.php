<?php

namespace Oggetto\News\Block\Adminhtml\News\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

class SaveButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        return [
            'label'          => __('Save'),
            'class'          => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'news_news_form.news_news_form',
                                'actionName' => 'save',
                            ],
                        ],
                    ],
                ],
            ],
            'class_name'     => Container::DEFAULT_CONTROL,
        ];
    }
}
