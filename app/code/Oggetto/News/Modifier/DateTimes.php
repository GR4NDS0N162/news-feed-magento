<?php

namespace Oggetto\News\Modifier;

use Magento\Framework\App\Request\Http;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Oggetto\News\Api\Data\NewsInterface;

class DateTimes implements ModifierInterface
{
    /**
     * @var Http
     */
    protected $request;

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
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        if ($this->request->getParam(NewsInterface::ID)) {
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
        }

        return $meta;
    }
}
