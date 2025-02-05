<?php
namespace Protect\AdminActions\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Protect\AdminActions\Helper\Security;

class Logger extends AbstractModel
{
    protected $remoteAddress;
    protected $dateTime;
    protected $security;
    
    public function __construct(
        RemoteAddress $remoteAddress,
        DateTime $dateTime,
        Security $security
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->dateTime = $dateTime;
        $this->security = $security;
    }

    public function logAction($username, $actionType, $objectType, $objectId, $details)
    {
        // Sanitize input
        $username = $this->security->sanitizeInput($username);
        $actionType = $this->security->sanitizeInput($actionType);
        $objectType = $this->security->sanitizeInput($objectType);
        $details = $this->security->sanitizeInput($details);

        $data = [
            'username' => $username,
            'action_type' => $actionType,
            'object_type' => $objectType,
            'object_id' => (int)$objectId,
            'details' => json_encode($details),
            'ip_address' => $this->remoteAddress->getRemoteAddress(),
            'created_at' => $this->dateTime->gmtDate()
        ];
        
        try {
            $this->setData($data)->save();

            // Check for suspicious patterns
            $this->checkSuspiciousActivity($data);
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function checkSuspiciousActivity($data)
    {
        // Check for rapid succession of actions
        $connection = $this->getResource()->getConnection();
        $select = $connection->select()
            ->from($this->getResource()->getMainTable())
            ->where('username = ?', $data['username'])
            ->where('created_at >= DATE_SUB(NOW(), INTERVAL 1 MINUTE)');
            
        $recentActions = $connection->fetchAll($select);
        
        if (count($recentActions) > 10) {
            $this->security->logSuspiciousActivity(
                'rapid_actions',
                [
                    'username' => $data['username'],
                    'count' => count($recentActions),
                    'timeframe' => '1 minute'
                ]
            );
        }
    }

    protected function _construct()
    {
        $this->_init(\Protect\AdminActions\Model\ResourceModel\Logger::class);
    }
}
