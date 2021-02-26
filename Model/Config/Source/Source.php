<?php

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
            $sourceCollection = $objectManager->create('Magento\Inventory\Model\ResourceModel\Source\Collection')->addFieldToFilter("enabled", '1');
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
