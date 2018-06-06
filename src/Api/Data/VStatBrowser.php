<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;
use Lay\Advance\Core\Model;

use Dcux\Api\Data\VObject;
use Dcux\SSO\Model\StatBrowser;
use stdClass;

class VStatBrowser extends VObject
{
    protected $id = 0;
    protected $browser = '';
    protected $version = '';
    protected $count = 0;
    public function rules()
    {
        return array(
                'id' => Component::TYPE_INTEGER,
                'browser' => Component::TYPE_STRING,
                'version' => Component::TYPE_STRING,
                'count' => Component::TYPE_INTEGER
        );
    }
    public static function parseSimple($statBrowser)
    {
        return parent::parse($statBrowser);
    }
}
// PHP END
