<?php
namespace Swatch\Localization\Model;

/**
 * Class District
 * @package Swatch\Localization\Model
 */
class District extends \Magento\Framework\Model\AbstractModel{

    /**
     * Init function
     *
     * @return void
     */
    protected function _construct(){
        $this->_init('Swatch\Localization\Model\ResourceModel\District');
    }

    /**
     * Retrieve district name
     *
     * If name is no declared, then default_name is used
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->getData('name');
        if ($name === null) {
            $name = $this->getData('default_name');
        }
        return $name;
    }

}