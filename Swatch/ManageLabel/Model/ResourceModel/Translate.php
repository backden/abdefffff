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

namespace Swatch\ManageLabel\Model\ResourceModel;

use Swatch\ManageLabel\Api\Data\TranslateInterface;

class Translate extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const STORE_DEFAULT_CONFIG = 0;

    protected $sectionDefault = 'all_pages';

    /**
     * Translate constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null|string $connectionName
     * @param array $config
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null,
        array $config = []
    ) {
        parent::__construct($context, $connectionName);
        // Set name of section for rollback case
        if (isset($config['sectionRollbackDefault'])) {
            $this->sectionDefault = $config['sectionRollbackDefault'];
        }
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('swatch_translate', 'id');
    }

    /**
     * Retrieve translation array for section and store code
     * Note: Section for rollback case will load first, if any label is the same then overwrite it
     *
     * @param string $section
     * @param int $storeId
     * @param string $locale
     * @return array
     */
    public function getTranslationArray($section, $storeId)
    {
        // Get rollback translation
        $defaultData = $this->getRollbackTranslateData($storeId);

        // Get translation of each section (except rollback section)
        $dbData = $this->getTranslation($section, $storeId);
        $data = array_merge($defaultData, $dbData);
        return $data;
    }

    /**
     * Retrieve translation array for store / locale code
     * Note: Section for rollback case that will be ignored
     * Auto get default value if no result
     *
     * @param int $section
     * @param int $storeId
     * @return array
     */
    public function getTranslation($section, $storeId)
    {
        if ($storeId === null) {
            $storeId = static::STORE_DEFAULT_CONFIG;
        }
        $data = [];
        $connection = $this->getConnection();
        if ($connection) {
            $select = $connection->select()
                ->from($this->getMainTable(), [
                    TranslateInterface::STRING_LABEL,
                    TranslateInterface::TRANSLATE_LABEL,
                ])
                ->where('store_id = :store_id')
                ->where('section <> :sectionDefault')
                ->order('store_id');
            $bind = [
                ':store_id' => $storeId,
                ':sectionDefault' => $this->sectionDefault
            ];
            if (!is_null($section)) {
                $select->where('section = :section');
                $bind[':section'] = $section;
            }
            $data = $connection->fetchPairs($select, $bind);
            // If current store has no records then use default
            if ($storeId !== static::STORE_DEFAULT_CONFIG && count($data) === 0) {
                $data = $this->getTranslation($section, static::STORE_DEFAULT_CONFIG);
            }
        }
        return $data;
    }

    /**
     * Get translate values of section for rollback case
     * Note: Other section will be ignore
     * Auto get default value if no result
     *
     * @param null $storeId
     * @return array
     */
    public function getRollbackTranslateData($storeId = null)
    {
        if ($storeId === null) {
            $storeId = static::STORE_DEFAULT_CONFIG;
        }
        $data = [];
        $connection = $this->getConnection();
        if ($connection) {
            $select = $connection->select()
                ->from($this->getMainTable(), [
                    TranslateInterface::STRING_LABEL,
                    TranslateInterface::TRANSLATE_LABEL,
                ])
                ->where('store_id = :store_id')
                ->where('section = :section')
                ->order('store_id');
            $bind = [
                ':store_id' => $storeId,
                ':section' => $this->sectionDefault
            ];
            $data = $connection->fetchPairs($select, $bind);
            // If current store has no records then use default
            if ($storeId !== static::STORE_DEFAULT_CONFIG && count($data) === 0) {
                $data = $this->getRollbackTranslateData(static::STORE_DEFAULT_CONFIG);
            }
        }
        return $data;
    }
}
