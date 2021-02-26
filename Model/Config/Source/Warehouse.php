<?php

namespace Lof\MultiBarcode\Model\Config\Source;

class Warehouse implements \Magento\Framework\Option\ArrayInterface
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
            $warehouseCollection = $objectManager->create('Lof\Inventory\Model\ResourceModel\Warehouse\Collection');
            $options[] = [
                'label' => "",
                'value' => "",
            ];
            foreach ($warehouseCollection as $key => $item) {
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
