<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class OAuth2Controller extends Controller
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

    //
    public function authorize($ability, $arguments = [])
    {
        // $this->initSession();

        return 1;
    }
    public function token($ability, $arguments = [])
    {
        // $this->initSession();

        return 1;
    }

    

    private function initSession()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->pk();
            $table->text('data');
            $table->integer('is_online');
            $table->datetime('create_time');
            $table->integer('expire');
        });
    }
}
