<?php

/**
 * City collection
 */
namespace Swatch\Localization\Model\ResourceModel\City;
/**
 * Class Collection
 * @package Swatch\Localization\Model\ResourceModel\City
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $_localeResolver;

    /**
     * Collection constructor.
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_localeResolver = $localeResolver;
        $this->_resource = $resource;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Init function
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Swatch\Localization\Model\City', 'Swatch\Localization\Model\ResourceModel\City');
        $this->addOrder('name', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
        $this->addOrder('default_name', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
    }

    /**
     * Initialize select object
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $locale = $this->_localeResolver->getLocale();
        $this->addBindParam(':city_locale', $locale);
        $this->getSelect()->joinLeft(
            ['cname' => $this->getResource()->getTable('directory_country_city_name')],
            'main_table.city_id = cname.city_id AND cname.locale = :city_locale',[]
        );
        $this->getSelect()->columns(['name' => new \Zend_Db_Expr('IF(ISNULL(name),default_name,name)')]);

        return $this;
    }

    /**
     * Convert collection items to select options array
     *
     * @return array
     */
    public function toOptionArray()
    {

        $options = [];
        $propertyMap = [
            'value' => 'city_id',
            'title' => 'default_name',
            'region_id' => 'region_id',
            'label'  => 'name'
        ];

        foreach ($this as $item) {
            $option = [];
            foreach ($propertyMap as $code => $field) {
                $option[$code] = $item->getData($field);
            }
            $options[] = $option;
        }
        if (count($options) > 0) {
            array_unshift(
                $options,
                ['title' => null, 'value' => null, 'label' => __('Please select a city.')]
            );
        }
        return $options;
    }

    /**
     * Add country filter
     * @param array $countryIds
     * @return $this
     */
    public function addCountryFilter($countryIds)
    {
        $this->addFieldToFilter('country_id', $countryIds);
        return $this;
    }

}
