<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 09/01/2017
 * Time: 16:58
 */

namespace Swatch\ManageLabel\Model\Config\Structure;


use Magento\Config\Model\Config\Structure\Converter;
use Magento\Framework\View\TemplateEngine\Xhtml\CompilerInterface;

class Reader extends \Magento\Config\Model\Config\Structure\Reader
{

//    public function __construct(
//        \Magento\Framework\Config\FileResolverInterface $fileResolver,
//        Converter $converter,
//        \Magento\Config\Model\Config\SchemaLocator $schemaLocator,
//        \Magento\Framework\Config\ValidationStateInterface $validationState,
//        CompilerInterface $compiler,
//        $fileName = 'label_sections.xml',
//        array $idAttributes = [],
//        $domDocumentClass = 'Magento\Framework\Config\Dom',
//        $defaultScope = 'global'
//    ) {
//        parent::__construct($fileResolver, $converter, $schemaLocator, $validationState, $fileName, $idAttributes, $domDocumentClass, $defaultScope);
//    }

    public function __construct(
        \Magento\Framework\Config\FileResolverInterface $fileResolver,
        Converter $converter,
        \Magento\Config\Model\Config\SchemaLocator $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        CompilerInterface $compiler,
        $fileName = 'label_sections.xml',
        array $idAttributes = [],
        $domDocumentClass = 'Magento\Framework\Config\Dom',
        $defaultScope = 'global'
    ) {
        parent::__construct($fileResolver, $converter, $schemaLocator, $validationState, $compiler, $fileName, $idAttributes, $domDocumentClass, $defaultScope);
    }
}