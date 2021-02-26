<?php
namespace Lof\MultiBarcode\Controller\Adminhtml\MultiBarcode;

class Convert extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    /**
     * @var \Lof\MultiBarcode\Model\BarcodeFactory
     */
    private $barcode;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    private $productCollection;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Lof\MultiBarcode\Model\BarcodeFactory $barcode,
        \Lof\MultiBarcode\Helper\Data $helper
    ) {
        $this->_pageFactory = $pageFactory;
        $this->helper = $helper;
        $this->barcode = $barcode;
        $this->productCollection = $productCollection;
        return parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $productCollection = $this->productCollection->addFieldToSelect("barcode");
        foreach ($productCollection as $product) {
            if ($product->getBarcode()) {
                $barcodecollection = $this->barcode->create()->getCollection()->addFieldToFilter("barcode", $product->getBarcode())->getFirstItem();
                if (!$barcodecollection->getData()) {
                    $productId = $product->getId();
                    $barcode = $this->barcode->create();
                    $barcode->setBarcode($product->getBarcode());
                    $barcode->setQty('1');
                    $barcode->setproductId($productId);
                    $barcode->setUrl($productId.$product->getBarcode().".png");
                    $barcode->save();
                    $this->helper->generateBarcode($barcode, $productId);
                    $product->addAttributeUpdate('multi_barcode', $productId.$product->getBarcode().".png", 0);

                }
            }
        }
        $this->messageManager->addSuccessMessage(__('You have converted all single barcode to multi qty barcode.'));
        return $resultRedirect->setPath('*/*/mirgration');
    }
}
