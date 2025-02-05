<?php
namespace Protect\AdminActions\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Protect\AdminActions\Model\Logger;
use Magento\Backend\Model\Auth\Session;

class LogAdminAction implements ObserverInterface
{
    protected $logger;
    protected $authSession;

    public function __construct(
        Logger $logger,
        Session $authSession
    ) {
        $this->logger = $logger;
        $this->authSession = $authSession;
    }

    public function execute(Observer $observer)
    {
        $user = $this->authSession->getUser();
        if (!$user) {
            return;
        }

        $event = $observer->getEvent();
        $object = $event->getData('object');
        
        if (!$object) {
            return;
        }

        $this->logger->logAction(
            $user->getUsername(),
            $event->getName(),
            get_class($object),
            $object->getId(),
            [
                'old_data' => $object->getOrigData(),
                'new_data' => $object->getData()
            ]
        );
    }
}
