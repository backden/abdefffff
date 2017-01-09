<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Localization\Setup\Directory\Data;

/**
 * Interface DataInterface
 * @package Swatch\Localization\Setup\Directory\Data
 */
interface DataInterface
{
    /**
     * Get data
     * @return array
     */
    public function getData();

    /**
     * Get insert filed
     * @return array
     */
    public function getInsertFields();

    /**
     * Get field index
     * @param string $locale
     * @return mixed
     */
    public function getNameFieldIndex($locale);

    /**
     * Get primary field name
     * @return string
     */
    public function getPrimaryFieldName();

    /**
     * Get Table
     * @return string
     */
    public function getTable();
    
    /**
     * Get Name table
     * @return string
     */
    public function getNameTable();

    /**
     * Prepare binding data
     * @param $data
     * @param $setup
     * @return array
     */
    public function prepareBindingData(array $data, $setup);
}