#!/bin/sh

# @see http://my.oschina.net/leejun2005/blog/150662
p=$(cd `dirname $0`; pwd)

cd $p
# @see http://blog.sina.com.cn/s/blog_6c9eaa1501011zml.html
nohup php ./once.php >> ./log/job-once.log 2>&1 &

echo "start once job"
