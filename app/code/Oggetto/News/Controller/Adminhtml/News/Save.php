<?php

namespace Oggetto\News\Controller\Adminhtml\News;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Controller\Adminhtml\News;
use Oggetto\News\Model\NewsFactory;

class Save extends News implements HttpPostActionInterface
{
    /**
     * @var NewsFactory
     */
    protected $newsFactory;
    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @param Context $context
     * @param NewsFactory $newsFactory
     * @param NewsRepositoryInterface $newsRepository
     */
    public function __construct(
        Context $context,
        NewsFactory $newsFactory,
        NewsRepositoryInterface $newsRepository,
    ) {
        parent::__construct($context);
        $this->newsFactory = $newsFactory;
        $this->newsRepository = $newsRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (empty($data[NewsInterface::ID])) {
                $data[NewsInterface::ID] = null;
            }

            $model = $this->newsFactory->create();

            $id = $this->getRequest()->getParam(NewsInterface::ID);
            if ($id) {
                try {
                    $model = $this->newsRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This news no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->newsRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the news.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the news.'));
            }

            return $resultRedirect->setPath('*/*/edit', [NewsInterface::ID => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
