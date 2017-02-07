<?php
/**
 * Created by PhpStorm.
 * User: long.pham
 * Date: 16/01/2017
 * Time: 15:32
 */

namespace Swatch\ManageLabel\Model\Validate;

use Swatch\ManageLabel\Model\Translate;

/**
 * Class TranslateValidator
 * @package Swatch\ManageLabel\Model\Validate
 * @author long.pham
 */
class TranslateValidator extends Translate
{
    protected $isError = false;
    protected $errorFields = [];

    /**
     * Check value of id and set to model
     * @param string $id
     * @return \Swatch\ManageLabel\Api\Data\TranslateInterface
     */
    public function setId($id)
    {
        if (is_null($id) || empty($id)) {
            $this->errorFields[] = static::TRANSLATE_ID;
        }
        return parent::setId($id);
    }

    /**
     * Check label and set to model
     * @param string $idString
     * @return mixed
     */
    public function setIdString($idString)
    {
        if (is_null($idString) || empty($idString)) {
            $this->errorFields[] = static::ID_LABEL;
        }
        return parent::setIdString($idString);
    }

    /**
     * Check section and set to model
     * @param string $section
     */
    public function setSection($section)
    {
        if (is_null($section) || empty($section)) {
            $this->errorFields[] = static::SECTION_NAME;
        }
        return parent::setSection($section);
    }

    /**
     * Check translate value and set to model
     * @param string $section
     */
    public function setString($string)
    {
        if (is_null($string) || empty($string)) {
            $this->errorFields[] = static::STRING_LABEL;
        }
        return parent::setString($string);
    }

    /**
     * If error fields has a value then model is invalid
     * @return bool True if has error
     */
    public function isError()
    {
        if (count($this->errorFields) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Get error fields
     * @return array
     */
    public function getErrorFields()
    {
        return $this->errorFields;
    }
}