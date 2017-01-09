<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Localization\Helper;


use Swatch\Localization\Model\City;

/**
 * Class Data
 * @package Swatch\Localization\Helper
 */
class Data extends \Magento\Directory\Helper\Data
{
    /**
     * City collection
     *
     * @var \Swatch\Localization\Model\ResourceModel\City\Collection
     */
    protected $_cityCollection;
    /**
     * @var \Swatch\Localization\Model\ResourceModel\City\CollectionFactory
     */
    protected $_cityCollectionFactory;
    /**
     * Json representation of cities data
     *
     * @var string
     */
    protected $_cityJson;


    /**
     * District collection
     *
     * @var \Swatch\Localization\Model\ResourceModel\District\Collection
     */
    protected $_districtCollection;
    /**
     * @var \Swatch\Localization\Model\ResourceModel\District\CollectionFactory
     */
    protected $_districtCollectionFactory;

    /**
     * Json representation of districts data
     *
     * @var string
     */
    protected $_districtJson;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     * @param \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regCollectionFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Swatch\Localization\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
     * @param \Swatch\Localization\Model\ResourceModel\District\CollectionFactory $districtCollectionFactory
     */
    public function __construct(\Magento\Framework\App\Helper\Context $context,
                                \Magento\Framework\App\Cache\Type\Config $configCacheType,
                                \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection,
                                \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regCollectionFactory,
                                \Magento\Framework\Json\Helper\Data $jsonHelper,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Directory\Model\CurrencyFactory $currencyFactory,
                                \Swatch\Localization\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory,
                                \Swatch\Localization\Model\ResourceModel\District\CollectionFactory $districtCollectionFactory)
    {
        $this->_cityCollectionFactory = $cityCollectionFactory;
        $this->_districtCollectionFactory = $districtCollectionFactory;
        parent::__construct($context, $configCacheType, $countryCollection, $regCollectionFactory, $jsonHelper, $storeManager, $currencyFactory);
    }

    /**
     * Retrieve city collection
     *
     * @return \Swatch\Localization\Model\ResourceModel\City\Collection
     */
    public function getCityCollection()
    {
        if (!$this->_cityCollection) {
            $this->_cityCollection = $this->_cityCollectionFactory->create();
        }
        return $this->_cityCollection;
    }

    /**
     * Retrieve district collection
     *
     * @return \Swatch\Localization\Model\ResourceModel\District\Collection
     */
    public function getDistrictCollection()
    {
        if (!$this->_districtCollection) {
            $this->_districtCollection = $this->_districtCollectionFactory->create();
        }
        return $this->_districtCollection;
    }

    /**
     * Get City data
     *
     * @return array
     */
    public function getCityData()
    {
        $countryIds = [];
        foreach ($this->getCountryCollection() as $country) {
            $countryIds[] = $country->getCountryId();
        }
        $collection = $this->_cityCollectionFactory->create();
        $collection->addCountryFilter($countryIds)->load();
        $cities = [];
        foreach ($collection as $city) {
            /** @var $city City */
            if (!$city->getRegionId()) {
                continue;
            }
            $cities[$city->getRegionId()][$city->getCityId()] = [
                'code' => $city->getCode(),
                'name' => (string)__($city->getName()),
            ];
        }
        return $cities;
    }

    /**
     * Retrieve cities data json
     *
     * @return string
     */
    public function getCityJson()
    {
        if (!$this->_regionJson) {
            $cacheKey = 'DIRECTORY_CITIES_JSON_STORE' . $this->_storeManager->getStore()->getId();
            $json = $this->_configCacheType->load($cacheKey);
            if (empty($json)) {
                $cities = $this->getCityData();
                $json = $this->jsonHelper->jsonEncode($cities);
                if ($json === false) {
                    $json = 'false';
                }
                $this->_configCacheType->save($json, $cacheKey);
            }
            $this->_regionJson = $json;
        }
        return $this->_regionJson;
    }

    /**
     * Get district data
     * @return array
     */
    public function getDistrictData()
    {
        $countryIds = [];
        foreach ($this->getCountryCollection() as $country) {
            $countryIds[] = $country->getCountryId();
        }
        $collection = $this->_districtCollectionFactory->create();
        $collection->addCountryFilter($countryIds)->load();
        $districts = [];
        foreach ($collection as $district) {
            /** @var $district District */
            if (!$district->getCityId()) {
                continue;
            }
            $districts[$district->getCityId()][$district->getDistrictId()] = [
                'code' => $district->getCode(),
                'name' => (string)__($district->getName()),
            ];
        }
        return $districts;
    }

    /**
     * Retrieve districts data json
     *
     * @return string
     */
    public function getDistrictJson()
    {
        if (!$this->_cityJson) {
            $cacheKey = 'DIRECTORY_DISTRICTS_JSON_STORE' . $this->_storeManager->getStore()->getId();
            $json = $this->_configCacheType->load($cacheKey);
            if (empty($json)) {
                $districts = $this->getDistrictData();
                $json = $this->jsonHelper->jsonEncode($districts);
                if ($json === false) {
                    $json = 'false';
                }
                $this->_configCacheType->save($json, $cacheKey);
            }
            $this->_cityJson = $json;
        }
        return $this->_cityJson;
    }
}