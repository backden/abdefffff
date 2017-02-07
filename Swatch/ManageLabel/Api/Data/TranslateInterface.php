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
    const ID_LABEL = 'id_string';
    const STRING_LABEL = 'string';
    const TRANSLATE_LABEL = 'translate';
    const USE_DEFAULT = 'use_default';

    const USE_DEFAULT_ENABLE = 1;

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
     * @return int
     */
    public function getStoreId();

    /**
     * @return string
     */
    public function getSection();

    /**
     * @return string
     */
    public function getString();

    /**
     * @return string
     */
    public function getTranslate();

    /**
     * @param $useDefault
     * @return mixed
     */
    public function setUseDefault($useDefault);

    /**
     * @return bool
     */
    public function isUseDefault();

    /**
     * @param string $idString
     * @return mixed
     */
    public function setIdString($idString);

    /**
     * @return string
     */

    public function getIdString();

}
