<?php

namespace Lof\MultiBarcode\Controller\Adminhtml\MultiBarcode;

use Exception;
use Lof\MultiBarcode\Model\BarcodeFactory;
use Lof\MultiBarcode\Helper\Data;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Import
 * @package Lof\MultiBarcode\Controller\Adminhtml\MultiBarcode
 */
class Import extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Csv
     */
    protected $csv;
    /**
     * @var BarcodeFactory
     */
    private $barcode;
    /**
     * @var Data
     */
    private $helper;

    /**
     * Import constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Csv $csv
     * @param BarcodeFactory $barcode
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Csv $csv,
        BarcodeFactory $barcode,
        Data $helperData
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->csv = $csv;
        $this->barcode = $barcode;
        $this->helper = $helperData;
        parent::__construct($context);
    }


    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     * @throws LocalizedException
     */
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
            throw new LocalizedException(__('Invalid file upload attempt.'));
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


    /**
     * @param $datas
     * @return string
     * @throws Exception
     */
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
