#!/bin/sh
# @see http://my.oschina.net/leejun2005/blog/150662
p=$(cd `dirname $0`; pwd)

cd $p
# @see http://blog.sina.com.cn/s/blog_6c9eaa1501011zml.html
nohup php ./once.php >> ./log/job-once.log 2>&1 &
nohup php ./minutely.php >> ./log/job-minutely.log 2>&1 &
nohup php ./hourly.php >> ./log/job-hourly.log 2>&1 &
nohup php ./weekly.php >> ./log/job-weekly.log 2>&1 &
nohup php ./monthly.php >> ./log/job-monthly.log 2>&1 &
nohup php ./yearly.php >> ./log/job-yearly.log 2>&1 &

echo "start all job"