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

namespace Lof\MultiBarcode\Model\Config\Source;

class Source implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $moduleManager = $objectManager->create('Magento\Framework\Module\Manager');
        if ($moduleManager->isEnabled("Lof_Inventory")) {
            $sourceCollection = $objectManager->create('Magento\Inventory\Model\ResourceModel\Source\Collection')->addFieldToFilter("enabled",
                '1');
            $options[] = [
                'label' => "",
                'value' => "",
            ];
            foreach ($sourceCollection as $key => $item) {
                $options[$key] = [
                    'label' => __($item->getWarehouseName()),
                    'value' => $item->getWarehouseCode(),
                ];
            }
        } else {
            $options[] = [];
        }
        return $options;
    }
}
