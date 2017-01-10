<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 10/01/2017
 * Time: 17:18
 */

namespace Swatch\ManageLabel\Model;


use Magento\Framework\Api\Search\SearchResult;
use Magento\Framework\Api\SearchResultsInterface;
use Swatch\ManageLabel\Api\Data\TranslateSearchResultsInterface;

/**
 * Class TranslateSearchResults to implement TranslateSearchResultInterface
 * @package Swatch\ManageLabel\Model
 * @author long.pham
 */
class TranslateSearchResults extends SearchResult implements TranslateSearchResultsInterface
{
}