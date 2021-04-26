<?php
/**
 * Copyright (c) 2020  Lanfofcoder
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
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
