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


namespace Lof\MultiBarcode\Block\Adminhtml\Export\Edit;

use Lof\MultiBarcode\Model\Config\Source\FileExtension;
use Lof\MultiBarcode\Model\Config\Source\Source;
use Lof\MultiBarcode\Model\Config\Source\Warehouse;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var
     */
    protected $_groupCollection;

    /**
     * @var Yesno
     */
    protected $_yesno;

    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @var Warehouse
     */
    private $warehouse;

    /**
     * @var Source
     */
    private $source;

    /**
     * Form constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Yesno $yesno
     * @param FileExtension $fileExtension
     * @param Warehouse $warehouse
     * @param Source $source
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Yesno $yesno,
        FileExtension $fileExtension,
        Warehouse $warehouse,
        Source $source,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_yesno = $yesno;
        $this->_fileExtension = $fileExtension;
        $this->warehouse = $warehouse;
        $this->source = $source;
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /**
         * Checking if user have permission to save information
         */
        if ($this->_isAllowedAction('Lof_MultiBarcode::export')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /**
         * @var \Magento\Framework\Data\Form $form
         */

        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/exportsave'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Lof Setup Export Barcode')]);
        $fieldset->addField(
            'file_name',
            'text',
            [
                'name' => 'file_name',
                'label' => __('File Name'),
                'title' => __('File Name'),
                'required' => true,
                'disabled' => $isElementDisabled,
                'note' => __('This will be the name of the file in which configuration will be saved. You can enter any name you want.')
            ]
        );

        $fieldset->addField(
            'file_extension',
            'select',
            [
                'name' => 'file_extension',
                'label' => __('File Extension'),
                'title' => __('File Extension'),
                'values' => $this->_fileExtension->toOptionArray(),
                'disabled' => $isElementDisabled
            ]
        );

        $filter_fieldset = $form->addFieldset('filter_fieldset', ['legend' => __('Filter Barcode')]);
        $filter_fieldset->addField(
            'barcode_id',
            'text',
            [
                'name' => 'barcode_id',
                'label' => __('Barcode Id'),
                'title' => __('Barcode Id'),
                'disabled' => $isElementDisabled
            ]
        );

        $filter_fieldset->addField(
            'barcode',
            'text',
            [
                'name' => 'barcode',
                'label' => __('Barcode'),
                'title' => __('Barcode'),
                'disabled' => $isElementDisabled
            ]
        );

        $filter_fieldset->addField(
            'qty',
            'text',
            [
                'label' => __('Qty'),
                'title' => __('Qty'),
                'name' => 'qty',
                'disabled' => $isElementDisabled
            ]
        );

        $filter_fieldset->addField(
            'product_id',
            'text',
            [
                'name' => 'product_id',
                'label' => __('Product ID'),
                'title' => __('Product ID'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $filter_fieldset->addField(
            'warehouse_code',
            'select',
            [
                'name' => 'warehouse_code',
                'label' => __('Warehouse'),
                'title' => __('Warehouse'),
                'values' => $this->warehouse->toOptionArray(),
                'disabled' => $isElementDisabled
            ]
        );

        $filter_fieldset->addField(
            'source',
            'select',
            [
                'name' => 'source',
                'label' => __('Source'),
                'title' => __('Source'),
                'values' => $this->source->toOptionArray(),
                'disabled' => $isElementDisabled
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * @param $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
