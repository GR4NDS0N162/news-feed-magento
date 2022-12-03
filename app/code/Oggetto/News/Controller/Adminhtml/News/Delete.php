<?php

declare(strict_types=1);

namespace Oggetto\News\Controller\Adminhtml\News;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Controller\Adminhtml\News as NewsAction;

class Delete extends NewsAction implements HttpPostActionInterface
{
    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepository;

    /**
     * @param Context $context
     * @param NewsRepositoryInterface $newsRepository
     */
    public function __construct(
        Context $context,
        NewsRepositoryInterface $newsRepository,
    ) {
        parent::__construct($context);
        $this->newsRepository = $newsRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->getRequest()->getParam(NewsInterface::ID);
        if ($id) {
            try {
                $this->newsRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the news.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', [NewsInterface::ID => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a news to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
