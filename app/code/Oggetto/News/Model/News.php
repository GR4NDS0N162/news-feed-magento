<?php

declare(strict_types=1);

namespace Oggetto\News\Model;

use Magento\Framework\Model\AbstractModel;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Model\ResourceModel\News as NewsResourceModel;

class News extends AbstractModel implements NewsInterface
{
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function getContent(): ?string
    {
        return $this->getData(self::CONTENT);
    }

    public function getCreationTime(): ?string
    {
        return $this->getData(self::CREATION_TIME);
    }

    public function getUpdateTime(): ?string
    {
        return $this->getData(self::UPDATE_TIME);
    }

    public function getStatus(): ?int
    {
        return (int) $this->getData(self::STATUS);
    }

    public function getImage(): ?string
    {
        return $this->getData(self::IMAGE);
    }

    public function getMetaTitle(): ?string
    {
        return $this->getData(self::META_TITLE);
    }

    public function getMetaKeywords(): ?string
    {
        return $this->getData(self::META_KEYWORDS);
    }

    public function getMetaDescription(): ?string
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    public function setTitle(string $title): News
    {
        return $this->setData(self::TITLE, $title);
    }

    public function setDescription(string $description): News
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function setContent(string $content): News
    {
        return $this->setData(self::CONTENT, $content);
    }

    public function setCreationTime(string $creationTime): News
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    public function setUpdateTime(string $updateTime): News
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    public function setStatus(int $status): News
    {
        return $this->setData(self::STATUS, $status);
    }

    public function setImage(string $image): News
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function setMetaTitle(string $metaTitle): News
    {
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    public function setMetaKeywords(string $metaKeywords): News
    {
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    public function setMetaDescription(string $metaDescription): News
    {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * Prepare news's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses(): array
    {
        return [
            self::STATUS_ENABLED  => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled'),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(NewsResourceModel::class);
    }
}
