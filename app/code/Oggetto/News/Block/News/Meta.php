<?php

declare(strict_types=1);

namespace Oggetto\News\Block\News;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Model\NewsFactory;

class Meta extends Template
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
     * @var NewsInterface
     */
    protected NewsInterface $news;

    /**
     * @param Template\Context        $context
     * @param NewsRepositoryInterface $newsRepository
     * @param NewsFactory             $newsFactory
     * @param array                   $data
     */
    public function __construct(
        Template\Context $context,
        NewsRepositoryInterface $newsRepository,
        NewsFactory $newsFactory,
        array $data = [],
    ) {
        $this->newsRepository = $newsRepository;
        $this->newsFactory = $newsFactory;
        parent::__construct($context, $data);
    }

    public function getNewsMetaTitle(): string
    {
        return $this->news->getMetaTitle() ?? '';
    }

    public function getNewsMetaKeywords(): string
    {
        return $this->news->getMetaKeywords() ?? '';
    }

    public function getNewsMetaDescription(): string
    {
        return $this->news->getMetaDescription() ?? '';
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
            }
        }
    }
}
