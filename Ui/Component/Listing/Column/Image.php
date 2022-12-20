<?php
namespace Mageget\CustomerImage\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Image extends Column
{
    public const URL_PATH_EDIT = 'customer/index/edit';
    /**
     * StoreManager $storeManager
     *
     * @var mixed
     */
    protected $storeManager;
    /**
     * Url $url
     *
     * @var mixed
     */
    protected $url;
    
    /**
     * __construct
     *
     * @param  mixed $context
     * @param  mixed $uiComponentFactory
     * @param  mixed $storeManager
     * @param  mixed $url
     * @param  mixed $components
     * @param  mixed $data
     * @return void
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $url,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
        $this->url = $url;
    }
    /**
     * PrepareDataSource
     *
     * @param  mixed $dataSource
     * @return void
     */
    public function prepareDataSource(array $dataSource)
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        if (isset($dataSource['data']['items'])) {
            $fieldName = 'customer_image';
            foreach ($dataSource['data']['items'] as & $item) {
                if (!empty($item['customer_image'])) {
                    $name = $item['customer_image'];
                    $item[$fieldName . '_src'] = $mediaUrl . 'mageget_customer' . $name;
                    $item[$fieldName . '_alt'] = '';
                    $item[$fieldName . '_link'] =
                    $this->url->getUrl(static ::URL_PATH_EDIT, ['id' => $item['entity_id']]);
                    $item[$fieldName . '_orig_src'] = $mediaUrl . 'mageget_customer' . $name;
                }
            }
        }
        return $dataSource;
    }
}
