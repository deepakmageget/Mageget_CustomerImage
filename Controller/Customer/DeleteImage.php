<?php
namespace Mageget\CustomerImage\Controller\Customer;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\CustomerFactory as CustomerResourceFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;

class DeleteImage extends \Magento\Framework\App\Action\Action
{
    public const CUSTOM_CUSTOMER_ATTR = 'customer_image';
        
    /**
     * customerResourceFactory $customerResourceFactory
     *
     * @var mixed
     */
    protected $customerResourceFactory;
    /**
     * customerModel $customerModel
     *
     * @var mixed
     */
    protected $customerModel;
    /**
     * customerSession $customerSession
     *
     * @var mixed
     */
    protected $customerSession;
    /**
     * __construct
     *
     * @param  mixed $context
     * @param  mixed $customerResourceFactory
     * @param  mixed $customerModel
     * @param  mixed $customerSession
     * @return void
     */
    public function __construct(
        Context $context,
        CustomerResourceFactory $customerResourceFactory,
        Customer $customerModel,
        CustomerSession $customerSession
    ) {
        parent::__construct($context);
        $this->customerResourceFactory = $customerResourceFactory;
        $this->customerModel = $customerModel;
        $this->customerSession = $customerSession;
    }
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        if ($this->customerSession->isLoggedIn()) {
            try {
                $imagePath = '';
                $customAttributeValue = $this->getRequest()->getParam('customer_image');
                $customerId = $this->customerSession->getCustomer()->getId();
                $customerNew = $this->customerModel->load($customerId);
                $customerData = $customerNew->getDataModel();
                $customerData->setCustomAttribute(self::CUSTOM_CUSTOMER_ATTR, $imagePath);
                $customerNew->updateData($customerData);
                $customerResource = $this->customerResourceFactory->create();
                $customerResource->saveAttribute($customerNew, self::CUSTOM_CUSTOMER_ATTR);
                $this->messageManager->addSuccessMessage('Profile Image Remove Successfully');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
    }
}
