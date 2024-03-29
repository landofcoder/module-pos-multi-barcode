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

namespace Lof\MultiBarcode\Controller\Adminhtml\MultiBarcode;

class Convert extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Lof_MultiBarcode::convert';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @var \Lof\MultiBarcode\Model\BarcodeFactory
     */
    private $barcode;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    private $productCollection;

    /**
     * Convert constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
     * @param \Lof\MultiBarcode\Model\BarcodeFactory $barcode
     * @param \Lof\MultiBarcode\Helper\Data $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Lof\MultiBarcode\Model\BarcodeFactory $barcode,
        \Lof\MultiBarcode\Helper\Data $helper
    ) {
        $this->_pageFactory = $pageFactory;
        $this->helper = $helper;
        $this->barcode = $barcode;
        $this->productCollection = $productCollection;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $productCollection = $this->productCollection->addFieldToSelect("barcode");
        foreach ($productCollection as $product) {
            if ($product->getBarcode()) {
                $barcodecollection = $this->barcode->create()->getCollection()->addFieldToFilter(
                    "barcode",
                    $product->getBarcode()
                )->getFirstItem();
                if (!$barcodecollection->getData()) {
                    $productId = $product->getId();
                    $barcode = $this->barcode->create();
                    $barcode->setBarcode($product->getBarcode());
                    $barcode->setQty('1');
                    $barcode->setproductId($productId);
                    $barcode->setUrl($productId . $product->getBarcode() . ".png");
                    $barcode->save();
                    $this->helper->generateBarcode($barcode, $productId);
                    $product->addAttributeUpdate('multi_barcode', $productId . $product->getBarcode() . ".png", 0);
                }
            }
        }

        $this->messageManager->addSuccessMessage(__('You have converted all single barcode to multi qty barcode.'));
        return $resultRedirect->setPath('*/*/mirgration');
    }
}
