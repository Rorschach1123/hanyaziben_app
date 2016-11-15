<?php
namespace Admin\Controller;
use Think\Controller;
class recharge {

    private $appkey;

    private $openid;

    private $telCheckUrl = 'http://op.juhe.cn/ofpay/mobile/telcheck';

    private $telQueryUrl = 'http://op.juhe.cn/ofpay/mobile/telquery';

    private $submitUrl = 'http://op.juhe.cn/ofpay/mobile/onlineorder';

    private $staUrl = 'http://op.juhe.cn/ofpay/mobile/ordersta';

    public function __construct($appkey,$openid){
        $this->appkey = $appkey;
        $this->openid = $openid;
    }

    /**
     * 根据手机号码及面额查询是否支持充值
     * @param  string $mobile   [手机号码]
     * @param  int $pervalue [充值金额]
     * @return  boolean
     */
    public function telcheck($mobile,$pervalue){
        $params = 'key='.$this->appkey.'&phoneno='.$mobile.'&cardnum='.$pervalue;
        $content = $this->juhecurl($this->telCheckUrl,$params);
        $result = $this->_returnArray($content);
        if($result['error_code'] == '0'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 根据手机号码和面额获取商品信息
     * @param  string $mobile   [手机号码]
     * @param  int $pervalue [充值金额]
     * @return  array
     */
    public function telquery($mobile,$pervalue){
        $params = 'key='.$this->appkey.'&phoneno='.$mobile.'&cardnum='.$pervalue;
        $content = $this->juhecurl($this->telQueryUrl,$params);
        return $this->_returnArray($content);
    }

    /**
     * 提交话费充值
     * @param  [string] $mobile   [手机号码]
     * @param  [int] $pervalue [充值面额]
     * @param  [string] $orderid  [自定义单号]
     * @return  [array]
     */
    public function telcz($mobile,$pervalue,$orderid){
        $sign = md5($this->openid.$this->appkey.$mobile.$pervalue.$orderid);//校验值计算
        $params = array(
            'key' => $this->appkey,
            'phoneno'   => $mobile,
            'cardnum'   => $pervalue,
            'orderid'   => $orderid,
            'sign' => $sign
        );
        $content = $this->juhecurl($this->submitUrl,$params,1);
        return $this->_returnArray($content);
    }

    /**
     * 查询订单的充值状态
     * @param  [string] $orderid [自定义单号]
     * @return  [array]
     */
    public function sta($orderid){
        $params = 'key='.$this->appkey.'&orderid='.$orderid;
        $content = $this->juhecurl($this->staUrl,$params);
        return $this->_returnArray($content);
    }

    /**
     * 将JSON内容转为数据，并返回
     * @param string $content [内容]
     * @return array
     */
    public function _returnArray($content){
        return json_decode($content,true);
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}
class LuckController extends Controller{
    public function index(){
        $this -> display();
    }
    // 流量充值接口
    public function liuliang(){
        header('Content-type:text/html;charset=utf-8');
        
        //获取手机号码
        $tel = I('post.tel');
        
        //配置您申请的appkey
        $appkey = '86e9001e5ca9c85253f90c27266b3f0b';
        $openid = 'JH96d2dcdca3228aba411971f91f3aac22';
        
        $recharge = new recharge($appkey,$openid);
        //************1.全部流量套餐列表************
        $url = "http://v.juhe.cn/flow/list";
        $params = array(
              "key" => $appkey,//应用APPKEY(应用详细页查询)
        );
        $paramstring = http_build_query($params);
        $content = $recharge->juhecurl($url,$paramstring);
        // $result = json_decode($content,true);
        // if($result){
        //     if($result['error_code']=='0'){
        //         echo '<pre>';
        //         print_r($result);
        //     }else{
        //         echo $result['error_code'].":".$result['reason'];
        //     }
        // }else{
        //     echo "请求失败";
        // }
        //**************************************************
         
         
         
         
        //************2.检测号码支持的流量套餐************
        $url = "http://v.juhe.cn/flow/telcheck";
        $params = array(
              "phone" => $tel,//要查询的手机号码
              "key" => $appkey,//应用APPKEY(应用详细页查询)
        );
        $paramstring = http_build_query($params);
        $content = $recharge->juhecurl($url,$paramstring);
        // $result = json_decode($content,true);
        // if($result){
        //     if($result['error_code']=='0'){
        //         echo '<pre>';
        //         print_r($result);
        //     }else{
        //         echo $result['error_code'].":".$result['reason'];
        //     }
        // }else{
        //     echo "请求失败";
        // }
        //**************************************************
         
         
         $time = time();
         $orderid = 'hy'.$time;
         
        //************3.提交流量充值************
        $url = "http://v.juhe.cn/flow/recharge";
        
        $msg = $openid.$appkey.$tel.'2'.$orderid;
        
        $sign = md5($msg);
        
        $params = array(
              "phone" => $tel,//需要充值流量的手机号码
              "pid" => "2",//流量套餐ID
              "orderid" => $orderid,//自定义订单号，8-32字母数字组合
              "key" => $appkey,//应用APPKEY(应用详细页查询)
              "sign" => $sign,//校验值，md5(<b>OpenID</b>+key+phone+pid+orderid)，结果转为小写
        );
        $paramstring = http_build_query($params);
        $content = $recharge->juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        // if($result){
        //     echo '<pre>';
        //     if($result['error_code']=='0'){
        //         // print_r($result);
        //         echo '充值成功';
        //     }else{
        //         // print_r($result['reason']);
        //         // echo $result['error_code'].":".$result['reason'].'<br>';
        //         echo '充值失败';
        //     }
        // }else{
        //     echo "请求失败";
        // }
        //**************************************************
         
         
         
         
        //************4.订单状态查询************
        $url = "http://v.juhe.cn/flow/batchquery";
        $params = array(
              "orderid" => $orderid,//用户订单号，多个以英文逗号隔开，最大支持50组
              "key" => $appkey,//应用APPKEY(应用详细页查询)
        );
        $paramstring = http_build_query($params);
        $content = $recharge->juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        if($result){
            // echo '<pre>';
            if($result['error_code']=='0'){
                // print_r($result);
                $data['prize_type'] = '流量充值';
                $data['phone_number'] = $tel;
                $data['order_number'] = $orderid;
                $data['draw_date'] = date('Y-m-d',time());
                M('cw_luck') -> data($data) -> add();
                $this->ajaxReturn('充值成功');
            }else{
                // echo $result['error_code'].":".$result['reason'];
                $this->ajaxReturn('充值失败');
            }
        }else{
            $this->ajaxReturn("请求失败");
        }
        //**************************************************
         
         
         
         
        //************5.充值订单列表************
        // $url = "http://v.juhe.cn/flow/orderlist";
        // $params = array(
        //       "pagesize" => "10",//每页返回条数，最大200，默认50
        //       "page" => "1",//页数，默认1
        //       "phone" => $tel,//指定要查询的手机号码
        //       "key" => $appkey,//应用APPKEY(应用详细页查询)
        // );
        // $paramstring = http_build_query($params);
        // $content = juhecurl($url,$paramstring);
        // $result = json_decode($content,true);
        // if($result){
        //     if($result['error_code']=='0'){
        //         print_r($result);
        //     }else{
        //         echo $result['error_code'].":".$result['reason'];
        //     }
        // }else{
        //     echo "请求失败";
        // }
        //**************************************************
         
         
         
         
        //************6.运营商状态查询************
        // $url = "http://v.juhe.cn/flow/operatorstate";
        // $params = array(
        //       "key" => $appkey,//应用APPKEY(应用详细页查询)
        // );
        // $paramstring = http_build_query($params);
        // $content = juhecurl($url,$paramstring);
        // $result = json_decode($content,true);
        // if($result){
        //     if($result['error_code']=='0'){
        //         print_r($result);
        //     }else{
        //         echo $result['error_code'].":".$result['reason'];
        //     }
        // }else{
        //     echo "请求失败";
        // }
        //**************************************************
 
    }
    // 话费充值接口
    public function huafei(){
        if (IS_POST){
            
            //----------------------------------
            // 聚合数据-手机话费充值API调用类
            //----------------------------------
            
            header('Content-type:text/html;charset=utf-8');

            
            // 二、配置一些必须的参数
            
            //获取手机号码
            $tel = I('post.tel');
            
            //接口基本信息配置
            $appkey = '9e3f145906a3b9b01a101904f2f8a5b1'; //从聚合申请的话费充值appkey
            $openid = 'JH96d2dcdca3228aba411971f91f3aac22'; //注册聚合账号就会分配的openid，在个人中心可以查看
            
            $recharge = new recharge($appkey,$openid);
            
            // 三、检测手机号码以及面额是否可以充值
            
//             $tel = I('post.tel');
            
//             $telCheckRes = $recharge->telcheck($tel,30);
//             if($telCheckRes){
//                 //说明支持充值，可以继续充值操作，以下可以根据实际需求修改
//                 echo "OK<br>";
//             }else{
//                 //暂不支持充值，以下可以根据实际需求修改
//                 exit("对不起，该面额暂不支持充值");
//             }
            
            // 四、根据手机号码以及面额查询商品信息
            
//             $telQueryRes =$recharge->telquery($tel,30); #可以选择的面额5、10、20、30、50、100、300
//             if($telQueryRes['error_code'] == '0'){
//                 //正常获取到话费商品信息
//                 $proinfo = $telQueryRes['result'];
//                 /*
//                  [cardid] => 191406
//                  [cardname] => 江苏电信话费10元直充
//                  [inprice] => 10.02
//                  [game_area] => 江苏苏州电信
//                 */
// //                 echo "商品ID：".$proinfo['cardid']."<br>";
// //                 echo "商品名称：".$proinfo['cardname']."<br>";
// //                 echo "进价：".$proinfo['inprice']."<br>";
// //                 echo "手机归属地：".$proinfo['game_area']."<br>";
//             }else{
//                 //查询失败，可能维护、不支持面额等情况
// //                 echo $telQueryRes["error_code"].":".$telQueryRes['reason'];
//             }
            
            
            // 五、提交话费充值
            
            $time = time();
            $orderid = 'hy'.$time; //自己定义一个订单号，需要保证唯一
            $telRechargeRes = $recharge->telcz($tel,30,$orderid); #可以选择的面额5、10、20、30、50、100、300
//             if($telQueryRes['error_code'] =='0'){
//                 //提交话费充值成功，可以根据实际需求改写以下内容
//                 echo "充值成功,订单号：".$telRechargeRes['result']['sporder_id'];
//                 var_dump($telRechargeRes);
//             }else{
//                 //提交充值失败，具体可以参考$telRechargeRes['reason']
//                 var_dump($telRechargeRes);
//             }
            
            
            // 六、订单状态查询
            
//             $orderid = '1114050205'; //商家自定的订单号
            $orderStatusRes = $recharge->sta($orderid);
            
            if($orderStatusRes['error_code'] == '0'){
                //查询成功
                if($orderStatusRes['result']['game_state'] =='1'){
//                     echo "充值成功";
                $data['prize_type'] = '话费充值';
                $data['phone_number'] = $tel;
                $data['order_number'] = $orderid;
                $data['draw_date'] = date('Y-m-d',time());
                M('cw_luck') -> data($data) -> add();
                    $this -> ajaxReturn("充值成功");
                }elseif($orderStatusRes['result']['game_state'] =='9'){
//                     echo "充值失败";
                    $this -> ajaxReturn("充值失败");
                }elseif($orderStatusRes['result']['game_state'] =='-1'){
//                     echo "提交充值失败"; //可能是如运营商维护、账户余额不足等情况
                    $this -> ajaxReturn("提交充值失败");
                }
            }else{
                //查询失败
//                 echo "查询失败:".$orderStatusRes['reason']."(".$orderStatusRes['error_code'].")";
                $msg = $orderStatusRes['reason']."(".$orderStatusRes['error_code'].")";
                $this -> ajaxReturn($msg);
            }
        }
    }
}