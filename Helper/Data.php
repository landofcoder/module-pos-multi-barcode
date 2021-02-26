<?php
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
     * @var UploaderFactory $fileUploader
     */
    protected $fileUploader;
    /**
     * @var Filesystem $filesystem
     */
    protected $filesystem;
    const XML_PATH_BARCODE = 'barcode/';
    /**
     * Data constructor.
     *
     * @param File $file
     * @param FileFactory $fileFactory
     * @param Filesystem\DirectoryList $dir
     * @param Filesystem $filesystem
     * @param UploaderFactory $fileUploader$fileFactory
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
        $this->scopeConfig=$scopeConfig;
        $this->fileUploader = $fileUploader;
    }
    public function generateBarcode($barcode,$productId)
    {
        $generator = new BarcodeGeneratorPNG();
        $fileContent = $generator->getBarcode($barcode->getBarcode(), $generator::TYPE_CODE_128);
        $this->saveBarcode($fileContent, $productId.$barcode->getBarcode());
    }
    private function getImageBarcodeFolder($folder_name, $folder_path)
    {
        $images = $folder_path.'/'.$folder_name;
        if (! file_exists($images)) {
            $this->file->mkdir($images);
        }
        return $images . '/';
    }
    /**
     * Save barcode
     * @param $fileContent
     * @param $fileName
     * @return $folder_path
     */
    public function saveBarcode($fileContent, $fileName)
    {
        $folder_path = $this->mediaDirectory->getAbsolutePath();
        if (!is_dir($folder_path."/barcode")) {
            $folder_path = $this->getImageBarcodeFolder("barcode", $folder_path);
        } else {
            $folder_path .= "/barcode";
        }
        file_put_contents($folder_path . '/'.$fileName.".png", $fileContent);
        return $folder_path;
    }
}
