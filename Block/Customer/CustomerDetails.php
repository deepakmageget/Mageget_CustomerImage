<?php
namespace Mageget\CustomerImage\Block\Customer;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\CustomerFactory as CustomerResourceFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\UrlInterface;

class CustomerDetails extends \Magento\Framework\View\Element\Template
{
/**
 * customerResourceFactory customerResourceFactory
 *
 *
 * @var mixed
 */
    protected $customerResourceFactory;
/**
 * customerModel customerModel
 *
 * @var mixed
 */
    protected $customerModel;
/**
 * customerSession customerSession
 *
 * @var mixed
 */
    protected $customerSession;
       
    /**
     * _url _url
     *
     * @var mixed
     */
    protected $_url;
    
    /**
     * __construct
     *
     * @param  mixed $context
     * @param  mixed $customerResourceFactory
     * @param  mixed $customerModel
     * @param  mixed $customerSession
     * @param  mixed $storeManager
     * @param  mixed $url
     * @return void
     */
    public function __construct(
        Context $context,
        CustomerResourceFactory $customerResourceFactory,
        Customer $customerModel,
        CustomerSession $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        UrlInterface $url
    ) {
        parent::__construct($context);
        $this->customerResourceFactory = $customerResourceFactory;
        $this->customerModel = $customerModel;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
    }
    /**
     * Return customerId to customer session
     *
     * @return void
     */
    public function customerId()
    {
        return $customerId = $this->customerSession->getCustomer()->getId();
    }
    /**
     * Return CustomersCollection
     *
     * @return void
     */
    public function getCustomersCollection()
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        return $this->customerModel->getCollection()->addFieldToFilter('entity_id', $customerId)
        ->addAttributeToSelect("*")->load();
    }
    /**
     * Return mediaurl
     *
     * @return void
     */
    public function mediaurl()
    {
        return $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }
    /**
     * Return BaseUrl
     *
     * @return void
     */
    public function baseUrl()
    {
        return $baseUrl = $this->storeManager->getStore()->getBaseUrl();
    }
}
