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

namespace Lof\MultiBarcode\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Lof\MultiBarcode\Api\Data\BarcodeSearchResultsInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Lof\MultiBarcode\Api\BarcodeRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Lof\MultiBarcode\Model\ResourceModel\Barcode\CollectionFactory as BarcodeCollectionFactory;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Lof\MultiBarcode\Model\ResourceModel\Barcode as ResourceBarcode;
use Magento\Framework\Exception\NoSuchEntityException;
use Lof\MultiBarcode\Api\Data\BarcodeInterfaceFactory;

class BarcodeRepository implements BarcodeRepositoryInterface
{
    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var BarcodeFactory
     */
    protected $barcodeFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var BarcodeSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var BarcodeCollectionFactory
     */
    protected $barcodeCollectionFactory;

    /**
     * @var ResourceBarcode
     */
    protected $resource;

    /**
     * @var ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * @var BarcodeInterfaceFactory
     */
    protected $dataBarcodeFactory;

    /**
     * BarcodeRepository constructor.
     * @param ResourceBarcode $resource
     * @param BarcodeFactory $barcodeFactory
     * @param BarcodeInterfaceFactory $dataBarcodeFactory
     * @param BarcodeCollectionFactory $barcodeCollectionFactory
     * @param BarcodeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ResourceBarcode $resource,
        BarcodeFactory $barcodeFactory,
        BarcodeInterfaceFactory $dataBarcodeFactory,
        BarcodeCollectionFactory $barcodeCollectionFactory,
        BarcodeSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->barcodeFactory = $barcodeFactory;
        $this->barcodeCollectionFactory = $barcodeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataBarcodeFactory = $dataBarcodeFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Lof\MultiBarcode\Api\Data\BarcodeInterface $barcode
    ) {
        /* if (empty($barcode->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $barcode->setStoreId($storeId);
        } */

        $barcodeData = $this->extensibleDataObjectConverter->toNestedArray(
            $barcode,
            [],
            \Lof\MultiBarcode\Api\Data\BarcodeInterface::class
        );

        $barcodeModel = $this->barcodeFactory->create()->setData($barcodeData);

        try {
            $this->resource->save($barcodeModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the barcode: %1',
                $exception->getMessage()
            ));
        }
        return $barcodeModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($barcodeId)
    {
        $barcode = $this->barcodeFactory->create();
        $this->resource->load($barcode, $barcodeId);
        if (!$barcode->getId()) {
            throw new NoSuchEntityException(__('barcode with id "%1" does not exist.', $barcodeId));
        }
        return $barcode->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->barcodeCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Lof\MultiBarcode\Api\Data\BarcodeInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Lof\MultiBarcode\Api\Data\BarcodeInterface $barcode
    ) {
        try {
            $barcodeModel = $this->barcodeFactory->create();
            $this->resource->load($barcodeModel, $barcode->getBarcodeId());
            $this->resource->delete($barcodeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the barcode: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($barcodeId)
    {
        return $this->delete($this->get($barcodeId));
    }
}
