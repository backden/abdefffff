<?php
namespace Isobar\Localization\Model;

/**
 * Class City
 * @package Isobar\Localization\Model
 */
class City extends \Magento\Framework\Model\AbstractModel
{

  /**
   * init
   */
  protected function _construct()
  {
    $this->_init('Isobar\Localization\Model\ResourceModel\City');
  }

  /**
   * Retrieve city name
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