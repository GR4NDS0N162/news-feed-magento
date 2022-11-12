<?php

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
    protected $context;
    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

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
    public function getNewsId()
    {
        try {
            return $this->newsRepository->getById(
                $this->context->getRequest()->getParam(NewsInterface::ID),
            )->getId();
        } catch (NoSuchEntityException) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
