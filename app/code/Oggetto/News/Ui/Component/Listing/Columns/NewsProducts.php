<?php

declare(strict_types=1);

namespace Oggetto\News\Ui\Component\Listing\Columns;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Oggetto\News\Controller\Adminhtml\News\Edit;

class NewsProducts extends Column
{
    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;

    /**
     * @param ContextInterface       $context
     * @param UiComponentFactory     $uiComponentFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array                  $components
     * @param array                  $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        DataPersistorInterface $dataPersistor,
        array $components = [],
        array $data = [],
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepare()
    {
        if ($news = $this->dataPersistor->get(Edit::KEY_NEWS)) {
            // TODO: Implement the checkmark setting
        }
        parent::prepare();
    }
}
