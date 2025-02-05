<?php
namespace Protect\AdminActions\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\ResourceConnection;

class Security extends AbstractHelper
{
    protected $encryptor;
    protected $remoteAddress;
    protected $dateTime;
    protected $cacheTypeList;
    protected $resource;

    public function __construct(
        Context $context,
        EncryptorInterface $encryptor,
        RemoteAddress $remoteAddress,
        DateTime $dateTime,
        TypeListInterface $cacheTypeList,
        ResourceConnection $resource
    ) {
        parent::__construct($context);
        $this->encryptor = $encryptor;
        $this->remoteAddress = $remoteAddress;
        $this->dateTime = $dateTime;
        $this->cacheTypeList = $cacheTypeList;
        $this->resource = $resource;
    }

    public function isIpBlocked($ip)
    {
        $connection = $this->resource->getConnection();
        $maxFailures = $this->getMaxLoginFailures();
        $blockDuration = $this->getBlockDuration();
        
        $select = $connection->select()
            ->from('protect_admin_login_attempts')
            ->where('ip_address = ?', $ip)
            ->where('status = 0')
            ->where('created_at >= DATE_SUB(NOW(), INTERVAL ? MINUTE)', $blockDuration);
            
        $failures = $connection->fetchAll($select);
        
        return count($failures) >= $maxFailures;
    }

    public function getMaxLoginFailures()
    {
        return (int) $this->scopeConfig->getValue('admin_security/general/max_login_failures');
    }

    public function getBlockDuration()
    {
        return (int) $this->scopeConfig->getValue('admin_security/general/block_duration');
    }

    public function sanitizeInput($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitizeInput($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    public function logSuspiciousActivity($type, $details)
    {
        if (!$this->scopeConfig->getValue('admin_security/general/notify_suspicious')) {
            return;
        }

        $connection = $this->resource->getConnection();
        $data = [
            'type' => $this->sanitizeInput($type),
            'details' => $this->sanitizeInput(json_encode($details)),
            'ip_address' => $this->remoteAddress->getRemoteAddress(),
            'created_at' => $this->dateTime->gmtDate()
        ];

        try {
            $connection->insert('protect_admin_suspicious_activity', $data);
        } catch (\Exception $e) {
            $this->_logger->critical('Failed to log suspicious activity: ' . $e->getMessage());
        }
    }

    public function cleanOldLogs()
    {
        $retention = (int) $this->scopeConfig->getValue('admin_security/general/log_retention_days');
        if ($retention <= 0) {
            return;
        }

        $connection = $this->resource->getConnection();
        $tables = ['protect_admin_actions_log', 'protect_admin_login_attempts', 'protect_admin_suspicious_activity'];

        foreach ($tables as $table) {
            try {
                $connection->delete(
                    $table,
                    ['created_at < ?' => new \Zend_Db_Expr('DATE_SUB(NOW(), INTERVAL ' . $retention . ' DAY)')]
                );
            } catch (\Exception $e) {
                $this->_logger->critical('Failed to clean old logs from ' . $table . ': ' . $e->getMessage());
            }
        }
    }
}
