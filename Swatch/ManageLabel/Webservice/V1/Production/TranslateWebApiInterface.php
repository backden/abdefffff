<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 13/01/2017
 * Time: 10:12
 */

namespace Swatch\ManageLabel\Webservice\V1\Production;

/**
 * Class TranslateInterface
 * @package Swatch\ManageLabel\Webservice\V1
 * @author long.pham
 */
interface TranslateWebApiInterface
{
    const RESPONSE_SUCCESS = 200;
    const RESPONSE_ERROR = 10001;
    const RESPONSE_ERROR_MESSAGE_NOT_SUPPORT = 10002;
    const RESPONSE_ERROR_REQUEST = 10003;
    const RESPONSE_ERROR_DATA_INVALID = 10004;
    const RESPONSE_ERROR_SERVER = 50001;
    const RESPONSE_ERROR_OBJECT = 50002;
    const RESPONSE_ERROR_UNKNOWN_FIELD = 50003;

    /**
     * Webservice
     * Extract and process content request
     * @api
     * @param \Swatch\ManageLabel\Api\Data\TranslateInterface $translate
     * @return mixed
     */
    public function executeProductionWebService(\Swatch\ManageLabel\Api\Data\TranslateInterface $translate);

}