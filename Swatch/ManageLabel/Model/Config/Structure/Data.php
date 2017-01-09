<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 09/01/2017
 * Time: 17:48
 */

namespace Swatch\ManageLabel\Model\Config\Structure;

class Data extends \Magento\Config\Model\Config\Structure\Data
{

    /**
     * Data constructor.
     * @param Reader $reader
     * @param \Magento\Framework\Config\ScopeInterface $configScope
     * @param \Magento\Framework\Config\CacheInterface $cache
     * @param string $cacheId
     */
    public function __construct(
        \Swatch\ManageLabel\Model\Config\Structure\Reader $reader,
        \Magento\Framework\Config\ScopeInterface $configScope,
        \Magento\Framework\Config\CacheInterface $cache,
        $cacheId
    ) {
        parent::__construct($reader, $configScope, $cache, $cacheId);
    }
}