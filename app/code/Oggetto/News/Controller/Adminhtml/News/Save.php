<?php

namespace Oggetto\News\Controller\Adminhtml\News;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Controller\Adminhtml\News as NewsAction;
use Oggetto\News\Model\News;
use Oggetto\News\Model\NewsFactory;

class Save extends NewsAction implements HttpPostActionInterface
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
     * @var UploaderFactory
     */
    protected $uploaderFactory;
    /**
     * @var Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * @param Context $context
     * @param NewsFactory $newsFactory
     * @param NewsRepositoryInterface $newsRepository
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $fileSystem
     * @throws FileSystemException
     */
    public function __construct(
        Context $context,
        NewsFactory $newsFactory,
        NewsRepositoryInterface $newsRepository,
        UploaderFactory $uploaderFactory,
        FileSystem $fileSystem,
    ) {
        parent::__construct($context);
        $this->newsFactory = $newsFactory;
        $this->newsRepository = $newsRepository;
        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $fileSystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
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
                return $this->processNewsReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                error_log($e->getMessage());
                error_log($e->getTraceAsString());
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the news.'));
            }

            return $resultRedirect->setPath('*/*/edit', [NewsInterface::ID => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process and set the block return
     *
     * @param News $model
     * @param array $data
     * @param \Magento\Framework\Controller\Result\Redirect $resultRedirect
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws CouldNotSaveException
     */
    private function processNewsReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect === 'continue') {
            $resultRedirect->setPath('*/*/edit', [NewsInterface::ID => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->newsFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setStatus(News::STATUS_DISABLED);
            $this->newsRepository->save($duplicateModel);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the news.'));
            $resultRedirect->setPath('*/*/edit', [NewsInterface::ID => $id]);
        }
        return $resultRedirect;
    }
}
