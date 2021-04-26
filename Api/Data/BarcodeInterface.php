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

namespace Lof\MultiBarcode\Api\Data;

interface BarcodeInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRODUCT_ID = 'product_id';
    const BARCODE = 'barcode';
    const BARCODE_ID = 'barcode_id';
    const QTY = 'qty';
    const URL = 'url';

    /**
     * Get barcode_id
     * @return string|null
     */
    public function getBarcodeId();

    /**
     * Set barcode_id
     * @param string $barcodeId
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setBarcodeId($barcodeId);

    /**
     * Get barcode
     * @return string|null
     */
    public function getBarcode();

    /**
     * Set barcode
     * @param string $barcode
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setBarcode($barcode);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Lof\MultiBarcode\Api\Data\BarcodeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Lof\MultiBarcode\Api\Data\BarcodeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Lof\MultiBarcode\Api\Data\BarcodeExtensionInterface $extensionAttributes
    );

    /**
     * Get qty
     * @return string|null
     */
    public function getQty();

    /**
     * Set qty
     * @param string $qty
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setQty($qty);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setProductId($productId);

    /**
     * Get url
     * @return string|null
     */
    public function getUrl();

    /**
     * Set url
     * @param string $url
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     */
    public function setUrl($url);
}
