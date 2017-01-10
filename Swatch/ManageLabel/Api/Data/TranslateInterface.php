<?php
/**
 * Manage Label Of StoreView
 * Copyright (C) 2016
 *
 * This file included in Swatch/ManageLabel is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Swatch\ManageLabel\Api\Data;

interface TranslateInterface
{

    const TRANSLATE_ID = 'id';
    const STORE_ID = 'store_id';
    const SECTION_NAME = 'section';
    const STRING_LABEL = 'string';
    const TRANSLATE_LABEL = 'translate';


    /**
     * Get id
     * @return string|null
     */

    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \Swatch\ManageLabel\Api\Data\TranslateInterface
     */

    public function setId($id);

    /**
     * @param int $storeId
     * @return void
     */
    public function setStoreId($storeId);

    /**
     * @param string $section
     * @return void
     */
    public function setSection($section);

    /**
     * @param string $string
     * @return void
     */
    public function setString($string);

    /**
     * @param string $translate
     * @return void
     */
    public function setTranslate($translate);

    /**
     * @param $storeId
     * @return int
     */
    public function getStoreId();

    /**
     * @param $section
     * @return string
     */
    public function getSection();

    /**
     * @param $string
     * @return string
     */
    public function getString();

    /**
     * @param $translate
     * @return string
     */
    public function getTranslate();
}
