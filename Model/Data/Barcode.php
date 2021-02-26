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
