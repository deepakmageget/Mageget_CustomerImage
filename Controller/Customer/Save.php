<?php
namespace Mageget\CustomerImage\Controller\Customer;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\CustomerFactory as CustomerResourceFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Save extends \Magento\Framework\App\Action\Action
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
     * uploaderFactory $uploaderFactory
     *
     * @var mixed
     */
    protected $uploaderFactory;
    /**
     * _mediaDirectory $_mediaDirectory
     *
     * @var mixed
     */
    protected $_mediaDirectory;
    /**
     * adapterFactory $adapterFactory
     *
     * @var mixed
     */
    protected $adapterFactory;
    /**
     * filesystem $filesystem
     *
     * @var mixed
     */
    protected $filesystem;
    
    /**
     * __construct
     *
     * @param  mixed $uploaderFactory
     * @param  mixed $adapterFactory
     * @param  mixed $filesystem
     * @param  mixed $context
     * @param  mixed $customerResourceFactory
     * @param  mixed $customerModel
     * @param  mixed $customerSession
     * @return void
     */
    public function __construct(
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        Context $context,
        CustomerResourceFactory $customerResourceFactory,
        Customer $customerModel,
        CustomerSession $customerSession
    ) {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        $this->customerResourceFactory = $customerResourceFactory;
        $this->customerModel = $customerModel;
        $this->customerSession = $customerSession;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    }
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomer()->getId();
            $files = $this->getRequest()->getFiles();
            if (isset($files['customer_image']) && !empty($files['customer_image']["name"])) {
                try {
                    $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'customer_image']);
                    //check upload file type or extension
                    $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'png']);
                    $imageAdapter = $this->adapterFactory->create();
                    $imageAdapter = $this->adapterFactory->create();
                    $uploaderFactory->addValidateCallback('custom_image_upload', $imageAdapter, 'validateUploadFile');
                    $uploaderFactory->setAllowRenameFiles(true);
                    $uploaderFactory->setFilesDispersion(true);
                    $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                    $destinationPath = $mediaDirectory->getAbsolutePath('mageget_customer');
                    $result = $uploaderFactory->save($destinationPath);
                    if (!$result) {
                        throw new LocalizedException(__('File cannot be saved to path: $1', $destinationPath));
                    }
                    $imagePath = $result['file'];
                    $customAttributeValue = $this->getRequest()->getParam('customer_image');
                    $customerId = $this->customerSession->getCustomer()->getId();
                    $customerNew = $this->customerModel->load($customerId);
                    $customerData = $customerNew->getDataModel();
                    $customerData->setCustomAttribute(self::CUSTOM_CUSTOMER_ATTR, $imagePath);
                    $customerNew->updateData($customerData);
                    $customerResource = $this->customerResourceFactory->create();
                    $customerResource->saveAttribute($customerNew, self::CUSTOM_CUSTOMER_ATTR);
                    $this->messageManager->addSuccessMessage('Profile Image Has Been Updated Successfully');
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        }
    }
}
