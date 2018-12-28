<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;

class ExampleController extends Controller
{
    /**
     * @var Request
     */
    private $_request;
    /**
     * @var Store
     */
    private $_session;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->_request = $request;
        $this->_session = $request->session();
    }

    public function test($name)
    {
        if(method_exists($this, $name)) {
            return $this->$name();
        }
    }

    //
    public function chmod()
    {
        $bool = chmod(env('APP_PRIVATE_KEY'), 660);

        return ['success' => $bool];
    }

    public function out()
    {
        return 'output';
    }

    public function clean()
    {
        $this->_session->flush();
        
        return 'success';
    }
}
