<?php
/**
 * Created by PhpStorm.
 * User: Mikkle
 * Email:776329498@qq.com
 * Date: 2017/2/4
 * Time: 15:48
 */

namespace app\base\controller;
/**
 * 微信支付帮助库
 * ====================================================
 * 命名空间 com\wxpay\+类名+_pub
 * 接口分三种类型：
 * 【请求型接口】--Wxpay_client_
 * 		统一支付接口类--UnifiedOrder
 * 		订单查询接口--OrderQuery
 * 		退款申请接口--Refund
 * 		退款查询接口--RefundQuery
 * 		对账单接口--DownloadBill
 * 		短链接转换接口--ShortUrl
 * 【响应型接口】--Wxpay_server_
 * 		通用通知接口--Notify
 * 		Native支付——请求商家获取商品信息接口--NativeCall
 * 【其他】
 * 		静态链接二维码--NativeLink
 * 		JSAPI支付--JsApi
 * =====================================================
 * 【CommonUtil】常用工具：
 * 		trimString()，设置参数时需要用到的字符处理函数
 * 		createNoncestr()，产生随机字符串，不长于32位
 * 		formatBizQueryParaMap(),格式化参数，签名过程需要用到
 * 		getSign(),生成签名
 * 		arrayToXml(),array转xml
 * 		xmlToArray(),xml转 array
 * 		postXmlCurl(),以post方式提交xml到对应的接口url
 * 		postXmlSSLCurl(),使用证书，以post方式提交xml到对应的接口url
 */

use think\Controller;
use think\Db;
use think\Session;
use com\wxpay\UnifiedOrder_pub as UnifiedOrder;
use com\wxpay\JsApi_pub as JaApi;

class WxPay extends Controller
{
    protected $wx_config=[
        'wechat_appid'=>'wx7b1fdb20e481b2f2',//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
        'wechat_mchid'=>'1417045202',//受理商ID，身份标识 商户号
        'wechat_appkey'=>'4sfd4se51sfd4s14gsd451sf51tf14h5',//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
        'wechat_appsecret'=>'714185779c8538a59cfc6eb549065e4e',//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看

        //证书路径,注意应该填写绝对路径  不用证书也是能支付的
        'sslcert_path'=>'',
        'sslkey_path'=> '',
    ];
    protected $order_table_param=[
        'table'=>'my_orders',      //订单表名称
        'no_field'=>'order_no',     //订单号 字段名字
        'state_field'=> 'is_pay',//订单支付状态值字段名
        'amount_field'=>'amount',//订单金额值字段名
        'pay_ok'=> '1', //订单已支付状态值
        'pay_no'=> '0',  // 订单未支付状态值
        'map' => [     //其他订单是否可以支付的参数值
            ['status' => 1] ,
            [ 'order_state'=>0],
        ],
    ];
    protected $open_id = false;
    protected $notify_url;
    protected $order_no;             //订单号
    protected $order_amount;           //订单详情
    protected $unified_order;        //统一下单订单号


    /**
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     */
    public function _initialize(){
        ini_set('date.timezone','Asia/Shanghai');
        config($this->wx_config);
        //已登陆的设置openid  本人微信登录是在控制器里完成
        if (Session::has('open_id','html5')) $this->open_id=Session::get('open_id','html5');
        $this->notify_url= $this->request->domain().url('Order/wxPayCallBackUrl');
    }

    /**
     * 根据订单号支付订单
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param string $order_no
     * @param array $param
     * @return array
     */
    public function payByOrderNo($order_no='',$order_amount='',$param=[]){

        if(!$order_no)return ['code'=>1003,'msg'=>'订单号丢失'];
        if (!$order_amount){
            $param=$this->order_table_param;
            $order_info=Db::table($param['table'])
                ->field(' '. $param['no_field'].' , '.$param['amount_field'].' ')
                ->where($param['map'][0])
                ->where($param['state_field'],'=','0')
                ->where(['order_no'=>$order_no])
                ->find();
            if (!$order_info) return ['code'=>1010,'msg'=>'订单不存在或者已经是完成状态'];
            $order_amount=$order_info['amount'];
        }
        $this->order_no=$order_no;
        $this->order_amount=$order_amount;
        $unified_order = $this->getUnifiedOrder($order_no,$order_amount);  //获取统一下单
        //dump($unified_order);
        $this->unified_order=$unified_order;
        $jsApiParameters=$this->getParameters($unified_order);
        return ['code'=>1001,'order_no'=>$order_no,'jsApiParameters'=>$jsApiParameters,'amount'=>$order_info['amount'],];
    }

    /**
     * 获取微信统一订单 如果数据库存在 从数据库获取
     * 数据库不存在 获取统一订单并入库
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param string $order_no
     * @param string $order_amount
     * @return bool|mixed
     */
    protected function getUnifiedOrder($order_no = '', $order_amount = '',$open_id='')
    {
        $open_id= $open_id ? : $this->open_id;
        $order_no = $order_no ?: $this->order_no;
        $order_amount = $order_amount ?: $this->order_amount;
        $order_wx = model('base/OrdersWxpay')->where(['order_no' => $order_no,'payer_openid'=>$open_id,'expiration_time'=>['>',time()]])->find();
        if ($order_wx) {
            $unified_order = $order_wx['unified_order'];
            $this->unified_order = $unified_order;
            return $unified_order;
        } else {
            $unified_order = $this->unifiedOrder($order_no, $order_amount);
            if ($unified_order) {
                $save_data = [
                    'order_no' => $order_no,
                    'unified_order' => $unified_order,
                    'amount' => $order_amount,
                    'payer_openid'=>$open_id,
                    'expiration_time'=>time()+7200,
                ];
                model('base/OrdersWxpay')->save($save_data);
                $this->unified_order = $unified_order;
                return $unified_order;
            } else {
                return false;
            }
        }

    }

    /**
     * 统一下单方法
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param array $data
     * @return bool
     */
    protected function unifiedOrder($order_no='',$amount='',$open_id=''){
        $open_id= $open_id ? : $this->open_id;
        $amount=$amount ? : $this->order_amount;
        $order_no = $order_no ? : $this->order_no ;

        $unifiedOrder = new UnifiedOrder();
        $unifiedOrder->setParameter("openid",$open_id); 	 		// openid
        $unifiedOrder->setParameter("body",'商品订单号'. $order_no); 		// 商品描术
        $unifiedOrder->setParameter("out_trade_no",$order_no);  // 商户订单号
        $unifiedOrder->setParameter("total_fee",$amount*100);    // 总金额
        $unifiedOrder->setParameter("notify_url",$this->notify_url);  // 通知地址
        $unifiedOrder->setParameter("trade_type","JSAPI");      // 交易类型
        return $unifiedOrder->getPrepayId();
    }

    /**
     * 获取JsApi$getParameters参数
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param string $unified_order
     * @return string
     */
    protected function getParameters($unified_order=''){
        if(!$unified_order&&$this->unified_order) $unified_order=$this->unified_order;
        $jsApi= new JaApi();
        $jsApi->setPrepayId($unified_order);
        $jsApiParameters = $jsApi->getParameters();
        return $jsApiParameters;
    }






}