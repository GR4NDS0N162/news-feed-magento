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
    public const IS_ACTIVE = 'is_active';
    public const IMAGE = 'image';

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Get image
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Set id
     *
     * @param int $id
     * @return NewsInterface
     */
    public function setId($id);

    /**
     * Set title
     *
     * @param string $title
     * @return NewsInterface
     */
    public function setTitle($title);

    /**
     * Set description
     *
     * @param string $description
     * @return NewsInterface
     */
    public function setDescription($description);

    /**
     * Set content
     *
     * @param string $content
     * @return NewsInterface
     */
    public function setContent($content);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return NewsInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return NewsInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return NewsInterface
     */
    public function setIsActive($isActive);

    /**
     * Set image
     *
     * @param string $image
     * @return NewsInterface
     */
    public function setImage($image);
}
