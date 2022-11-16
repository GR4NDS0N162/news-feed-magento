<?php

declare(strict_types=1);

namespace Oggetto\News\Model\News\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Oggetto\News\Model\News;

class Status implements OptionSourceInterface
{
    /**
     * @var News
     */
    protected News $news;

    /**
     * @param News $news
     */
    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        $availableOptions = $this->news->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
