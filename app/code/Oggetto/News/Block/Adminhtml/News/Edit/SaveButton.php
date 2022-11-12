<?php

namespace Oggetto\News\Block\Adminhtml\News\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        return [
            'label'                      => __('Save'),
            'class'                      => 'save primary',
            'data_attribute'             => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'news_news_form.news_news_form',
                                'actionName' => 'save',
                                'params'     => [
                                    true,
                                    [
                                        'back' => 'continue',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'class_name'                 => Container::SPLIT_BUTTON,
            'options'                    => $this->getOptions(),
            'dropdown_button_aria_label' => __('Save options'),
        ];
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    private function getOptions()
    {
        return [
            [
                'label'          => __('Save & Duplicate'),
                'id_hard'        => 'save_and_duplicate',
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'news_news_form.news_news_form',
                                    'actionName' => 'save',
                                    'params'     => [
                                        true,
                                        [
                                            'back' => 'duplicate',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id_hard'        => 'save_and_close',
                'label'          => __('Save & Close'),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'news_news_form.news_news_form',
                                    'actionName' => 'save',
                                    'params'     => [
                                        true,
                                        [
                                            'back' => 'close',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
