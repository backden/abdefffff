<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 09/01/2017
 * Time: 16:58
 */

namespace Swatch\ManageLabel\Model\Config\Structure;

use Magento\Config\Model\Config\Structure\Converter;
use Magento\Framework\Config\FileIterator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\TemplateEngine\Xhtml\CompilerInterface;

/**
 * Class Reader
 * @package Swatch\ManageLabel\Model\Config\Structure
 * @author long.pham
 */
class Reader extends \Magento\Config\Model\Config\Structure\Reader
{

    protected $prefixFile = 'label_';
    protected $folderTranslate = 'translates';

    protected $scope = 'adminhtml';

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
     * @param array $config Optional: support: prefixFile, folderTranslate, scope
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
        $defaultScope = 'global',
        $config = []
    ) {
        if (isset($config['prefixFile'])) {
            $this->prefixFile = $config['prefixFile'];
        }
        if (isset($config['folderTranslate'])) {
            $this->folderTranslate = $config['folderTranslate'];
        }
        if (isset($config['scope'])) {
            $this->scope = $config['scope'];
        }
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

    /**
     * Load configuration scope
     *
     * @param string|null $scope
     * @return array
     */
    public function read($scope = null)
    {
        $directories = [];
        /**
         * @var FileIterator $dirTranslates
         */
        $dirTranslates = $this->_fileResolver->get($this->folderTranslate, $this->scope);
        while (($path = $dirTranslates->key()) != null) {
            if (is_dir($path)) {
                $directories[] = $path;
            }
            $dirTranslates->next();
        }
        /**
         * Collect all translate section files based on directory path
         */
        $fileSections = [];
        foreach ($directories as $dir) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            foreach ($files as $file) {
                if ($file->isFile() && strpos($file->getFilename(), $this->prefixFile) !== false) {
                    $fileSections[$file->getFilename()] = $file;
                }
            }
        }

        // Collect files based on file name
        $allFiles = [];
        foreach ($fileSections as $file) {
            // Fetch all XML file what have the same file name and in scope
            $fileList = $this->_fileResolver
                ->get($this->folderTranslate . DIRECTORY_SEPARATOR . $file->getFilename(), $this->scope);
            if (!count($fileList)) {
                $fileList = [];
            }
            $allFiles[] = $fileList;
        }

        // Turn all to array
        $output = $this->readFiles($allFiles);
        return $output;
    }

    /**
     * Read configuration files
     *
     * @param [] $fileList
     * @return array
     * @throws LocalizedException
     */
    protected function readFiles($arrList)
    {
        /** @var \Magento\Framework\Config\Dom $configMerger */
        $configMerger = null;
        foreach ($arrList as $fileList) {
            foreach ($fileList as $key => $content) {
                try {
                    $content = $this->processingDocument($content);
                    if (!$configMerger) {
                        $configMerger = $this->_createConfigMerger($this->_domDocumentClass, $content);
                    } else {
                        $configMerger->merge($content);
                    }
                } catch (\Magento\Framework\Config\Dom\ValidationException $e) {
                    throw new LocalizedException(
                        new \Magento\Framework\Phrase("Invalid XML in file %1:\n%2", [$key, $e->getMessage()])
                    );
                }
            }
        }

        if ($this->validationState->isValidationRequired()) {
            $errors = [];
            if ($configMerger && !$configMerger->validate($this->_schemaFile, $errors)) {
                $message = "Invalid Document \n";
                throw new LocalizedException(
                    new \Magento\Framework\Phrase($message . implode("\n", $errors))
                );
            }
        }

        $output = [];
        if ($configMerger) {
            $output = $this->_converter->convert($configMerger->getDom());
        }

        return $output;
    }
}