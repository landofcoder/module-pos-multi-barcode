<?php

namespace Lof\MultiBarcode\Block\Adminhtml\Import\Edit;

use Magento\Config\Model\Config\Source\Yesno;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var Yesno
     */
    protected $_yesno;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var Yesno
     */
    protected $_yesNo;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Lof\Setup\Model\System\Config\Source\Import\Files $importFiles
     * @param Yesno $yesNo
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        Yesno $yesno,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_yesno = $yesno;
        $this->_systemStore = $systemStore;
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
        if ($this->_isAllowedAction('Lof_MultiBarcode::import')) {
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
                    'action' => '*/*/import',
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Lof Setup Import Barcode')]);

        $fieldset->addField(
            'data_import_file',
            'file',
            [
                'name' => 'data_import_file',
                'label' => __('Upload Csv File'),
                'title' => __('Upload Csv File'),
                'required' => true
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}