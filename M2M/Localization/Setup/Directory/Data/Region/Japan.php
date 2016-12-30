<?php
/**
 * Copyright © 2016 Isobar. All rights reserved.
 */

namespace Isobar\Localization\Setup\Directory\Data\Region;

use Isobar\Localization\Setup\Directory;

/**
 * Class Japan
 * @package Isobar\Localization\Setup\RegionData
 */
class Japan extends Directory\Data\Region\RegionAbstract implements Directory\Data\DataInterface
{
    /**
     * Japan constructor.
     */
    public function __construct()
    {
        $this->_localeIndex = [
            'en_US' => 2,
            'ja_JP' => 3
        ];
    }

    /**
     * Get Insert field
     * @return array
     */
    public function getInsertFields()
    {
        return [
            'country_id' => 0,
            'code' => 1,
            'default_name' => 2,
            'sort_order' => 4
        ];
    }
    /**
     * Get data
     * @return array
     */
    public function getData()
    {
        return
            [
                ["JP", "JP-01", "Hokkaido", "北海道", 1],
                ["JP", "JP-02", "Aomori", "青森県", 2],
                ["JP", "JP-03", "Iwate", "岩手県", 3],
                ["JP", "JP-04", "Miyagi", "宮城県", 4],
                ["JP", "JP-05", "Akita", "秋田県", 5],
                ["JP", "JP-06", "Yamagata", "山形県", 6],
                ["JP", "JP-07", "Fukushima", "福島県", 7],
                ["JP", "JP-08", "Ibaraki", "茨城県", 8],
                ["JP", "JP-09", "Tochigi", "栃木県", 9],
                ["JP", "JP-10", "Gunma", "群馬県", 10],
                ["JP", "JP-11", "Saitama", "埼玉県", 11],
                ["JP", "JP-12", "Chiba", "千葉県", 12],
                ["JP", "JP-13", "Tokyo", "東京都", 13],
                ["JP", "JP-14", "Kanagawa", "神奈川県", 14],
                ["JP", "JP-15", "Niigata", "新潟県", 15],
                ["JP", "JP-16", "Toyama", "富山県", 16],
                ["JP", "JP-17", "Ishikawa", "石川県", 17],
                ["JP", "JP-18", "Fukui", "福井県", 18],
                ["JP", "JP-19", "Yamanashi", "山梨県", 19],
                ["JP", "JP-20", "Nagano", "長野県", 20],
                ["JP", "JP-21", "Gifu", "岐阜県", 21],
                ["JP", "JP-22", "Shizuoka", "静岡県", 22],
                ["JP", "JP-23", "Aichi", "愛知県", 23],
                ["JP", "JP-24", "Mie", "三重県", 24],
                ["JP", "JP-25", "Shiga", "滋賀県", 25],
                ["JP", "JP-26", "Kyoto", "京都府", 26],
                ["JP", "JP-27", "Osaka", "大阪府", 27],
                ["JP", "JP-28", "Hyōgo", "兵庫県", 28],
                ["JP", "JP-29", "Nara", "奈良県", 29],
                ["JP", "JP-30", "Wakayama", "和歌山県", 30],
                ["JP", "JP-31", "Tottori", "鳥取県", 31],
                ["JP", "JP-32", "Shimane", "島根県", 32],
                ["JP", "JP-33", "Okayama", "岡山県", 33],
                ["JP", "JP-34", "Hiroshima", "広島県", 34],
                ["JP", "JP-35", "Yamaguchi", "山口県", 35],
                ["JP", "JP-36", "Tokushima", "徳島県", 36],
                ["JP", "JP-37", "Kagawa", "香川県", 37],
                ["JP", "JP-38", "Ehime", "愛媛県", 38],
                ["JP", "JP-39", "Kōchi", "高知県", 39],
                ["JP", "JP-40", "Fukuoka", "福岡県", 40],
                ["JP", "JP-41", "Saga", "佐賀県", 41],
                ["JP", "JP-42", "Nagasaki", "長崎県", 42],
                ["JP", "JP-43", "Kumamoto", "熊本県", 43],
                ["JP", "JP-44", "Ōita", "大分県", 44],
                ["JP", "JP-45", "Miyazaki", "宮崎県", 45],
                ["JP", "JP-46", "Kagoshima", "鹿児島県", 46],
                ["JP", "JP-47", "Okinawa", "沖縄県", 47],
            ];
    }
}