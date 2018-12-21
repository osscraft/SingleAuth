#!/usr/bin
info=$(ps auxf | grep "artisan workerman")
match=$(echo $info | grep '^.*php.*artisan workerman.*$')
dir=$(cd `dirname $0`; pwd)

if [ "$match" = "" ]; then
    exec php $dir/artisan workerman start --d > /dev/null 2>&1 &
fi
