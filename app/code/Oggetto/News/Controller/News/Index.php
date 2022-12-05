<?php

declare(strict_types=1);

namespace Oggetto\News\Controller\News;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Theme\Block\Html\Breadcrumbs;

class Index implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var UrlInterface
     */
    protected UrlInterface $url;

    /**
     * @param PageFactory  $resultPageFactory
     * @param UrlInterface $url
     */
    public function __construct(
        PageFactory $resultPageFactory,
        UrlInterface $url,
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->url = $url;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('News List'));

        /** @var Breadcrumbs $breadcrumbs */
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Home'),
                'link'  => $this->url->getUrl(''),
            ],
        );
        $breadcrumbs->addCrumb(
            'news',
            [
                'label' => __('News'),
                'title' => __('News'),
            ],
        );

        return $resultPage;
    }
}
