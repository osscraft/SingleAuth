<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Session;
use App\Http\Helper\LogHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 每天创建一份日志文件
        // $this->app->configureMonologUsing(function(Logger $monolog) {
        //     $handler = new RotatingFileHandler(storage_path('logs/lumen.log'));
        //     $handler->setFilenameFormat('{date}', 'Ym/d');
        //     return $monolog->pushHandler($handler);
        // });

        // 异常日志单独
        $this->app->singleton('error-log',function($app){
            return new Logger('SYSTEM');
        });
        // SQL日志单独
        $this->app->singleton('sql-log',function($app){
            return new Logger('SQL');
        });
    }

    /**
     * 启动所有应用服务
     *
     * @return void
     */
    public function boot()
    {
        // SQL日志
        DB::listen(function(QueryExecuted $query) {
            $this->_logHelper->info("{$query->sql} - Bindings: ". json_encode($query->bindings) . " - Time: {$query->time}ms", 'SQL');
        });

        // 普通日志配置
        $handlers = [];
        $handler = new RotatingFileHandler(storage_path("logs/lumen.log"));
        $handler->setFilenameFormat('{date}', 'Ym/d');
        // $handler->setFormatter(new LineFormatter(null, null, true, true));
        $handlers[] = $handler;
        app('log')->setHandlers($handlers);

        // 异常日志配置
        $handlers = [];
        $handler = new RotatingFileHandler(storage_path("logs/error.log"));
        $handler->setFilenameFormat('{date}.{filename}', 'Ym/d');
        // $handler->setFormatter(new LineFormatter(null, null, true, true));
        $handlers[] = $handler;
        app('error-log')->setHandlers($handlers);

        // SQL日志配置
        $handlers = [];
        $handler = new RotatingFileHandler(storage_path("logs/sql.log"));
        $handler->setFilenameFormat('{date}.{filename}', 'Ym/d');
        // $handler->setFormatter(new LineFormatter(null, null, true, true));
        $handlers[] = $handler;
        app('sql-log')->setHandlers($handlers);
    }
}
