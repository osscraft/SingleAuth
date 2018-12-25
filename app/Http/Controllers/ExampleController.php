<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
}
