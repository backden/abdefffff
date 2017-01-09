<?php
namespace Swatch\Localization\Plugin\Quote\Model\Quote;

class Address
{
    public function afterGetLastname(
        \Magento\Quote\Model\Quote\Address $subject,
        $result
    ) {
        if(empty($result))
            $result = '.';
        return $result;
    }
}