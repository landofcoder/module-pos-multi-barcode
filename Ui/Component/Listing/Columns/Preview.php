<?php
namespace Lof\MultiBarcode\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Preview
 * @package Lof\MultiBarcode\Ui\Component\Listing\Columns
 */
class Preview extends Column
{
    /**
     *
     */
    const URL_PREVIEW = 'lof_multibarcode/multibarcode/preview';


    /**
     * @var string
     */
    private $editUrl;
    /**
     * @var
     */
    protected $actionUrlBuilder;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var
     */
    protected $_storeManager;

    /**
     * @param ContextInterface $context
     * @param UrlInterface $urlBuilder
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UrlInterface $urlBuilder,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [],
        $editUrl = self::URL_PREVIEW
    ) {
        $this->editUrl = $editUrl;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
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
                if (isset($item['entity_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['product_id' => $item['entity_id']]),
                        'target'=>'_blank',
                        'label' => __('Preview')
                    ];
                }
            }
        }
        return $dataSource;
    }
}
