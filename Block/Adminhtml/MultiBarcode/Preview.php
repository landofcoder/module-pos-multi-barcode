<?php
namespace Lof\MultiBarcode\Block\Adminhtml\MultiBarcode;

use Exception;
use Magento\Framework\View\Element\Template;
use Lof\MultiBarcode\Model\BarcodeFactory;
use Lof\MultiBarcode\Model\ResourceModel\Barcode\CollectionFactory;
use Magento\Framework\UrlInterface;

class Preview extends \Magento\Framework\View\Element\Template
{
    private $barcodeFactory = null;
    protected $urlBuilder;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        BarcodeFactory $barcodeFactory,
        CollectionFactory $collectionFactory,
        UrlInterface $urlBuilder,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->urlBuilder = $urlBuilder;
        $this->collectionFactory = $collectionFactory;
        $this->_filterProvider = $filterProvider;
        $this->barcodeFactory = $barcodeFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(barcodeFactory::class);
    }
    public function execute()
    {
        $model = $this->barcodeFactory->create();
    }

    /**
     * Prepare HTML content
     *
     * @return string
     * @throws Exception
     */
    public function getCmsFilterContent($value = '')
    {
        $html = $this->_filterProvider->getPageFilter()->filter($value);
        return $html;
    }
    public function getAssetUrl($asset)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $assetRepository = $objectManager->get('Magento\Framework\View\Asset\Repository');
        return $assetRepository->createAsset($asset)->getUrl();
    }
    public function getBarcodeCollectionFactory(){
        $collection = $this->collectionFactory->create();
        return $collection;
    }
    public function getProductId()
    {
        $id = $this->getRequest()->getParam('product_id');
        return $id;
    }
    public function getDomain()
    {
        return $this->urlBuilder->getBaseUrl();
    }
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['product_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl('lof_multibarcode/multibarcode/preview', ['product_id' => $item['product_id']]),
                        'target'=>'_blank',
                        'label' => __('Preview')
                    ];
                }
            }
        }
        return $dataSource;
    }
}
