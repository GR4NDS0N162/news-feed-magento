<?php

namespace Oggetto\News\Api\Data;

interface NewsInterface
{
    public const ID = 'id';
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';
    public const CONTENT = 'content';
    public const CREATION_TIME = 'creation_time';
    public const UPDATE_TIME = 'update_time';
    public const STATUS = 'status';
    public const IMAGE = 'image';
    public const META_TITLE = 'meta_title';
    public const META_KEYWORDS = 'meta_keywords';
    public const META_DESCRIPTION = 'meta_description';
    public const STORES = 'stores';

    /**
     * Get entity id
     *
     * @return string|null
     */
    public function getId();

    public function getTitle(): ?string;

    public function getDescription(): ?string;

    public function getContent(): ?string;

    public function getCreationTime(): ?string;

    public function getUpdateTime(): ?string;

    public function getStatus(): ?int;

    public function getImage(): ?string;

    public function getMetaTitle(): ?string;

    public function getMetaKeywords(): ?string;

    public function getMetaDescription(): ?string;

    public function getStores(): ?array;

    /**
     * Set entity id
     *
     * @param string $id
     * @return NewsInterface
     */
    public function setId($id);

    public function setTitle(string $title): NewsInterface;

    public function setDescription(string $description): NewsInterface;

    public function setContent(string $content): NewsInterface;

    public function setCreationTime(string $creationTime): NewsInterface;

    public function setUpdateTime(string $updateTime): NewsInterface;

    public function setStatus(int $status): NewsInterface;

    public function setImage(string $image): NewsInterface;

    public function setMetaTitle(string $metaTitle): NewsInterface;

    public function setMetaKeywords(string $metaKeywords): NewsInterface;

    public function setMetaDescription(string $metaDescription): NewsInterface;
}
