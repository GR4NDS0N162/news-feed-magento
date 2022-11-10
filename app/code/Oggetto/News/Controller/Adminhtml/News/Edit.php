<?php

namespace Oggetto\News\Controller\Adminhtml\News;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param NewsRepositoryInterface $newsRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        NewsRepositoryInterface $newsRepository,
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->newsRepository = $newsRepository;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(NewsInterface::ID);

        try {
            $model = $this->newsRepository->getById($id);
        } catch (LocalizedException $ex) {
            $this->messageManager->addErrorMessage(__($ex->getMessage()));
            /** @var Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(__('Edit News'), __('Edit News'));
        $resultPage->getConfig()->getTitle()->prepend(__('News'));
        $resultPage->getConfig()->getTitle()->prepend($model->getTitle());
        return $resultPage;
    }
}
