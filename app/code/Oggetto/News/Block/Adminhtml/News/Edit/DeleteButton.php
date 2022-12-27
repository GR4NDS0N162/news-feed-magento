<?php

declare(strict_types=1);

namespace Oggetto\News\Block\Adminhtml\News\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Oggetto\News\Api\Data\NewsInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getButtonData(): array
    {
        $data = [];
        if ($this->getNewsId()) {
            $data = [
                'label'      => __('Delete'),
                'class'      => 'delete',
                'on_click'   => sprintf(
                    'deleteConfirm(\'%s\', \'%s\', {"data": {}})',
                    __('Are you sure you want to do this?'),
                    $this->getDeleteUrl()
                ),
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * URL to send delete requests to.
     *
     * @return string
     */
    private function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', [NewsInterface::ID => $this->getNewsId()]);
    }
}
