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
namespace Lof\MultiBarcode\Block\Adminhtml\Catalog\Product\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

class Barcode extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_barcode;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * Barcode constructor.
     * @param Context $context
     * @param Registry $registry
     * @param \Lof\MultiBarcode\Model\BarcodeFactory $barcodeFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        \Lof\MultiBarcode\Model\BarcodeFactory $barcodeFactory,
        array $data = []
    ) {

        $this->_coreRegistry = $registry;
        $this->_barcode=$barcodeFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    /**
     * @return \Magento\Framework\Data\Collection\AbstractDb|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     */
    public function getBarcodes()
    {
        $barcode=$this->_barcode->create()->getCollection();
        return $barcode;
    }
}
