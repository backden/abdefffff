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

    /**
     * Reader constructor.
     * @param \Magento\Framework\Config\FileResolverInterface $fileResolver
     * @param Converter $converter
     * @param \Magento\Config\Model\Config\SchemaLocator $schemaLocator
     * @param \Magento\Framework\Config\ValidationStateInterface $validationState
     * @param CompilerInterface $compiler
     * @param string $fileName
     * @param array $idAttributes
     * @param string $domDocumentClass
     * @param string $defaultScope
     */
    public function __construct(
        \Magento\Framework\Config\FileResolverInterface $fileResolver,
        Converter $converter,
        \Magento\Config\Model\Config\SchemaLocator $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        CompilerInterface $compiler,
        $fileName = 'manage_labels.xml',
        array $idAttributes = [],
        $domDocumentClass = 'Magento\Framework\Config\Dom',
        $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $compiler,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}