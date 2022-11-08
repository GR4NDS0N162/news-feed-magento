<?php

namespace Oggetto\News\Controller\Adminhtml\News;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;
use Oggetto\News\Api\Data\NewsInterfaceFactory;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Model\NewsFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var NewsFactory
     */
    protected $newsFactory;
    /**
     * @var NewsInterfaceFactory
     */
    protected $dataNewsFactory;
    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @param PageFactory $pageFactory
     * @param NewsFactory $newsFactory
     * @param NewsInterfaceFactory $dataNewsFactory
     * @param NewsRepositoryInterface $newsRepository
     */
    public function __construct(
        PageFactory $pageFactory,
        NewsFactory $newsFactory,
        NewsInterfaceFactory $dataNewsFactory,
        NewsRepositoryInterface $newsRepository,
    ) {
        $this->pageFactory = $pageFactory;
        $this->newsFactory = $newsFactory;
        $this->dataNewsFactory = $dataNewsFactory;
        $this->newsRepository = $newsRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }
}
