<?php

namespace Lof\MultiBarcode\Controller\Adminhtml\MultiBarcode;

use Lof\MultiBarcode\Model\Barcode;

class ExportSave extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * ExportSave constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\App\ResourceConnection $Resource
     * @param Barcode $barcode
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\ResourceConnection $Resource,
        Barcode $barcode
    ) {
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->resultPageFactory = $resultPageFactory;
        $this->_resource = $Resource;
        $this->barcode = $barcode;
        $this->directory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $data['file_extension'] = isset($data['file_extension']) ? $data['file_extension'] : ".csv";
        $fileName = $data['file_name'] . $data['file_extension'];
        $fileName = str_replace(" ", "-", $fileName);
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            . "/" . $fileName;

        $this->directory->create('export');
        $stream = $this->directory->openFile($filePath, 'w+');
        $stream->lock();


        $columns = ['barcode_id', 'barcode', 'qty', 'product_id', 'warehouse_code', 'source',];
        foreach ($columns as $column) {
            $header[] = $column;
        }

        $stream->writeCsv($header);
        $barcodeData = $this->getBarcodeData();
        foreach ($barcodeData as $item) {
            $itemData = [];
            $itemData[] = $item->getData('barcode_id');
            $itemData[] = $item->getData('barcode');
            $itemData[] = $item->getData('qty');
            $itemData[] = $item->getData('product_id');
            $itemData[] = $item->getData('warehouse_code');
            $itemData[] = $item->getData('source');
            $stream->writeCsv($itemData);
        }

        $content = [];
        $content['type'] = 'filename'; // must keep filename
        $content['value'] = $filePath;
        $content['rm'] = '1'; //remove csv from var folder
        return $this->fileFactory->create($fileName, $content,
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
    }

    /**
     * @return \Magento\Framework\Data\Collection\AbstractDb|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     */
    protected function getBarcodeData()
    {
        $result = [];
        $result[0] = [
            'barcode_id',
            'barcode',
            'qty',
            'product_id',
            'warehouse_code',
            'source',
        ];
        $data = $this->getRequest()->getParams();
        $filter_barcode_id = isset($data['barcode_id']) ? $data['barcode_id'] : "";
        $filter_barcode = isset($data['barcode']) ? $data['barcode'] : "";
        $filter_qty = isset($data['qty']) ? $data['qty'] : "";
        $filter_product_id = isset($data['product_id']) ? (int)$data['product_id'] : '';
        $filter_warehouse_code = isset($data['warehouse_code']) ? $data['warehouse_code'] : '';
        $filter_source = isset($data['source']) ? $data['source'] : '';
        $barcode = $this->barcode;
        $collectionBarcode = $barcode->getCollection();
        if ($filter_barcode_id) {
            $collectionBarcode->addFieldToFilter("barcode_id", $filter_barcode_id);
        }
        if ($filter_barcode) {
            $collectionBarcode->addFieldToFilter("barcode", $filter_barcode);
        }
        if ($filter_qty) {
            $collectionBarcode->addFieldToFilter("qty", $filter_qty);
        }
        if ($filter_product_id) {
            $collectionBarcode->addFieldToFilter("product_id", $filter_product_id);
        }
        if ($filter_warehouse_code) {
            $collectionBarcode->addFieldToFilter("warehouse_code", $filter_warehouse_code);
        }
        if ($filter_source) {
            $collectionBarcode->addFieldToFilter("source", $filter_source);
        }
        return $collectionBarcode;
    }

    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Lof_MultiBarcode::exportSave');
    }
}
