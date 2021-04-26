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

namespace Lof\MultiBarcode\Model;

use Magento\Framework\Api\DataObjectHelper;
use Lof\MultiBarcode\Api\Data\BarcodeInterface;
use Lof\MultiBarcode\Api\Data\BarcodeInterfaceFactory;

class Barcode extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var string
     */
    protected $_eventPrefix = 'lof_multibarcode_barcode';

    /**
     * @var BarcodeInterfaceFactory
     */
    protected $barcodeDataFactory;

    /**
     * Barcode constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param BarcodeInterfaceFactory $barcodeDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\Barcode $resource
     * @param ResourceModel\Barcode\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        BarcodeInterfaceFactory $barcodeDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Lof\MultiBarcode\Model\ResourceModel\Barcode $resource,
        \Lof\MultiBarcode\Model\ResourceModel\Barcode\Collection $resourceCollection,
        array $data = []
    ) {
        $this->barcodeDataFactory = $barcodeDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve barcode model with barcode data
     * @return BarcodeInterface
     */
    public function getDataModel()
    {
        $barcodeData = $this->getData();

        $barcodeDataObject = $this->barcodeDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $barcodeDataObject,
            $barcodeData,
            BarcodeInterface::class
        );

        return $barcodeDataObject;
    }
}
