<?php
$time = date('Y-m-d H:i:s');
$cmddir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cmd';
$cmddir = realpath($cmddir);
$jobdir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'job';
$jobdir = realpath($jobdir);
$phpdir = __DIR__ . DIRECTORY_SEPARATOR . 'monthly';
$phpdir = realpath($phpdir);
$logdir = __DIR__ . DIRECTORY_SEPARATOR . 'log';
$logdir = realpath($logdir);
$logfile = "$logdir/job-monthly-result.log";
echo "[$time] SCAN $phpdir\n";
@chmod($logfile, 0777);
if (is_dir($phpdir) && $handle = opendir($phpdir)) {
    while ( false !== ($item = readdir($handle)) ) {
        if ($item != "." && $item != "..") {
        	$file = realpath($phpdir . DIRECTORY_SEPARATOR . $item);
            $fileinfo = pathinfo($file);
            extract($fileinfo);// && !empty($dirname) && !empty($basename)
            if (is_file($file) && !empty($extension) && $extension == 'php') {
            	//$cmd = "cd $dirname; nohup php $basename >> $logfile 2>&1 &";
            	$cmd = "nohup php $file >> $logfile 2>&1 &";
            	exec($cmd);
				echo "[$time] $cmd\n";
            }
        }
    }
    closedir($handle);
}
echo "[$time] SCAN done\n";
// PHP END