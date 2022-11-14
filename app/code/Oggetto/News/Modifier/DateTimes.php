<?php

namespace Oggetto\News\Modifier;

use Magento\Ui\Component\Form\Field;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class DateTimes implements ModifierInterface
{
    /**
     * @inheritDoc
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        $meta['general'] = [
            'children' => [
                'creation_time' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement'   => 'input',
                                'componentType' => Field::NAME,
                                'label'         => __('News Creation Time'),
                                'visible'       => 1,
                                'required'      => 1,
                                'disabled'      => true,
                            ],
                        ],
                    ],
                ],
                'update_time'   => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement'   => 'input',
                                'componentType' => Field::NAME,
                                'label'         => __('News Modification Time'),
                                'visible'       => 1,
                                'required'      => 1,
                                'disabled'      => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $meta;
    }
}
