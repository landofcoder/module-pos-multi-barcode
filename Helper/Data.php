<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_MultiBarcode
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\MultiBarcode\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\App\Helper\AbstractHelper;
use Picqer\Barcode\BarcodeGeneratorPNG;

class Data extends AbstractHelper
{
    const XML_PATH_BARCODE = 'barcode/';

    /**
     * @var File
     */
    private $file;

    /**
     * @var Filesystem\DirectoryList
     */
    private $dir;

    /**
     * @var Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var UploaderFactory
     */
    protected $fileUploader;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Data constructor.
     * @param File $file
     * @param FileFactory $fileFactory
     * @param Filesystem\DirectoryList $dir
     * @param ScopeConfigInterface $scopeConfig
     * @param Filesystem $filesystem
     * @param UploaderFactory $fileUploader
     * @throws FileSystemException
     */
    public function __construct(
        File $file,
        FileFactory $fileFactory,
        Filesystem\DirectoryList $dir,
        ScopeConfigInterface $scopeConfig,
        Filesystem $filesystem,
        UploaderFactory $fileUploader
    ) {
        $this->filesystem = $filesystem;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->file = $file;
        $this->dir = $dir;
        $this->fileFactory = $fileFactory;
        $this->scopeConfig = $scopeConfig;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param $barcode
     * @param $productId
     */
    public function generateBarcode($barcode, $productId)
    {
        $generator = new BarcodeGeneratorPNG();
        $fileContent = $generator->getBarcode($barcode->getBarcode(), $generator::TYPE_CODE_128);
        $this->saveBarcode($fileContent, $productId . $barcode->getBarcode());
    }

    /**
     * @param $folder_name
     * @param $folder_path
     * @return string
     */
    private function getImageBarcodeFolder($folder_name, $folder_path)
    {
        $images = $folder_path . '/' . $folder_name;
        if (!file_exists($images)) {
            $this->file->mkdir($images);
        }
        return $images . '/';
    }

    /**
     * @param $fileContent
     * @param $fileName
     * @return string
     */
    public function saveBarcode($fileContent, $fileName)
    {
        $folder_path = $this->mediaDirectory->getAbsolutePath();
        if (!is_dir($folder_path . "/barcode")) {
            $folder_path = $this->getImageBarcodeFolder("barcode", $folder_path);
        } else {
            $folder_path .= "/barcode";
        }
        file_put_contents($folder_path . '/' . $fileName . ".png", $fileContent);
        return $folder_path;
    }
}
