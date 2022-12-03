<?php

declare(strict_types=1);

namespace Oggetto\News\Block\News;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Model\NewsFactory;
use Zend_Filter_Exception as FilterException;
use Zend_Filter_Interface as FilterInterface;

class View extends Template
{
    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepository;

    /**
     * @var NewsFactory
     */
    protected NewsFactory $newsFactory;

    /**
     * @var FilterInterface
     */
    protected FilterInterface $templateProcessor;

    /**
     * @var NewsInterface
     */
    protected NewsInterface $news;

    /**
     * @param Context $context
     * @param NewsRepositoryInterface $newsRepository
     * @param NewsFactory $newsFactory
     * @param FilterInterface $templateProcessor
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NewsRepositoryInterface $newsRepository,
        NewsFactory $newsFactory,
        FilterInterface $templateProcessor,
        array $data = [],
    ) {
        $this->newsRepository = $newsRepository;
        $this->newsFactory = $newsFactory;
        $this->templateProcessor = $templateProcessor;
        parent::__construct($context, $data);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();

        $this->news = $this->newsFactory->create();

        if ($newsId = $this->getRequest()->getParam(NewsInterface::ID)) {
            try {
                $this->news = $this->newsRepository->getById($newsId);
            } catch (NoSuchEntityException) {
                $this->news = $this->newsFactory->create();
            }
        }
    }

    /**
     * Get news content
     *
     * @return string
     */
    public function getNewsContent(): string
    {
        $content = $this->news->getContent() ?? '';
        try {
            return $this->templateProcessor->filter($content);
        } catch (FilterException) {
            return '';
        }
    }

    /**
     * Get news meta title
     *
     * @return string
     */
    public function getNewsMetaTitle(): string
    {
        return $this->news->getMetaTitle() ?? '';
    }

    /**
     * Get news meta keywords
     *
     * @return string
     */
    public function getNewsMetaKeywords(): string
    {
        return $this->news->getMetaKeywords() ?? '';
    }

    /**
     * Get news meta description
     *
     * @return string
     */
    public function getNewsMetaDescription(): string
    {
        return $this->news->getMetaDescription() ?? '';
    }
}
