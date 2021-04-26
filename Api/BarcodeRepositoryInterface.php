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

namespace Lof\MultiBarcode\Api;

interface BarcodeRepositoryInterface
{
    /**
     * Save barcode
     * @param \Lof\MultiBarcode\Api\Data\BarcodeInterface $barcode
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Lof\MultiBarcode\Api\Data\BarcodeInterface $barcode
    );

    /**
     * Retrieve barcode
     * @param string $barcodeId
     * @return \Lof\MultiBarcode\Api\Data\BarcodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($barcodeId);

    /**
     * Retrieve barcode matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Lof\MultiBarcode\Api\Data\BarcodeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete barcode
     * @param \Lof\MultiBarcode\Api\Data\BarcodeInterface $barcode
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Lof\MultiBarcode\Api\Data\BarcodeInterface $barcode
    );

    /**
     * Delete barcode by ID
     * @param string $barcodeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($barcodeId);
}
