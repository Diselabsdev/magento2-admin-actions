<?php
namespace Protect\AdminActions\Cron;

use Protect\AdminActions\Helper\Security;

class CleanLogs
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function execute()
    {
        $this->security->cleanOldLogs();
    }
}
