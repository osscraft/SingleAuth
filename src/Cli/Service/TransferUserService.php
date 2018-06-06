<?php
namespace Dcux\Cli\Service;

use Lay\Advance\Core\Service;

use Dcux\Cli\Model\TransferUser;

class TransferUserService extends Service
{
    private $user;
    protected function __construct()
    {
        parent::__construct();
        $this->user = TransferUser::getInstance();
    }
    public static function getInstance()
    {
        $instance = parent::getInstance();
        return $instance;
    }
    // base user
    public function model()
    {
        return $this->user;
    }
}
// PHP END
