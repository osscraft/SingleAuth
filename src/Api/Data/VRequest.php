<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;

use Dcux\Api\Data\VBasic;

class VRequest extends VBasic
{
    public function properties()
    {
        return array(
            'api' => '',
            'data' => array()
        );
    }
    public function rules()
    {
        return array(
            'api' => Component::TYPE_STRING,
            'data' => Component::TYPE_ARRAY
        );
    }
}
// PHP END
