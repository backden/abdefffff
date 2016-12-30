<?php

/**
 * Directory City Resource Model
 */
namespace Isobar\Localization\Model\ResourceModel;
/**
 * Class City
 * @package Isobar\Localization\Model\ResourceModel
 */
class City extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Table with localized city names
     *
     * @var string
     */
    protected $_cityNameTable;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $_localeResolver;

    /**
     * City constructor
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_localeResolver = $localeResolver;
    }

    /**
     * Define main and locale city name tables
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('directory_country_city', 'city_id');
        $this->_cityNameTable = $this->getTable('directory_country_city_name');
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        $connection = $this->getConnection();

        $locale = $this->_localeResolver->getLocale();
        $systemLocale = \Magento\Framework\AppInterface::DISTRO_LOCALE_CODE;

        $cityField = $connection->quoteIdentifier($this->getMainTable() . '.' . $this->getIdFieldName());

        $condition = $connection->quoteInto('lrn.locale = ?', $locale);
        $select->joinLeft(
            ['lrn' => $this->_cityNameTable],
            "{$cityField} = lrn.city_id AND {$condition}",
            []
        );

        if ($locale != $systemLocale) {
            $nameExpr = $connection->getCheckSql('lrn.city_id is null', 'srn.name', 'lrn.name');
            $condition = $connection->quoteInto('srn.locale = ?', $systemLocale);
            $select->joinLeft(
                ['srn' => $this->_cityNameTable],
                "{$cityField} = srn.city_id AND {$condition}",
                ['name' => $nameExpr]
            );
        } else {
            $select->columns(['name'], 'lrn');
        }

        return $select;
    }
}
