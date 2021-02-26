<?php

namespace Lof\MultiBarcode\Controller\Adminhtml\MultiBarcode;

use Lof\MultiBarcode\Model\BarcodeFactory;
use Lof\MultiBarcode\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ProductFactory;

class Import extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $csv;
    /**
     * @var BarcodeFactory
     */
    private $barcode;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\File\Csv $csv,
        BarcodeFactory $barcode,
        \Lof\MultiBarcode\Helper\Data $helperdata
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->csv = $csv;
        $this->barcode = $barcode;
        $this->helper = $helperdata;

        parent::__construct($context);
    }

    public function execute()
    {
        $datas = $this->getRequest()->getPostValue();
        if (!isset($_FILES['data_import_file'])) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->setActiveMenu('Lof_MultiBarcode::import');
            $resultPage->addBreadcrumb(__('Import Barcodes'), __('Import Barcodes'));
            $resultPage->addBreadcrumb(__('Import Barcodes'), __('Import Barcodes'));
            $resultPage->getConfig()->getTitle()->prepend(__('Import Barcodes'));
            return $resultPage;
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!isset($_FILES['data_import_file']['tmp_name'])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }
        $csvData = $this->csv->getData($_FILES['data_import_file']['tmp_name']);
        $fields = [];
        $datas['entity_id'] = '';
        foreach ($csvData as $row => $data) {
            if ($row == 0) {
                $fields = $data;
            }
            if ($row > 0) {
                $data_field = $data;
                foreach ($data_field as $key => $item) {
                    $datas[$fields[$key]] = isset($data_field[$key]) ? str_replace('"', '', $data_field[$key]) : "";
                }
                $result = $this->importDataBarcode($datas);
                if ($result == "1") {
                    $this->messageManager->addErrorMessage(__('Import Error. Barcode have already existed'));
                    return $resultRedirect->setPath('*/*/import');
                    break;
                } elseif ($result == "0") {
                    $this->messageManager->addErrorMessage(__('Import Error. Please check your csv file'));
                    return $resultRedirect->setPath('*/*/import');
                    break;
                }
            }
        }
        $this->messageManager->addSuccessMessage(__('Import Successful'));
        return $resultRedirect->setPath('*/*/import');
    }

    public function importDataBarcode($datas)
    {
        if (isset($datas['barcode']) && isset($datas['product_id']) && isset($datas['qty']) && isset($datas['warehouse_code']) && isset($datas['source'])) {
            $dataBarcode = [
                'barcode' => $datas['barcode'],
                'product_id' => $datas['product_id'],
                'qty' => $datas['qty'],
                'warehouse_code' => $datas['warehouse_code'],
                'source' => $datas['source']
            ];
        } else {
            return "0";
        }
        $barcode = $this->barcode->create();
        $existedBarcode = $barcode->getCollection()->addFieldToFilter("barcode", $dataBarcode['barcode'])->getFirstItem();
        if ($existedBarcode->getData()) {
            return "1";
        } else {
            $barcode = $this->barcode->create();
            $barcode->setData($dataBarcode);
            $barcode->setUrl($barcode->getProductId().$barcode->getBarcode().".png");
            $barcode->save();
            $this->helper->generateBarcode($barcode, $barcode->getProductId());

        }
    }
}
