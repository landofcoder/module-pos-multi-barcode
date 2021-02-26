<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://www.landofcoder.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_MultiBarcode
 * @copyright  Copyright (c) 2020 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\MultiBarcode\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Lof\MultiBarcode\Helper\Data;
use Magento\Framework\Module\Manager;
use Magento\Inventory\Model\ResourceModel\Source\Collection;

/**
 * Lof MultiBarcode ProductSaveAfter Observer.
 */
class ProductSaveAfter implements ObserverInterface
{
    protected $_helper;
    /**
     * @var \Lof\MultiBarcode\Model\BarcodeFactory
     */
    protected $barcode;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $product;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $_messageManager;
    /**
     * @var Collection
     */
    private $source;
    /**
     * @var Manager
     */
    private $_moduleManager;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param Data $helper
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Catalog\Model\ProductFactory $productLoader
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Lof\MultiBarcode\Model\BarcodeFactory $barcode
     * @param Collection $sourceCollection
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        Data $helper,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Model\ProductFactory $productLoader,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Lof\MultiBarcode\Model\BarcodeFactory $barcode,
        Manager $moduleManager,
        Collection $sourceCollection
    ) {
        $this->barcode=$barcode;
        $this->_helper = $helper;
        $this->date = $date;
        $this->_messageManager = $messageManager;
        $this->source = $sourceCollection;
        $this->request=$request;
        $this->_moduleManager = $moduleManager;
        $this->product=$productLoader;
    }

    /**
     * Product delete after event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $product = $observer->getProduct();
            $productId = $observer->getProduct()->getId();
            $barcode_arr = $this->request->getParam("barcodearr");
            $source = $this->source->addFieldToFilter("enabled", "1");
            if (isset($barcode_arr['name'])) {
                foreach ($barcode_arr['name'] as $barcode_key => $name) {
                    $this->updateBarcode($productId, $barcode_key, $barcode_arr, $name);
                    $product->addAttributeUpdate('multi_barcode', $productId.$name.".png", 0);
                }
                $barcode = $this->barcode->create();
                $products_bar = $barcode->getCollection()->addFieldToFilter("product_id", $productId);
                foreach ($products_bar as $item) {
                    if (in_array($item->getBarcode(), $barcode_arr['name'])) {
                    } else {
                        if ($this->_moduleManager->isEnabled('Lof_BarcodeWarehouseIntegration')) {
                            if (count($source->getData())>1) {
                                $item->delete();
                            } else {
                                if ($item->getSource() == "") {
                                    $item->delete();
                                }
                            }
                        } else {
                            $item->delete();
                        }
                    }
                }
            } else {
                $barcode = $this->barcode->create();
                $products_bar = $barcode->getCollection()->addFieldToFilter("product_id", $productId);
                foreach ($products_bar as $item) {
                    if ($this->_moduleManager->isEnabled('Lof_BarcodeWarehouseIntegration')) {
                        if (count($source->getData())>1) {
                            $item->delete();
                        } else {
                            if ($item->getSource() == "") {
                                $item->delete();
                            }
                        }
                    } else {
                        $item->delete();
                    }
                }
            }
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }
    }
    public function updateBarcode(
        $pro_id,
        $barcode_key,
        $barcode_array,
        $name
    ) {
        $model = $this->barcode->create();
        $qty = $barcode_array['qty'][$barcode_key];
        if (isset($barcode_array['warehouse_code'][$barcode_key])) {
            $warehousecode = $barcode_array['warehouse_code'][$barcode_key];
        }
        if (isset($barcode_array['source_code'][$barcode_key])) {
            $sourcecode = $barcode_array['source_code'][$barcode_key];
        }
        $existing = $model->getCollection()->addFieldToFilter("barcode", $name)->addFieldToFilter("product_id", $pro_id)->getData();
        if (count($existing) == 0) {
            $model->setBarcode($name);
            $model->setQty($qty);
            $model->setProductId($pro_id);
            if (isset($warehousecode)) {
                $model->setWarehouseCode($warehousecode);
            }
            if (isset($sourcecode)) {
                $model->setSource($sourcecode);
            }
            $model->setUrl($pro_id.$name.".png");
            $model->save();
            $this->_helper->generateBarcode($model, $pro_id);
        } else {
            $entity_barcode_id = $existing[0]['barcode_id'];
            $model->load($entity_barcode_id);
            if ($qty != $model->getQty()) {
                $model->delete();
                $model = $this->barcode->create();
                $model->setQty($qty);
                $model->setUrl($pro_id.$name.".png");
                $model->setBarcode($name);
                $model->setProductId($pro_id);
                $model->save();
            }
        }
    }
}
