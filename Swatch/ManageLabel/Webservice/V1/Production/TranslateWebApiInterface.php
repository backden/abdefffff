<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 13/01/2017
 * Time: 10:12
 */

namespace Swatch\ManageLabel\Webservice\V1\Production;

use Swatch\Staging\Api\Data\ApproveListInterface;

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

    const ENTITY_TYPE = 'labels';
    const ACTION_TYPE_CREATE = 'create';
    const ACTION_TYPE_UPDATE = 'update';
    const ACTION_TYPE_DELETE = 'delete';

    /**
     * Webservice
     * Extract and process content request
     * @api
     * @param \Swatch\ManageLabel\Api\Data\TranslateInterface $translate
     * @return string
     */
    public function executeProductionWebService(ApproveListInterface $approvedData);

}