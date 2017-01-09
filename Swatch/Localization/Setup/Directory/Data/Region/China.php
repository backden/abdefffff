<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Localization\Setup\Directory\Data\Region;
use Swatch\Localization\Setup\Directory;
/**
 * Class Japan
 * @package Swatch\Localization\Setup\RegionData
 */
class China extends Directory\Data\Region\RegionAbstract implements Directory\Data\DataInterface
{
    /**
     * China constructor.
     */
    public function __construct()
    {
        $this->_localeIndex = [
            'en_US' => 3,
            'zh_CN' => 4
        ];
    }

    /**
     * Get Data
     * @return array
     */
    public function getData()
    {
        return [
            ["CN","Beijing","北京","Beijing","北京"],
            ["CN","Shanghai","上海","Shanghai","上海"],
            ["CN","Hebei","河北省","Hebei","河北省"],
            ["CN","Shan1xi","山西省","Shan1xi","山西省"],
            ["CN","Liaoning","辽宁省","Liaoning","辽宁省"],
            ["CN","Jilin","吉林省","Jilin","吉林省"],
            ["CN","Heilongjiang","黑龙江省","Heilongjiang","黑龙江省"],
            ["CN","Jiangsu","江苏省","Jiangsu","江苏省"],
            ["CN","Zhejiang","浙江省","Zhejiang","浙江省"],
            ["CN","Anhui","安徽省","Anhui","安徽省"],
            ["CN","Fujian","福建省","Fujian","福建省"],
            ["CN","Jiangxi","江西省","Jiangxi","江西省"],
            ["CN","Shandong","山东省","Shandong","山东省"],
            ["CN","Henan","河南省","Henan","河南省"],
            ["CN","Hubei","湖北省","Hubei","湖北省"],
            ["CN","Hunan","湖南省","Hunan","湖南省"],
            ["CN","Chongqing","重庆市","Chongqing","重庆市"],
            ["CN","Guangdong","广东省","Guangdong","广东省"],
            ["CN","Hainan","海南省","Hainan","海南省"],
            ["CN","Sichuan","四川省","Sichuan","四川省"],
            ["CN","Guizhou","贵州省","Guizhou","贵州省"],
            ["CN","Yunnan","云南省","Yunnan","云南省"],
            ["CN","Shan3xi","陕西省","Shan3xi","陕西省"],
            ["CN","Gansu","甘肃省","Gansu","甘肃省"],
            ["CN","Qinghai","青海省","Qinghai","青海省"],
            ["CN","Tianjin","天津","Tianjin","天津"],
            ["CN","Neimenggu","内蒙古","Neimenggu","内蒙古"],
            ["CN","Guangxi","广西","Guangxi","广西"],
            ["CN","Xinjiang","新疆","Xinjiang","新疆"],
            ["CN","Xizang","西藏","Xizang","西藏"],
            ["CN","Ningxia","宁夏","Ningxia","宁夏"],
        ];
    }
}