<?php

namespace Lof\MultiBarcode\Controller\Adminhtml\MultiBarcode;

class Export extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Export constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Lof_MultiBarcode::export');
        $resultPage->addBreadcrumb(__('Export Barcodes'), __('Export Barcodes'));
        $resultPage->addBreadcrumb(__('Export Barcodes'), __('Export Barcodes'));
        $resultPage->getConfig()->getTitle()->prepend(__('Export Barcodes'));
        return $resultPage;
    }
}
