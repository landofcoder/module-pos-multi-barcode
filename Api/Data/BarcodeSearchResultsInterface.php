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

interface BarcodeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get barcode list.
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface[]
     */
    public function getItems();

    /**
     * Set barcode list.
     * @param \Lof\MultiBarcode\Api\Data\BarcodeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
