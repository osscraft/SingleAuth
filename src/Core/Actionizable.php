<?php

namespace Dcux\Core;

interface Actionizable
{
    /**
     * 创建事件触发方法
     */
    public function onCreate();
    /**
     * GET事件触发方法
     */
    public function onGet();
    /**
     * POST事件触发方法
     */
    public function onPost();
    /**
     * PUT事件触发方法
     */
    public function onPut();
    /**
     * DELETE事件触发方法
     */
    public function onDelete();
    /**
     * HEAD事件触发方法
     */
    public function onHead();
    /**
     * PATCH事件触发方法
     */
    public function onPatch();
    /**
     * OPTIONS事件触发方法
     */
    public function onOptions();
    /**
     * 创建事件触发方法
     */
    public function onRender();
}

// PHP END
