<?php

declare(strict_types=1);

namespace Oggetto\News\Block\News;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Zend_Filter_Exception as FilterException;
use Zend_Filter_Interface as FilterInterface;

class View extends Template
{
    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepository;

    /**
     * @var FilterInterface
     */
    protected FilterInterface $templateProcessor;

    /**
     * @param Context $context
     * @param NewsRepositoryInterface $newsRepository
     * @param FilterInterface $templateProcessor
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NewsRepositoryInterface $newsRepository,
        FilterInterface $templateProcessor,
        array $data = [],
    ) {
        $this->newsRepository = $newsRepository;
        $this->templateProcessor = $templateProcessor;
        parent::__construct($context, $data);
    }

    /**
     * Get news information
     *
     * @return string
     */
    public function getNewsContent(): string
    {
        $newsId = $this->getRequest()->getParam(NewsInterface::ID);
        try {
            $content = $this->newsRepository->getById($newsId)->getContent();
            if (is_null($content)) {
                return '';
            }
            return $this->templateProcessor->filter($content);
        } catch (FilterException|NoSuchEntityException) {
            return '';
        }
    }
}
