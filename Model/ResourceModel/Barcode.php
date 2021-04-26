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

namespace Lof\MultiBarcode\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\LocalizedException;

class Barcode extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('lof_multibarcode_barcode', 'barcode_id');
    }

    /**
     * @param AbstractModel $object
     * @return Barcode
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $result = $this->checkNameExits($object);
        return parent::_beforeSave($object);
    }

    /**
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    public function checkNameExits(AbstractModel $object)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('lof_multibarcode_barcode'),
            'barcode'
        )
            ->where(
                'barcode = ?',
                $object->getBarcode()
            );
        $barcodeIds = $connection->fetchCol($select);
        if (count($barcodeIds) > 0) {
            throw new LocalizedException(
                __('Barcode name already exists.')
            );
        }
        return $this;
    }
}
