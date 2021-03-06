<?php

/**
 * Directory District Resource Model
 */
namespace Swatch\Localization\Model\ResourceModel;
/**
 * Class District
 * @package Swatch\Localization\Model\ResourceModel
 */
class District extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Table with localized district names
     *
     * @var string
     */
    protected $_districtNameTable;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $_localeResolver;

    /** District constructor
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
     * Define main and locale district name tables
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('directory_country_district', 'district_id');
        $this->_districtNameTable = $this->getTable('directory_country_district_name');
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

        $districtField = $connection->quoteIdentifier($this->getMainTable() . '.' . $this->getIdFieldName());

        $condition = $connection->quoteInto('lrn.locale = ?', $locale);
        $select->joinLeft(
            ['lrn' => $this->_districtNameTable],
            "{$districtField} = lrn.district_id AND {$condition}",
            []
        );

        if ($locale != $systemLocale) {
            $nameExpr = $connection->getCheckSql('lrn.district_id is null', 'srn.name', 'lrn.name');
            $condition = $connection->quoteInto('srn.locale = ?', $systemLocale);
            $select->joinLeft(
                ['srn' => $this->_districtNameTable],
                "{$districtField} = srn.district_id AND {$condition}",
                ['name' => $nameExpr]
            );
        } else {
            $select->columns(['name'], 'lrn');
        }

        return $select;
    }
}
