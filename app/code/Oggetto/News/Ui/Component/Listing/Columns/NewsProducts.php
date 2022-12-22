<?php

declare(strict_types=1);

namespace Oggetto\News\Ui\Component\Listing\Columns;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Controller\Adminhtml\News\Edit;
use Oggetto\News\Model\News\ProductNews;

class NewsProducts extends Column
{
    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;

    /**
     * @var ProductNews
     */
    protected ProductNews $productNews;

    /**
     * @param ContextInterface       $context
     * @param UiComponentFactory     $uiComponentFactory
     * @param DataPersistorInterface $dataPersistor
     * @param ProductNews            $productNews
     * @param array                  $components
     * @param array                  $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        DataPersistorInterface $dataPersistor,
        ProductNews $productNews,
        array $components = [],
        array $data = [],
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->productNews = $productNews;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepare()
    {
        $news = $this->dataPersistor->get(Edit::KEY_NEWS);
        if ($news instanceof NewsInterface) {
            $selectedIds = $this->productNews->getProductIdsById($news->getId());
            $customConfig = ['selected' => $selectedIds];
            $config = array_merge($this->getData('config'), $customConfig);
            $this->setData('config', $config);
        }
        parent::prepare();
    }
}
