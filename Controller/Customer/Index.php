<?php
namespace Mageget\CustomerImage\Controller\Customer;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * _pageFactory  $_pageFactory
     *
     * @var mixed
     */
    protected $_pageFactory;
    /**
     * __construct
     *
     * @param mixed $context
     * @param mixed $pageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }
    /**
     * Execute return page factory
     *
     * @return void
     */
    public function execute()
    {
        return $this->_pageFactory->create();
    }
}
