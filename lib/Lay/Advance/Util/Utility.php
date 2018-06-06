<?php

namespace Lay\Advance\Util;

use Lay\Advance\Core\Component;

/**
 * 工具类
 *
 * @author Dcux Li
 */
class Utility
{
    /**
     * 服务器系统是不是Windows
     *
     * @var boolean
     */
    private static $_is_windows = false;
    /**
     * 是不是手机
     *
     * @var boolean
     */
    private static $_is_mobile = false;
    /**
     * 判断是否为手机浏览器
     * @return bool
     */
    public static function isMobile()
    {
        static $_is_mobile;
 
        if (isset($_is_mobile)) {
            return $_is_mobile;
        }
 
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            $_is_mobile = false;
        } elseif (stristr(empty($_SERVER['HTTP_VIA']) ? '' : $_SERVER['HTTP_VIA'], "wap")) {// 先检查是否为wap代理，准确度高
            $_is_mobile = true;
        } elseif (strpos(strtoupper(empty($_SERVER['HTTP_ACCEPT']) ? '' : $_SERVER['HTTP_ACCEPT']), "VND.WAP.WML") > 0) {// 检查浏览器是否接受 WML.
            $_is_mobile = true;
        } elseif (preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {//检查USER_AGENT
            $_is_mobile = true;
        } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false) {
            $_is_mobile = true;
        } else {
            $_is_mobile = false;
        }
        return $_is_mobile;
    }
    /**
     * 判断服务器系统是不是Windows
     *
     * @return boolean
     */
    public static function isWindows()
    {
        if (! is_bool(self::$_is_windows)) {
            self::$_is_windows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        }
        return self::$_is_windows;
    }
    /**
     * 判断PHP是不是CLI形式运行
     *
     * @return boolean
     */
    public static function isCli()
    {
        return strtoupper(php_sapi_name()) == 'CLI';
    }
    // header reidrect by post
    public static function redirectPost($url, array $data = array())
    {
        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <script type="text/javascript">
                function closethisasap() {
                    document.forms["redirectpost"].submit();
                }
            </script>
        </head>
        <body onload="closethisasap();">
        <form name="redirectpost" method="post" action="<?php echo $url; ?>">
            <?php
            if (!is_null($data)) {
                foreach ($data as $k => $v) {
                    echo '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
                }
            } ?>
        </form>
        </body>
        </html>
        <?php
        exit;
    }
    /**
     * 获取客户端IP地址
     *
     * @return string
     */
    public static function ip()
    {
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "unknown";
        }
        return $ip;
    }
    /**
     * 获取长整型的客户端IP地址
     *
     * @return string
     */
    public static function ip_long()
    {
        return sprintf("%u", ip2long(Utility::ip()));
    }
    /**
     * 获取客户端浏览器
     *
     * @return string
     */
    public static function browser($ret_arr = false, $sys_browscap = true, $ua = false)
    {
        $ua = empty($ua) ? self::ua() : $ua;
        $browser = '';
        if (empty($sys_browscap)) {
            $browscap = new Browscap();
            $br = $browscap->getBrowser($ua, true);
            $br = self::arrayKeyToLower($br);
        } else {
            $br = get_browser($ua, true);
        }
        if ($br) {
            $browser = $br['browser'] . ' ' . $br['version'];
        } elseif (preg_match('/IE\s(\d+)\.\d/i', $user_agent, $regs)) {
            $browser = $regs[0];
        } elseif (preg_match('/Trident\/(\d+).\d/i', $user_agent, $regs)) {
            $arr = array(
                    'Trident/8.0' => 'IE 12.0',
                    'Trident/7.0' => 'IE 11.0',
                    'Trident/6.0' => 'IE 10.0',
                    'Trident/5.0' => 'IE 9.0',
                    'Trident/4.0' => 'IE 8.0'
            );
            $browser = $arr[$regs[0]];
        } elseif (preg_match('/FireFox\/(\d+).(\d)/i', $user_agent, $regs)) {
            $browser = preg_replace('/\//', ' ', $regs[0]);
        } elseif (preg_match('/Chrome\/(\d+).\d/i', $user_agent, $regs)) {
            $browser = preg_replace('/\//', ' ', $regs[0]);
        } else {
            $ua = ' ' . $_SERVER['HTTP_USER_AGENT'];
            if (strpos($ua, "Maxthon") && strpos($ua, "MSIE")) {
                $browser = "Maxthon(IE)";
            } elseif (strpos($ua, "Maxthon 2.0")) {
                $browser = "Maxthon 2.0";
            } elseif (strpos($ua, "Maxthon")) {
                $browser = "Maxthon";
            } elseif (strpos($ua, "MSIE 9.0")) {
                $browser = "IE 9.0";
            } elseif (strpos($ua, "MSIE 8.0")) {
                $browser = "IE 8.0";
            } elseif (strpos($ua, "MSIE 7.0")) {
                $browser = "IE 7.0";
            } elseif (strpos($ua, "MSIE 6.0")) {
                $browser = "IE 6.0";
            } elseif (strpos($ua, "MSIE 5.5")) {
                $browser = "IE 5.5";
            } elseif (strpos($ua, "MSIE 5.0")) {
                $browser = "IE 5.0";
            } elseif (strpos($ua, "MSIE 4.01")) {
                $browser = "IE 4.01";
            } elseif (strpos($ua, "NetCaptor")) {
                $browser = "NetCaptor";
            } elseif (strpos($ua, "Netscape")) {
                $browser = "Netscape";
            } elseif (strpos($ua, "Lynx")) {
                $browser = "Lynx";
            } elseif (strpos($ua, "Epiphany")) {
                $browser = "Epiphany";
            } elseif (strpos($ua, "Opera")) {
                $browser = "Opera";
            } elseif (strpos($ua, "Konqueror")) {
                $browser = "Konqueror";
            } elseif (strpos($ua, "Firefox")) {
                $browser = "Firefox";
            } elseif (strpos($ua, "Chrome")) {
                $browser = "Chrome";
            } elseif (strpos($ua, "Safari")) {
                $browser = "Safari";
            } elseif (strpos($ua, "Mozilla/5.0")) {
                $browser = "Mozilla";
            } else {
                $browser = "unknown";
            }
        }
        if (empty($ret_arr)) {
            return $browser;
        } else {
            return explode(' ', $browser);
        }
    }
    /**
     * 获取客户端UA
     *
     * @return string
     */
    public static function ua()
    {
        return empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
    }
    /**
     * 获取客户端操作系统
     *
     * @return string
     */
    public static function os($sys_browscap = true, $ua = false)
    {
        $ua = empty($ua) ? self::ua() : $ua;
        $browser = '';
        if (empty($sys_browscap)) {
            $browscap = new Browscap();
            $br = $browscap->getBrowser($ua, true);
            $br = self::arrayKeyToLower($br);
        } else {
            $br = get_browser($ua, true);
        }
        if ($br) {
            if ($br['platform'] != '' && $br['platform'] != 'unknown') {
                return empty($br['platform_bits']) ? $br['platform'] : ($br['platform'] . ' ' . $br['platform_bits']);
            }
        }
        if (strpos($ua, "Win") && strpos($ua, "NT 5.1")) {
            $os = "Windows XP (SP2)";
        } elseif (strpos($ua, "Win") && strpos($ua, "NT 5.2") && strpos($ua, "WOW64")) {
            $os = "Windows XP 64-bit Edition";
        } elseif (strpos($ua, "Win") && strpos($ua, "NT 5.2")) {
            $os = "Windows 2003";
        } elseif (strpos($ua, "Win") && strpos($ua, "NT 6.0")) {
            $os = "Windows Vista";
        } elseif (strpos($ua, "Win") && strpos($ua, "NT 6.1")) {
            $os = "Win7";
        } elseif (strpos($ua, "Win") && strpos($ua, "NT 5.0")) {
            $os = "Windows 2000";
        } elseif (strpos($ua, "Win") && strpos($ua, "4.9")) {
            $os = "Windows ME";
        } elseif (strpos($ua, "Win") && strpos($ua, "NT 4")) {
            $os = "Windows NT 4.0";
        } elseif (strpos($ua, "Win") && strpos($ua, "98")) {
            $os = "Windows 98";
        } elseif (strpos($ua, "Win") && strpos($ua, "95")) {
            $os = "Windows 95";
        } elseif (strpos($ua, "MacOSX")) {
            $os = "Mac";
        } elseif (strpos($ua, "Linux")) {
            if (strpos($ua, "Android")) {
                $os = "Android";
            } else {
                $os = "Linux";
            }
        } elseif (strpos($ua, "Unix")) {
            $os = "Unix";
        } elseif (strpos($ua, "FreeBSD")) {
            $os = "FreeBSD";
        } elseif (strpos($ua, "SunOS")) {
            $os = "SunOS";
        } elseif (strpos($ua, "BeOS")) {
            $os = "BeOS";
        } elseif (strpos($ua, "OS/2")) {
            $os = "OS/2";
        } elseif (strpos($ua, "PC")) {
            $os = "Macintosh";
        } elseif (strpos($ua, "AIX")) {
            $os = "AIX";
        } elseif (strpos($ua, "IBM OS/2")) {
            $os = "IBM OS/2";
        } elseif (strpos($ua, "BSD")) {
            $os = "BSD";
        } elseif (strpos($ua, "NetBSD")) {
            $os = "NetBSD";
        } else {
            $os = "unknown";
        }
        return $os;
    }
    public static function ipton($ip)
    {
        $ip_arr = explode('.', $ip); // 分隔ip段
        foreach ($ip_arr as $value) {
            $iphex = dechex($value); // 将每段ip转换成16进制
            if (strlen($iphex) < 2) { // 255的16进制表示是ff，所以每段ip的16进制长度不会超过2
                $iphex = '0' . $iphex; // 如果转换后的16进制数长度小于2，在其前面加一个0
                                       // 没有长度为2，且第一位是0的16进制表示，这是为了在将数字转换成ip时，好处理
            }
            $ipstr .= $iphex; // 将四段IP的16进制数连接起来，得到一个16进制字符串，长度为8
        }
        return hexdec($ipstr); // 将16进制字符串转换成10进制，得到ip的数字表示
    }
    public static function ntoip($n)
    {
        $iphex = dechex($n); // 将10进制数字转换成16进制
        $len = strlen($iphex); // 得到16进制字符串的长度
        if (strlen($iphex) < 8) {
            $iphex = '0' . $iphex; // 如果长度小于8，在最前面加0
            $len = strlen($iphex); // 重新得到16进制字符串的长度
        }
        // 这是因为ipton函数得到的16进制字符串，如果第一位为0，在转换成数字后，是不会显示的
        // 所以，如果长度小于8，肯定要把第一位的0加上去
        // 为什么一定是第一位的0呢，因为在ipton函数中，后面各段加的'0'都在中间，转换成数字后，不会消失
        for ($i = 0, $j = 0; $j < $len; $i = $i + 1, $j = $j + 2) { // 循环截取16进制字符串，每次截取2个长度
            $ippart = substr($iphex, $j, 2); // 得到每段IP所对应的16进制数
            $fipart = substr($ippart, 0, 1); // 截取16进制数的第一位
            if ($fipart == '0') { // 如果第一位为0，说明原数只有1位
                $ippart = substr($ippart, 1, 1); // 将0截取掉
            }
            $ip[] = hexdec($ippart); // 将每段16进制数转换成对应的10进制数，即IP各段的值
        }
        return implode('.', $ip); // 连接各段，返回原IP值
    }
    /**
     * 判断是不是绝对路径
     *
     * @param string $path
     * @return boolean
     */
    public static function isAbsolutePath($path)
    {
        return false;
    }
    /**
     * 转变为纯粹的数组
     *
     * @param array $arr
     * @return array
     */
    public static function toPureArray($arr)
    {
        if (is_array($arr)) {
            return array_values($arr);
        } elseif (is_object($arr)) {
            if (method_exists($arr, 'toArray')) {
                return self::toPureArray($arr->toArray());
            } else {
                return self::toPureArray(get_object_vars($arr));
            }
        } elseif (! is_resource($arr)) {
            return ( array ) $arr;
        } else {
            return $arr;
        }
    }
    /**
     * 判断是不是纯粹的数组
     *
     * @param array $arr
     * @return boolean
     */
    public static function isPureArray($arr)
    {
        $bool = true;
        if (is_array($arr)) {
            foreach ($arr as $i => $a) {
                if (is_string($i) || ! is_int($i)) {
                    $bool = false;
                    break;
                }
            }
        } else {
            $bool = false;
        }
        return $bool;
    }
    public static function emptyObject($obj)
    {
        if (is_object($obj)) {
            $arr = get_object_vars($obj);
            if ($obj instanceof Component) {
                $arr = array_merge($arr, $obj->toArray());
            }
            return empty($arr) ? true : false;
        } else {
            return false;
        }
    }
    /**
     * 计算有没有下页
     *
     * @param int $total
     * @param int $offset
     * @param int $num
     * @return boolean
     */
    public static function hasNext($total, $offset = -1, $num = -1)
    {
        if ($offset == - 1 || $num == - 1) {
            return false;
        } elseif ($total > $offset + $num) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 获取代微秒数的当前时间
     *
     * @param string $format
     * @return string number
     */
    public static function microtime($format = false)
    {
        if ($format) {
            return date($format) . substr(( string ) microtime(), 1, 8);
        } else {
            return time() + substr(( string ) microtime(), 1, 8);
        }
    }
    public static function arrayKeyToLower($arr)
    {
        $new = array();
        if (is_array($arr) && !empty($arr)) {
            foreach ($arr as $key => $value) {
                $new[strtolower($key)] = $value;
            }
        }
        return $new;
    }
    public static function arrayKeyToUpper($arr)
    {
        $new = array();
        if (is_array($arr) && !empty($arr)) {
            foreach ($arr as $key => $value) {
                $new[strtoupper($key)] = $value;
            }
        }
        return $new;
    }
    /**
     * php array to php content
     *
     * @param array $arr
     *            convert array
     * @param boolean $encrypt
     *            if encrypt
     * @return string
     */
    public static function array2PHPContent($arr, $encrypt = false)
    {
        $arr = empty($arr) ? array() : $arr;
        if ($encrypt) {
            $r = '';
            $r .= self::array2String($arr);
        } else {
            $r = "<?php";
            $r .= "\r\nreturn ";
            self::a2s($r, $arr);
            $r .= ";\r\n// PHP END\r\n";
        }
        return $r;
    }
    /**
     * convert a multidimensional array to url save and encoded string
     *
     * 在Array和String类型之间转换，转换为字符串的数组可以直接在URL上传递
     *
     * @param array $Array
     *            convert array
     * @return string
     */
    public static function array2String($Array)
    {
        $Return = '';
        $NullValue = "^^^";
        foreach ($Array as $Key => $Value) {
            if (is_array($Value)) {
                $ReturnValue = '^^array^' . self::array2String($Value);
            } else {
                $ReturnValue = (strlen($Value) > 0) ? $Value : $NullValue;
            }
            $Return .= urlencode(base64_encode($Key)) . '|' . urlencode(base64_encode($ReturnValue)) . '||';
        }
        return urlencode(substr($Return, 0, - 2));
    }
    /**
     * convert a string generated with Array2String() back to the original (multidimensional) array
     *
     * @param string $String
     *            convert string
     * @return array
     */
    public static function string2Array($String)
    {
        $Return = array();
        $String = urldecode($String);
        $TempArray = explode('||', $String);
        $NullValue = urlencode(base64_encode("^^^"));
        foreach ($TempArray as $TempValue) {
            list($Key, $Value) = explode('|', $TempValue);
            $DecodedKey = base64_decode(urldecode($Key));
            if ($Value != $NullValue) {
                $ReturnValue = base64_decode(urldecode($Value));
                if (substr($ReturnValue, 0, 8) == '^^array^') {
                    $ReturnValue = self::string2Array(substr($ReturnValue, 8));
                }
                $Return[$DecodedKey] = $ReturnValue;
            } else {
                $Return[$DecodedKey] = null;
            }
        }
        return $Return;
    }
    /**
     * array $a to string $r
     *
     * @param string $r
     *            output string pointer address
     * @param array $a
     *            input array pointer address
     * @param array $l
     *            左则制表字符串
     * @param array $b
     *            左则制表字符串单元
     * @return void
     */
    public static function a2s(&$r, array &$a = array(), $l = "", $b = "    ")
    {
        $f = false;
        $h = false;
        $i = 0;
        $r .= 'array(' . "\r\n";
        foreach ($a as $k => $v) {
            if (! $h) {
                $h = array(
                        'k' => $k,
                        'v' => $v
                );
            }
            if ($f) {
                $r .= ',' . "\r\n";
            }
            $j = ! is_string($k) && is_numeric($k) && $h['k'] === 0;
            self::o2s($r, $k, $v, $i, $j, $l, $b);
            $f = true;
            if ($j && $k >= $i) {
                $i = $k + 1;
            }
        }
        $r .= "\r\n$l" . ')';
    }
    /**
     * to string $r
     *
     * @param string $r
     *            output string pointer address
     * @param string $k
     *            键名
     * @param string $v
     *            键值
     * @param string $i
     * @param string $j
     * @param array $l
     *            左则制表字符串
     * @param array $b
     *            左则制表字符串单元
     * @return void
     */
    private static function o2s(&$r, $k, $v, $i, $j, $l, $b)
    {
        $isW = self::isWindows();
        if ($k !== $i) {
            if ($j) {
                $r .= "$l$b$k => ";
            } else {
                $r .= "$l$b'$k' => ";
            }
        } else {
            $r .= "$l$b";
        }
        if (is_array($v)) {
            self::a2s($r, $v, $l . $b);
        } elseif (is_numeric($v)) {
            $r .= "" . $v;
        } else {
            $r .= "'" . str_replace("'", "\'", $v) . "'";
        }
    }
    
    /**
     * xml format string to php array
     *
     * @param string $xml
     *            xml format string
     * @param bool $simple
     *            if use simplexml,default false
     * @return array bool
     */
    public static function xml2Array($xml, $simple = false)
    {
        if (! is_string($xml)) {
            return false;
        }
        if ($simple) {
            $xml = @simplexml_load_string($xml);
        } else {
            $xml = @json_decode(json_encode(( array ) simplexml_load_string($xml)), 1);
        }
        return $xml;
    }
    /**
     * php array to xml format string
     *
     * @param array $value
     *            convert array
     * @param string $encoding
     *            xml encoding
     * @param string $root
     *            xml root tag
     * @param string $nkey
     *            纯数组转换时使用的标签名
     * @return string
     */
    public static function array2XML($value, $encoding = 'utf-8', $root = 'root', $nkey = '')
    {
        if (! is_array($value) && ! is_string($value) && ! is_bool($value) && ! is_numeric($value) && ! is_object($value)) {
            return false;
        }
        $nkey = preg_match('/^[A-Za-z_][A-Za-z0-9\-_]{0,}$/', $nkey) ? $nkey : '';
        return simplexml_load_string('<?xml version="1.0" encoding="' . $encoding . '"?>' . self::x2str($value, $root, $nkey))->asXml();
    }
    /**
     * object or array to xml format string
     *
     * @param object $xml
     *            array or object
     * @param string $key
     *            tag name
     * @param string $nkey
     *            纯数组转换时使用的标签名
     * @return string
     */
    private static function x2str($xml, $key, $nkey)
    {
        if (! is_array($xml) && ! is_object($xml)) {
            return "<$key>" . htmlspecialchars($xml) . "</$key>";
        }
        
        $xml_str = '';
        foreach ($xml as $k => $v) {
            if (is_numeric($k)) {
                $k = $nkey ? $key . '-' . $nkey : $key . '-item';
            }
            $xml_str .= self::x2str($v, $k, $nkey);
        }
        return "<$key>$xml_str</$key>";
    }
    /**
     * php array to csv format string
     *
     * @param array $input
     *            convert array
     * @param string $delimiter
     * @return string
     */
    public static function array2CSV($input = array(), $delimiter = ',')
    {
        /**
         * open raw memory as file, no need for temp files, be careful not to run out of memory thought
         */
        $handler = fopen('php://temp', 'w');
        /**
         * loop through array
         */
        foreach ($input as $line) {
            /**
             * default php csv handler *
             */
            fputcsv($handler, array_map('Lay\Advance\Util\Utility::mixed2String', ( array ) $line), $delimiter);
        }
        /**
         * rewrind the "file" with the csv lines *
         */
        fseek($handler, 0);
        $output = stream_get_contents($handler);
        fclose($handler);
        return $output;
    }
    /**
     * php var to string
     *
     * @param mixed $mixed
     * @return string
     */
    public static function mixed2String($mixed)
    {
        if (is_scalar($mixed)) {
            return strval($mixed);
        } elseif (is_array($mixed)) {
            return json_encode($mixed);
        } elseif (is_object($mixed)) {
            if (method_exists($mixed, 'toArray')) {
                return self::mixed2String($mixed->toArray());
            } else {
                return self::mixed2String(get_object_vars($mixed));
            }
        }
    }
    /**
     * 递归创建文件夹目录
     *
     * @param string $dir
     * @return boolean
     */
    public static function createFolders($dir, $mode = 0777)
    {
        return is_dir($dir) || (self::createFolders(dirname($dir)) && mkdir($dir, $mode));
    }
    /**
     * 获取文件类型后缀
     */
    public static function getExtension($file)
    {
        $info = pathinfo($file);
        return empty($info['extension']) ? '' : $info['extension'];
    }
    /**
     * 删除文件夹及文件夹内的文件
     *
     * @param string $dir
     * @return boolean
     */
    public static function rmdir($dir, $involve = true)
    {
        $dir = realpath($dir);
        if (is_dir($dir) && $handle = opendir($dir)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir("$dir/$item")) {
                        self::rmdir("$dir/$item");
                    } else {
                        unlink("$dir/$item");
                    }
                }
            }
            closedir($handle);
            $involve && rmdir($dir);
        } else {
            return false;
        }
        return true;
    }
    /**
     * 压缩文件夹或文件
     *
     * @param string $dir
     * @param string $dest
     * @return boolean
     */
    public static function zip($dir, $dest, $flags = ZipArchive::OVERWRITE)
    {
        if (class_exists('ZipArchive') && (is_dir($dir) || is_file($dir))) {
            $zip = new ZipArchive();
            $res = $zip->open($dest, $flags);
            if ($res && is_dir($dir)) {
                self::zipdir($dir, '', $zip);
            } elseif ($res && is_file($dir)) {
                $zip->addFile($dir);
            } else {
                return false;
            }
            $zip->close();
        } else {
            return false;
        }
        return true;
    }
    /**
     * 压缩文件夹至压缩包里的指定目录下
     *
     * @param string $dir
     * @param string $pre
     *            指定目录
     * @param ZipArchive $zip
     * @return boolean
     */
    private static function zipdir($dir, $pre, $zip)
    {
        $ret = true;
        $dir = realpath($dir) . "/";
        $basename = basename($dir);
        $predir = $pre . $basename . "/";
        // 添加目录
        $ret = $zip->addEmptyDir($predir);
        // 添加文件
        $handler = opendir($dir); // 打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") { // 文件夹文件名字为'.'和'..'，不要对他们进行操作
                if (is_dir($dir . $filename)) { // 如果读取的某个对象是文件夹，则递归
                    $ret = $ret && self::zipdir($dir . $filename . "/", $predir, $zip);
                } else { // 将文件加入zip对象
                    $ret = $ret && $zip->addFile($dir . $filename, $predir . $filename);
                }
            }
            if (empty($ret)) {
                break;
            }
        }
        @closedir($dir);
        return $ret;
    }
    /**
     * 是关联数组还是普通数组
     *
     * @param array $array
     * @return boolean
     */
    public static function isAssocArray($array)
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }
    /**
     * 根据参数构建url字符串
     * url('/', array('a' => 1, 'b' => 2));
     * /?a=1&b=2
     * url('/?c=3', array('a' => 1, 'b' => 2, 'c' => false));
     * /?a=1&b=2
     * url('/', array('a' => 1, 'b' => 2, 'c' => 3), array('c' => 4));
     * /?a=1&b=2&c=4
     *
     * @param string $url
     * @param array $args
     * @return string
     */
    public static function url($url, $args = null)
    {
        $url = parse_url($url);
        if (! isset($url['path']) || ! $url['path']) {
            $url['path'] = '';
        }
        $query = array();
        if (isset($url['query'])) {
            parse_str($url['query'], $query);
        }
        if ($args !== null) {
            foreach (array_slice(func_get_args(), 1) as $args) {
                if (! is_array($args)) {
                    continue;
                }
                foreach ($args as $k => $v) {
                    if ($v === false) {
                        unset($query[$k]);
                    } else {
                        $query[$k] = $v;
                    }
                }
            }
        }
        $result = '';
        if (isset($url['scheme'])) {
            $result .= $url['scheme'] . '://';
        }
        if (isset($url['user'])) {
            $result .= $url['user'];
            if (isset($url['pass'])) {
                $result .= ':' . $url['pass'];
            }
            $result .= '@';
        }
        if (isset($url['host'])) {
            $result .= $url['host'];
        }
        if (isset($url['port'])) {
            $result .= ':' . $url['port'];
        }
        $result .= $url['path'];
        if ($query) {
            $result .= '?' . http_build_query($query);
        }
        if (isset($url['fragment'])) {
            $result .= '#' . $url['fragment'];
        }
        return $result;
    }
    public static function split_url($url, $decode=true)
    {
        $xunressub     = 'a-zA-Z\d\-._~\!$&\'()*+,;=';
        $xpchar        = $xunressub . ':@%';

        $xscheme       = '([a-zA-Z][a-zA-Z\d+-.]*)';

        $xuserinfo     = '((['  . $xunressub . '%]*)' .
                         '(:([' . $xunressub . ':%]*))?)';

        $xipv4         = '(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})';

        $xipv6         = '(\[([a-fA-F\d.:]+)\])';

        $xhost_name    = '([a-zA-Z\d-.%]+)';

        $xhost         = '(' . $xhost_name . '|' . $xipv4 . '|' . $xipv6 . ')';
        $xport         = '(\d*)';
        $xauthority    = '((' . $xuserinfo . '@)?' . $xhost .
                         '?(:' . $xport . ')?)';

        $xslash_seg    = '(/[' . $xpchar . ']*)';
        $xpath_authabs = '((//' . $xauthority . ')((/[' . $xpchar . ']*)*))';
        $xpath_rel     = '([' . $xpchar . ']+' . $xslash_seg . '*)';
        $xpath_abs     = '(/(' . $xpath_rel . ')?)';
        $xapath        = '(' . $xpath_authabs . '|' . $xpath_abs .
                         '|' . $xpath_rel . ')';

        $xqueryfrag    = '([' . $xpchar . '/?' . ']*)';

        $xurl          = '^(' . $xscheme . ':)?' .  $xapath . '?' .
                         '(\?' . $xqueryfrag . ')?(#' . $xqueryfrag . ')?$';
     
     
        // Split the URL into components.
        if (!preg_match('!' . $xurl . '!', $url, $m)) {
            return false;
        }
     
        if (!empty($m[2])) {
            $parts['scheme']  = strtolower($m[2]);
        }
     
        if (!empty($m[7])) {
            if (isset($m[9])) {
                $parts['user']    = $m[9];
            } else {
                $parts['user']    = '';
            }
        }
        if (!empty($m[10])) {
            $parts['pass']    = $m[11];
        }
     
        if (!empty($m[13])) {
            $h=$parts['host'] = $m[13];
        } elseif (!empty($m[14])) {
            $parts['host']    = $m[14];
        } elseif (!empty($m[16])) {
            $parts['host']    = $m[16];
        } elseif (!empty($m[5])) {
            $parts['host']    = '';
        }
        if (!empty($m[17])) {
            $parts['port']    = $m[18];
        }
     
        if (!empty($m[19])) {
            $parts['path']    = $m[19];
        } elseif (!empty($m[21])) {
            $parts['path']    = $m[21];
        } elseif (!empty($m[25])) {
            $parts['path']    = $m[25];
        }
     
        if (!empty($m[27])) {
            $parts['query']   = $m[28];
        }
        if (!empty($m[29])) {
            $parts['fragment']= $m[30];
        }
     
        if (!$decode) {
            return $parts;
        }
        if (!empty($parts['user'])) {
            $parts['user']     = rawurldecode($parts['user']);
        }
        if (!empty($parts['pass'])) {
            $parts['pass']     = rawurldecode($parts['pass']);
        }
        if (!empty($parts['path'])) {
            $parts['path']     = rawurldecode($parts['path']);
        }
        if (isset($h)) {
            $parts['host']     = rawurldecode($parts['host']);
        }
        if (!empty($parts['query'])) {
            $parts['query']    = rawurldecode($parts['query']);
        }
        if (!empty($parts['fragment'])) {
            $parts['fragment'] = rawurldecode($parts['fragment']);
        }
        return $parts;
    }
    public static function join_url($parts, $encode=true)
    {
        if ($encode) {
            if (isset($parts['user'])) {
                $parts['user']     = rawurlencode($parts['user']);
            }
            if (isset($parts['pass'])) {
                $parts['pass']     = rawurlencode($parts['pass']);
            }
            if (isset($parts['host']) &&
                !preg_match('!^(\[[\da-f.:]+\]])|([\da-f.:]+)$!ui', $parts['host'])) {
                $parts['host']     = rawurlencode($parts['host']);
            }
            if (!empty($parts['path'])) {
                $parts['path']     = preg_replace(
                    '!%2F!ui',
                    '/',
                    rawurlencode($parts['path'])
                );
            }
            if (isset($parts['query'])) {
                $parts['query']    = rawurlencode($parts['query']);
            }
            if (isset($parts['fragment'])) {
                $parts['fragment'] = rawurlencode($parts['fragment']);
            }
        }
     
        $url = '';
        if (!empty($parts['scheme'])) {
            $url .= $parts['scheme'] . ':';
        }
        if (isset($parts['host'])) {
            $url .= '//';
            if (isset($parts['user'])) {
                $url .= $parts['user'];
                if (isset($parts['pass'])) {
                    $url .= ':' . $parts['pass'];
                }
                $url .= '@';
            }
            if (preg_match('!^[\da-f]*:[\da-f.:]+$!ui', $parts['host'])) {
                $url .= '[' . $parts['host'] . ']';
            } // IPv6
            else {
                $url .= $parts['host'];
            }             // IPv4 or name
            if (isset($parts['port'])) {
                $url .= ':' . $parts['port'];
            }
            if (!empty($parts['path']) && $parts['path'][0] != '/') {
                $url .= '/';
            }
        }
        if (!empty($parts['path'])) {
            $url .= $parts['path'];
        }
        if (isset($parts['query'])) {
            $url .= '?' . $parts['query'];
        }
        if (isset($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }
        return $url;
    }
    /**
     * 2到62，任意进制转换
     *
     * @param string $number:
     *            转换的数字
     * @param string $from:
     *            本来的进制
     * @param string $to:
     *            转换到进制
     * @param string $use_bcmath:
     *            是否使用bcmath模块处理超大数字
     */
    public static function base_convert($number, $from, $to, $use_bcmath = null)
    {
        $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $loaded = extension_loaded('bcmath');
        if ($use_bcmath && ! $loaded) {
            throw new \RuntimeException('Require bcmath extension!');
        }
        $use_bcmath = $loaded;
        // 任意进制转换为十进制
        $any2dec = function ($number, $from) use ($base, $use_bcmath) {
            if ($from === 10) {
                return $number;
            }
            $base = substr($base, 0, $from);
            $dec = 0;
            $number = ( string ) $number;
            for ($i = 0, $len = strlen($number); $i < $len; $i ++) {
                $c = substr($number, $i, 1);
                $n = strpos($base, $c);
                if ($n === false) { // 出现了当前进制不支持的数字
                    trigger_error('Unexpected base character: ' . $c, E_USER_ERROR);
                }
                $pos = $len - $i - 1;
                if ($use_bcmath) {
                    $dec = bcadd($dec, bcmul($n, bcpow($from, $pos)));
                } else {
                    $dec += $n * pow($from, $pos);
                }
            }
            return $dec;
        };
        // 十进制转换为任意进制
        $dec2any = function ($number, $to) use ($base, $use_bcmath) {
            if ($to === 10) {
                return $number;
            }
            $base = substr($base, 0, $to);
            $any = '';
            while ($number >= $to) {
                if ($use_bcmath) {
                    list($number, $c) = array(
                            bcdiv($number, $to),
                            bcmod($number, $to)
                    );
                } else {
                    list($number, $c) = array(
                            ( int ) ($number / $to),
                            $number % $to
                    );
                }
                $any = substr($base, $c, 1) . $any;
            }
            $any = substr($base, $number, 1) . $any;
            return $any;
        };
        // //////////////////////////////////////////////////////////////////////////////
        $from = ( int ) $from;
        $to = ( int ) $to;
        $min_base = 2;
        $max_base = strlen($base);
        if ($from < $min_base || $from > $max_base || $to < $min_base || $to > $max_base) {
            trigger_error("Only support base between {$min_base} and {$max_base}", E_USER_ERROR);
        }
        if ($from === $to) {
            return $number;
        }
        // 转换为10进制
        $dec = ($from === 10) ? $number : $any2dec($number, $from);
        return $dec2any($dec, $to);
    }
    
    /*
     * *************************************************************************
     * pinyin.php
     * Desc. : 拼音转换
     * //默认是gb编码
     * echo pinyin('第二个参数随意设置',2); //第二个参数随意设置即为utf8
     * *************************************************************************
     */
    public static function pinyin($_String, $_Code = 'gb2312', $isInitial = false)
    {
        $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" . "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" . "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" . "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" . "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" . "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" . "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" . "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" . "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" . "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" . "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" . "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" . "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" . "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" . "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" . "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
        $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" . "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" . "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" . "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" . "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" . "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" . "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" . "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" . "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" . "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" . "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" . "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" . "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" . "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" . "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" . "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" . "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" . "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" . "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" . "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" . "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" . "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" . "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" . "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" . "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" . "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" . "|-10270|-10262|-10260|-10256|-10254";
        $_TDataKey = explode('|', $_DataKey);
        $_TDataValue = explode('|', $_DataValue);
        $_Data = (PHP_VERSION >= '5.0') ? array_combine($_TDataKey, $_TDataValue) : self::_array_combine($_TDataKey, $_TDataValue);
        arsort($_Data);
        reset($_Data);
        if ($_Code != 'gb2312') {
            $_String = self::_u2_utf8_gb($_String);
        }
        $_Res = '';
        for ($i = 0; $i < strlen($_String); $i ++) {
            $_P = ord(substr($_String, $i, 1));
            if ($_P > 160) {
                $_Q = ord(substr($_String, ++ $i, 1));
                $_P = $_P * 256 + $_Q - 65536;
            }
            $_Res .= self::_pinyin($_P, $_Data, $isInitial);
        }
        return preg_replace("/[^a-z0-9]*/", '', $_Res);
    }
    private static function _pinyin($_Num, $_Data, $isInitial)
    {
        if ($_Num > 0 && $_Num < 160) {
            return chr($_Num);
        } elseif ($_Num < - 20319 || $_Num > - 10247) {
            return '';
        } else {
            foreach ($_Data as $k => $v) {
                if ($v) {
                    break;
                }
            }
            if ($isInitial) {
                $k = substr($k, 0, 1);
            } // 是否只显示首个拼音字母
            return $k;
        }
    }
    private static function _u2_utf8_gb($_C)
    {
        $_String = '';
        if ($_C < 0x80) {
            $_String .= $_C;
        } elseif ($_C < 0x800) {
            $_String .= chr(0xC0 | $_C >> 6);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x10000) {
            $_String .= chr(0xE0 | $_C >> 12);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x200000) {
            $_String .= chr(0xF0 | $_C >> 18);
            $_String .= chr(0x80 | $_C >> 12 & 0x3F);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        return iconv('UTF-8', 'GB2312', $_String);
    }
    private static function _array_combine($_Arr1, $_Arr2)
    {
        for ($i = 0; $i < count($_Arr1); $i ++) {
            $_Res[$_Arr1[$i]] = $_Arr2[$i];
        }
        return $_Res;
    }
}
