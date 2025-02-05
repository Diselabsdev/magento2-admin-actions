<?php
namespace Protect\AdminActions\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Logger extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('protect_admin_actions_log', 'log_id');
    }
}
