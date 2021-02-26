<?php
namespace Lof\MultiBarcode\Controller\Adminhtml\MultiBarcode;

class Mirgration extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->_pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Convert single barcodes to multi qty barcodes"));
        return $resultPage;
    }
}
