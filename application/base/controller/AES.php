<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/9/21
 * Time: 15:37
 */

namespace app\base\controller;


class AES
{
    /**
     * AES加密、解密类
     * 用法：
    //key 必须 16 或24位
    $key=1234567890123456;
    $aes = new AES($key);
    $a= $aes->encode("Aes加解密测试 ok!");
    dump($a);
    $aes->decode($a);
    dump($aes->decode($a));
     * </pre>
     */

    private $_bit = MCRYPT_RIJNDAEL_256;
    private $_type = MCRYPT_MODE_CBC;
    private $_key = '_Mikkle_AES_Key_'; // 密钥 必须16位 24位
    private $_use_base64 = true;
    private $_iv_size = null;
    private $_iv = null;
    protected static $instance;
    protected $sn;

    /**
     * @param string $_key 密钥
     * @param int $_bit 默认使用128字节
     * @param string $_type 加密解密方式
     * @param boolean $_use_base64 默认使用base64二次加密
     */
    public function __construct($_key = '', $_bit = 128, $_type = 'ecb', $_use_base64 = true){
        // 加密字节
        if(192 === $_bit){
            $this->_bit = MCRYPT_RIJNDAEL_192;
        }elseif(128 === $_bit){
            $this->_bit = MCRYPT_RIJNDAEL_128;
        }else{
            $this->_bit = MCRYPT_RIJNDAEL_256;
        }
        // 加密方法
        if('cfb' === $_type){
            $this->_type = MCRYPT_MODE_CFB;
        }elseif('cbc' === $_type){
            $this->_type = MCRYPT_MODE_CBC;
        }elseif('nofb' === $_type){
            $this->_type = MCRYPT_MODE_NOFB;
        }elseif('ofb' === $_type){
            $this->_type = MCRYPT_MODE_OFB;
        }elseif('stream' === $_type){
            $this->_type = MCRYPT_MODE_STREAM;
        }else{
            $this->_type = MCRYPT_MODE_ECB;
        }
        // 密钥
        if(!empty($_key)){
            $this->_key = $_key;
        }
        // 是否使用base64
        $this->_use_base64 = $_use_base64;

        $this->_iv_size = mcrypt_get_iv_size($this->_bit, $this->_type);
        $this->_iv = mcrypt_create_iv($this->_iv_size, MCRYPT_RAND);
    }

    static public function instance($_key = '', $_bit = 128, $_type = 'ecb', $_use_base64 = true){
        $is_use_base64 = $_use_base64?1:0;
        $sn = md5("{$_key}{$_bit}{$_type}{$is_use_base64}");
        if(isset(self::$instance[$sn])){
            return self::$instance[$sn];
        }else{
            return self::$instance[$sn] = new static($_key = '', $_bit = 128, $_type = 'ecb', $_use_base64 = true);
        }
    }

    /**
     * 加密
     * @param string $string 待加密字符串
     * @return string
     */
    public function encode($string){
        //
        if(MCRYPT_MODE_ECB === $this->_type){
            $encodeString = mcrypt_encrypt($this->_bit, $this->_key, $string, $this->_type);
        }else{
            $encodeString = mcrypt_encrypt($this->_bit, $this->_key, $string, $this->_type, $this->_iv);
        }
        if($this->_use_base64){
            $encodeString = base64_encode($encodeString);
        }
        return $encodeString;
    }

    /**
     * 解密
     * @param string $string 待解密字符串
     * @return string
     */
    public function decode($string){
        if($this->_use_base64){
            $string = base64_decode($string);
        }
        if(MCRYPT_MODE_ECB === $this->_type){
            $decodeString = mcrypt_decrypt($this->_bit, $this->_key, $string, $this->_type);
        }else{
            $decodeString = mcrypt_decrypt($this->_bit, $this->_key, $string, $this->_type, $this->_iv);
        }
        return $decodeString;
    }




}

