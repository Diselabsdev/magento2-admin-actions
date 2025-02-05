<?php
namespace Protect\AdminActions\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\App\ResourceConnection;

class LogLoginAttempt implements ObserverInterface
{
    protected $remoteAddress;
    protected $resource;

    public function __construct(
        RemoteAddress $remoteAddress,
        ResourceConnection $resource
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->resource = $resource;
    }

    public function execute(Observer $observer)
    {
        $user = $observer->getEvent()->getUser();
        $connection = $this->resource->getConnection();
        
        $data = [
            'username' => $user->getUsername(),
            'ip_address' => $this->remoteAddress->getRemoteAddress(),
            'status' => (int)$observer->getEvent()->getStatus(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            $connection->insert('protect_admin_login_attempts', $data);
        } catch (\Exception $e) {
            // Log error if needed
        }
    }
}
