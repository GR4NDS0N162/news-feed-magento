<?php

namespace Oggetto\News\Controller\Adminhtml\News;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Model\NewsFactory as NewsFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var NewsFactory
     */
    protected $newsFactory;
    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @param PageFactory $resultPageFactory
     * @param NewsFactory $newsFactory
     * @param NewsRepositoryInterface $newsRepository
     */
    public function __construct(
        PageFactory $resultPageFactory,
        NewsFactory $newsFactory,
        NewsRepositoryInterface $newsRepository,
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->newsFactory = $newsFactory;
        $this->newsRepository = $newsRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('News'));
        return $resultPage;
    }
}
