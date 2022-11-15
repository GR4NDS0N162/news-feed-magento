<?php

declare(strict_types=1);

namespace Oggetto\News\Block\Adminhtml\News\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;

class GenericButton
{
    /**
     * @var Context
     */
    protected Context $context;
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
        $this->context = $context;
        $this->newsRepository = $newsRepository;
    }

    /**
     * Return news ID
     *
     * @return int|null
     */
    public function getNewsId(): ?int
    {
        $id = $this->context->getRequest()->getParam(NewsInterface::ID);
        if ($id) {
            try {
                return $this->newsRepository->getById($id)->getId();
            } catch (NoSuchEntityException) {
                return null;
            }
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
