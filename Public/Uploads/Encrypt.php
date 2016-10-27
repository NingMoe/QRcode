<?php
// +----------------------------------------------------------------------
// | Author: lpy 
// +----------------------------------------------------------------------

class Encrypt{
    public $key; // 加密解密的密码
    public $data; // 待加密，解密的数据

    /**
     * 
     */
    public function __construct($data, $key='lpy') {
        /* 基础设置 */
        $this->key  = $key; 
        $this->data   = $data;  
    }

    /**
     * 加密
     */
    public function encrypt() {
        $key = md5($this->key);
        $x = 0;
        $len = strlen($this->data);
        $l = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($this->data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    /**
     * 解密
     */
    public function decrypt() {
        $key = md5($this->key);
        $x = 0;
        $data = base64_decode($this->data);
        $len = strlen($data);
        $l = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }
}
