<?php

namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;
use Lay\Advance\Core\Volatile;
use Lay\Advance\DB\DataBase;

/**
 * QR Code类
 *
 * @category
 *
 * @package classes
 * @author liaiyong <liaiyong@dcux.com>
 * @version 1.0
 * @copyright 2005-2012 dcux Inc.
 * @link http://www.dcux.com
 *
 */
class QrCode extends Model
{
    const STATUS_CREATE = 0;
    const STATUS_SCAN = 1;
    const STATUS_LOGIN = 2;
    protected $code = '';
    protected $time = '';
    protected $expires = 0;
    protected $status = 0;
    public function schema()
    {
        return 'sso';
    }
    public function table()
    {
        return 'qr_code';
    }
    public function primary()
    {
        return 'code';
    }
    public function columns()
    {
        return array(
                'code' => 'code',
                'time' => 'time',
                'expires' => 'expires',
                'status' => 'status'
        );
    }
}
// PHP END
