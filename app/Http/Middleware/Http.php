<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;
use App\Events\RequestEvent;

/**
 * @author liaiyong
 */
class Http
{

    public function handle(Request $request, Closure $next)
    {
        // 唯一请求ID
        $request->requestId = $this->createRequestId();
        // HTTP请求日志处理
        event(new RequestEvent($request));

        return $next($request);
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
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }

}
