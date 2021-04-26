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

namespace Lof\MultiBarcode\Block\Adminhtml\MultiBarcode;

use Exception;
use Magento\Framework\View\Element\Template;
use Lof\MultiBarcode\Model\BarcodeFactory;
use Lof\MultiBarcode\Model\ResourceModel\Barcode\CollectionFactory;
use Magento\Framework\UrlInterface;

class Preview extends \Magento\Framework\View\Element\Template
{
    /**
     * @var BarcodeFactory|mixed
     */
    private $barcodeFactory = null;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Preview constructor.
     * @param Template\Context $context
     * @param BarcodeFactory $barcodeFactory
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param array $data
     */
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

    /**
     *
     */
    public function execute()
    {
        $model = $this->barcodeFactory->create();
    }

    /**
     * @param string $value
     * @return string
     * @throws Exception
     */
    public function getCmsFilterContent($value = '')
    {
        $html = $this->_filterProvider->getPageFilter()->filter($value);
        return $html;
    }

    /**
     * @param $asset
     * @return mixed
     */
    public function getAssetUrl($asset)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $assetRepository = $objectManager->get('Magento\Framework\View\Asset\Repository');
        return $assetRepository->createAsset($asset)->getUrl();
    }

    /**
     * @return \Lof\MultiBarcode\Model\ResourceModel\Barcode\Collection
     */
    public function getBarcodeCollectionFactory()
    {
        $collection = $this->collectionFactory->create();
        return $collection;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        $id = $this->getRequest()->getParam('product_id');
        return $id;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->urlBuilder->getBaseUrl();
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['product_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'lof_multibarcode/multibarcode/preview',
                            ['product_id' => $item['product_id']]
                        ),
                        'target' => '_blank',
                        'label' => __('Preview')
                    ];
                }
            }
        }

        return $dataSource;
    }
}
