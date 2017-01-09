<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 09/01/2017
 * Time: 17:46
 */

namespace Swatch\ManageLabel\Model\Config\Structure;

use Magento\Config\Model\Config\ScopeDefiner;

class Structure extends \Magento\Config\Model\Config\Structure
{

    /**
     * Structure constructor.
     * @param Data $structureData
     * @param \Magento\Config\Model\Config\Structure\Element\Iterator\Tab $tabIterator
     * @param \Magento\Config\Model\Config\Structure\Element\FlyweightFactory $flyweightFactory
     * @param ScopeDefiner $scopeDefiner
     */
    public function __construct(
        \Swatch\ManageLabel\Model\Config\Structure\Data $structureData,
        \Magento\Config\Model\Config\Structure\Element\Iterator\Tab $tabIterator,
        \Magento\Config\Model\Config\Structure\Element\FlyweightFactory $flyweightFactory,
        ScopeDefiner $scopeDefiner
    ) {
        parent::__construct($structureData, $tabIterator, $flyweightFactory, $scopeDefiner);
    }
}