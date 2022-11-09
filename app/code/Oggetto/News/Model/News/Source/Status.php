<?php

namespace Oggetto\News\Model\News\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Oggetto\News\Model\News;

class Status implements OptionSourceInterface
{
    /**
     * @var News
     */
    protected $news;

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
    public function toOptionArray()
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
