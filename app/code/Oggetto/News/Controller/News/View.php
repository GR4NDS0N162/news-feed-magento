<?php

declare(strict_types=1);

namespace Oggetto\News\Controller\News;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Theme\Block\Html\Breadcrumbs;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;

class View implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepository;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var UrlInterface
     */
    protected UrlInterface $url;

    /**
     * @param PageFactory $resultPageFactory
     * @param NewsRepositoryInterface $newsRepository
     * @param RequestInterface $request
     * @param UrlInterface $url
     */
    public function __construct(
        PageFactory $resultPageFactory,
        NewsRepositoryInterface $newsRepository,
        RequestInterface $request,
        UrlInterface $url,
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->newsRepository = $newsRepository;
        $this->request = $request;
        $this->url = $url;
    }

    /**
     * @inheritDoc
     *
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $newsId = $this->request->getParam(NewsInterface::ID);
        $news = $this->newsRepository->getById($newsId);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($news->getTitle());

        /** @var Breadcrumbs $breadcrumbs */
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Home'),
                'link' => $this->url->getUrl(''),
            ],
        );
        $breadcrumbs->addCrumb(
            'news',
            [
                'label' => __('News'),
                'title' => __('News'),
                'link' => $this->url->getUrl('news/news/index'),
            ],
        );
        $breadcrumbs->addCrumb(
            'currentNews',
            [
                'label' => $news->getTitle(),
                'title' => $news->getTitle(),
            ],
        );

        return $resultPage;
    }
}
