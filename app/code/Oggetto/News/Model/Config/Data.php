<?php

declare(strict_types=1);

namespace Oggetto\News\Model\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const XML_PATH_NEWS_PER_PAGE = 'news/news_config/news_per_page';

    /**
     * Get news per page
     *
     * @return int
     */
    public function getNewsPerPage(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_NEWS_PER_PAGE,
            ScopeInterface::SCOPE_STORE,
        );
    }
}
