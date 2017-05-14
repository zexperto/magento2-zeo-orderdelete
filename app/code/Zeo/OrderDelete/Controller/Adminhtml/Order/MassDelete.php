<?php
/**
 * Copyright © 2015 Magento.
 * All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zeo\OrderDelete\Controller\Adminhtml\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\DeploymentConfig;

class MassDelete extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
   
    public function __construct(
        Context $context,
        ResourceConnection $resource,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DeploymentConfig $deploymentConfig
    ) {
        $this->_resource = $resource;
        parent::__construct($context, $filter);
        $this->deploymentConfig = $deploymentConfig;
        $this->collectionFactory = $collectionFactory;
        
    }

    /**
     * Cancel selected orders
     * 
     * @param AbstractCollection $collection            
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    protected function massAction(AbstractCollection $collection)
    {

        $countDeleteOrder = 0;
        foreach ($collection->getItems() as $order) {
            $order->delete();
            $countDeleteOrder++;
        }
        $countNonDeleteOrder = $collection->count() - $countDeleteOrder;
        
        if ($countNonDeleteOrder && $countDeleteOrder) {
            $this->messageManager->addError(__('%1 order(s) were not deleted.', $countNonDeleteOrder));
        } elseif ($countNonDeleteOrder) {
            $this->messageManager->addError(__('No order(s) were deleted.'));
        }
        
        if ($countDeleteOrder) {
            $this->messageManager->addSuccess(__('You have delete %1 order(s).', $countDeleteOrder));
        }
        
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($this->getComponentRefererUrl());
        return $resultRedirect;
    }
}