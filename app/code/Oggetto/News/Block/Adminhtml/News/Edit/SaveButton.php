<?php

declare(strict_types=1);

namespace Oggetto\News\Block\Adminhtml\News\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    public const REDIRECT_KEY = 'back';
    public const REDIRECT_CONTINUE = 'continue';
    public const REDIRECT_DUPLICATE = 'duplicate';
    public const REDIRECT_CLOSE = 'close';

    /**
     * @inheritDoc
     */
    public function getButtonData(): array
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
                                        self::REDIRECT_KEY => self::REDIRECT_CONTINUE,
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
    private function getOptions(): array
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
                                            self::REDIRECT_KEY => self::REDIRECT_DUPLICATE,
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
                                            self::REDIRECT_KEY => self::REDIRECT_CLOSE,
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
