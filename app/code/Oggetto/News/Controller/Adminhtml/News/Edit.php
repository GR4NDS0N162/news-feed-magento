<?php

declare(strict_types=1);

namespace Oggetto\News\Controller\Adminhtml\News;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Controller\Adminhtml\News as NewsAction;

class Edit extends NewsAction implements HttpGetActionInterface
{
    public const KEY_NEWS = 'current_news';

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepository;

    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;

    /**
     * @param Context                 $context
     * @param PageFactory             $resultPageFactory
     * @param NewsRepositoryInterface $newsRepository
     * @param DataPersistorInterface  $dataPersistor
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        NewsRepositoryInterface $newsRepository,
        DataPersistorInterface $dataPersistor,
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->newsRepository = $newsRepository;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($newsId = $this->getRequest()->getParam(NewsInterface::ID)) {
            try {
                $model = $this->newsRepository->getById($newsId);
                $this->dataPersistor->set(self::KEY_NEWS, $model);
            } catch (NoSuchEntityException) {
                $this->messageManager->addErrorMessage(__('This news no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $this->dataPersistor->clear(self::KEY_NEWS);
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $newsId ? __('Edit News') : __('Add News'),
            $newsId ? __('Edit News') : __('Add News'),
        );
        $resultPage->getConfig()->getTitle()->prepend(__('News'));
        $resultPage->getConfig()->getTitle()->prepend($newsId ? $model->getTitle() : __('Add News'));
        return $resultPage;
    }
}
