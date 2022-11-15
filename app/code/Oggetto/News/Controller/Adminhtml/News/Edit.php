<?php

namespace Oggetto\News\Controller\Adminhtml\News;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Controller\Adminhtml\News as NewsAction;

class Edit extends NewsAction implements HttpGetActionInterface
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

        if ($id) {
            try {
                $model = $this->newsRepository->getById($id);
            } catch (NoSuchEntityException) {
                $this->messageManager->addErrorMessage(__('This news no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit News') : __('Add News'),
            $id ? __('Edit News') : __('Add News'),
        );
        $resultPage->getConfig()->getTitle()->prepend(__('News'));
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getTitle() : __('Add News'));
        return $resultPage;
    }
}
