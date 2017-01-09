<?php
namespace Swatch\Localization\Plugin\Customer\Model\Data;

class Address
{
    public function afterGetLastname(
        \Magento\Customer\Model\Data\Address $subject,
        $result
    ) {
        if(empty($result))
            $result = '.';
        return $result;
    }
}