<?php

namespace Lay\Advance\Util;

use Lay\Advance\Core\Bean;

class Paging extends Bean
{
    protected $page = 0;
    protected $pageSize = 0;
    protected $pageCount = 0;
    protected $count = 0;
    public function build($scope = array(), $page_default = 1, $pageSize_default = 20)
    {
        parent::build($scope);
        if ($this->getPage() == 0) {
            $this->page = $page_default;
        }
        if ($this->getPageSize() == 0) {
            $this->pageSize = $pageSize_default;
        }
        return $this;
    }
    
    /**
     * 运算分页数
     */
    public function carry()
    {
        $page = $this->getPage();
        $pageSize = $this->getPageSize();
        $pageCount = $this->getPageCount();
        $count = $this->getCount();
        $pageCount = ($pageSize > 0) ? floor(($count + $pageSize - 1) / $pageSize) : 0;
        $return = $this->setPageCount(0 + $pageCount);
        if ($page == - 1 || $page > $pageCount) {
            $this->setPage($pageCount);
        }
    }
    public function toPaging()
    {
        if ($this->getPageSize() == - 1) {
            return;
        }
        $pages = $this->toPages(5);
        $page = $this->toArray();
        return array(
                'page' => $page,
                'pages' => $pages
        );
    }
    /**
     * 返回当前页前后各 $count 的页码罗列排序后的数组
     */
    public function toPages($count)
    {
        $this->carry();
        $page = $this->getPage();
        $pageCount = $this->getPageCount();
        $paging = $this->toArray();
        $morePre = true;
        $moreNext = true;
        
        $pages[$page] = array_merge($paging, array(
                'p' => $page,
                'text' => $page
        ));
        for ($i = $count; $i > 0; $i --) {
            $pre = $page - $i;
            $next = $page + $i;
            if ($pre > 0) {
                $pages["$pre"] = array_merge($paging, array(
                        'p' => $pre,
                        'text' => $pre
                ));
                if ($pre == 1) {
                    $morePre = false;
                }
            } else {
                $morePre = false;
            }
            if ($next <= $pageCount) {
                $pages["$next"] = array_merge($paging, array(
                        'p' => $next,
                        'text' => $next
                ));
                if ($next == $pageCount) {
                    $moreNext = false;
                }
            } else {
                $moreNext = false;
            }
        }
        if (count($pages) > 1) {
            ksort($pages);
        }
        if ($morePre) {
            $morep = $page - $count - $count;
            array_unshift($pages, array_merge($paging, array(
                    'p' => ($morep > 0) ? $morep : 1,
                    'text' => '...'
            )));
        }
        if ($moreNext) {
            $moren = $page + $count + $count;
            array_push($pages, array_merge($paging, array(
                    'p' => ($moren <= $pageCount) ? $moren : $pageCount,
                    'text' => '...'
            )));
        }
        if ($page - 1 >= 1) {
            array_unshift($pages, array_merge($paging, array(
                    'p' => $page - 1,
                    'text' => '上一页'
            )));
        }
        if ($page + 1 <= $pageCount) {
            array_push($pages, array_merge($paging, array(
                    'p' => $page + 1,
                    'text' => '下一页'
            )));
        }
        if ($page != 1) {
            array_unshift($pages, array_merge($paging, array(
                    'p' => 1,
                    'text' => '首页'
            )));
        }
        if ($page != $pageCount) {
            array_push($pages, array_merge($paging, array(
                    'p' => $pageCount,
                    'text' => '尾页'
            )));
        }
        
        return $pages;
    }
    /**
     * 转换为 SQL LIMIT 部分
     */
    public function toLimit()
    {
        $page = $this->getPage();
        $pageSize = $this->getPageSize();
        if ($pageSize && $pageSize > 0) {
            $return = $this->carry();
            $pageCount = $this->getPageCount();
            $count = $this->getCount();
            if ($page && $page == 1) {
                //$limit = 'LIMIT ' . ($page * $pageSize);
                $limit = array($page*$pageSize);
            } elseif ($page && $page > 1) {
                //$limit = 'LIMIT ' . (($page - 1) * $pageSize) . ',' . $pageSize;
                $limit = array(($page - 1) * $pageSize, $pageSize);
            } elseif ($page && $page == - 1) {
                //$limit = 'LIMIT ' . (($pageCount - 1) * $pageSize) . ',' . $pageSize;
                $limit = array(($pageCount - 1) * $pageSize, $pageSize);
            }
        } else {
            $limit = array();
        }
        return $limit;
    }
}
// PHP END
