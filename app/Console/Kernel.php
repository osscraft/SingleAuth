<?php

namespace App\Console;

use App\Events\ExceptionEvent;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Application;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\WorkermanServer::class,
        Commands\WorkermanWinServer::class,
    ];

    /**
     * Create a new console kernel instance.
     *
     * @param  \Laravel\Lumen\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $request = $app->request;
        $request->requestId = $this->createRequestId();
        $app->request = $request;
    }
    
    /**
     * 生成唯一请求ID
     */
    private function createRequestId()
    {
        try {
            $id = Uuid::uuid4()->toString();
            return $id;
        } catch (UnsatisfiedDependencyException $e) {
            event(new ExceptionEvent($e));
        }
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
