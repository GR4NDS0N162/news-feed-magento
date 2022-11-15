<?php

declare(strict_types=1);

namespace Oggetto\News\Modifier;

use Magento\Framework\App\Request\Http;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Oggetto\News\Api\Data\NewsInterface;

class DateTimes implements ModifierInterface
{
    /**
     * @var Http
     */
    protected Http $request;

    /**
     * @param Http $request
     */
    public function __construct(
        Http $request,
    ) {
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data): array
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta): array
    {
        if ($this->request->getParam(NewsInterface::ID)) {
            $meta['general'] = [
                'children' => [
                    'creation_time' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'formElement'   => Input::NAME,
                                    'componentType' => Field::NAME,
                                    'label'         => __('News Creation Time'),
                                    'visible'       => true,
                                    'required'      => true,
                                    'disabled'      => true,
                                ],
                            ],
                        ],
                    ],
                    'update_time'   => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'formElement'   => Input::NAME,
                                    'componentType' => Field::NAME,
                                    'label'         => __('News Modification Time'),
                                    'visible'       => true,
                                    'required'      => true,
                                    'disabled'      => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        }

        return $meta;
    }
}
