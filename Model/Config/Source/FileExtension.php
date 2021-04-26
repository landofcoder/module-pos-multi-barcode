<?php

namespace Lof\MultiBarcode\Model\Config\Source;

class FileExtension implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '.csv',
                'label' => __('.csv')
            ]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['value' => '.csv', 'label' => __('.csv')];
    }
}
