<?php

declare(strict_types=1);

namespace Oggetto\News\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;

abstract class News extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Oggetto_News::news';

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage): Page
    {
        $resultPage->setActiveMenu('Oggetto_News::news')
            ->addBreadcrumb(__('News Module'), __('News Module'));
        return $resultPage;
    }
}
