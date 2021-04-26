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

namespace Lof\MultiBarcode\Model\Data;

use Lof\MultiBarcode\Api\Data\BarcodeInterface;

class Barcode extends \Magento\Framework\Api\AbstractExtensibleObject implements BarcodeInterface
{
    /**
     * Get barcode_id
     * @return string|null
     */
    public function getBarcodeId()
    {
        return $this->_get(self::BARCODE_ID);
    }

    /**
     * Set barcode_id
     * @param string $barcodeId
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setBarcodeId($barcodeId)
    {
        return $this->setData(self::BARCODE_ID, $barcodeId);
    }

    /**
     * Get barcode
     * @return string|null
     */
    public function getBarcode()
    {
        return $this->_get(self::BARCODE);
    }

    /**
     * Set barcode
     * @param string $barcode
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setBarcode($barcode)
    {
        return $this->setData(self::BARCODE, $barcode);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Lof\MultiBarcode\Api\Data\BarcodeExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Lof\MultiBarcode\Api\Data\BarcodeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Lof\MultiBarcode\Api\Data\BarcodeExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get qty
     * @return string|null
     */
    public function getQty()
    {
        return $this->_get(self::QTY);
    }

    /**
     * Set qty
     * @param string $qty
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId()
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $productId
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Get url
     * @return string|null
     */
    public function getUrl()
    {
        return $this->_get(self::URL);
    }

    /**
     * Set url
     * @param string $url
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }
}
