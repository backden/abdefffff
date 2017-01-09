<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Model\Import;

/**
 * Interface TypeInterface
 * @package Swatch\Content\Model\Import
 */
interface TypeInterface
{
    const STATUS_NO_FILE = 0;
    const STATUS_READY = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_COMPLETED = 3;
    const XML_PATH_CONTENT_IMPORT = 'content/import';
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getSource();

    /**
     * @return string
     */
    public function getMode();

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @return boolean
     */
    public function isAuto();
}