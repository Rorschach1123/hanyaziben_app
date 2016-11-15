<?php
namespace Admin\Controller;
use Think\Controller;
class IosReturnController extends Controller{
    public function index(){
        /*** Kiki's api for ios app ***/
        header("Content-type:application/json");
        
        if (IS_POST){
            $ios_return_json = file_get_contents("php://input");
            
            $decode_json_arr = json_decode($ios_return_json,true);
            
            $data = $decode_json_arr['data'];
            
            if (!is_array($data)){
                $decode_json_data = json_decode($data,true);
                
                // 对日期数据进行处理
                $map = $decode_json_data['commit'];
                $date = preg_replace('/([\x80-\xff]*)/i','',$map['date']);
                $map['date'] = $date;
            }
            // 判断当前数据类型
            $opt = isset($decode_json_arr['opt'])?$decode_json_arr['opt']:'';

            // 总积分查询
            function get_total_points($uid){
                
                // 依次查询注册积分表、签到积分表、产品积分表的有效剩余积分
                $register_sum = M('pt_register_points') -> where("user_id = '{$uid}' and `is_effective` = 1") -> sum('rest_points');
                $sign_sum = M('pt_sign_points') -> where("user_id = '{$uid}' and `is_effective` = 1") -> sum('rest_points');
                $investment_sum = M('pt_investment_points') -> where("user_id = '{$uid}' and `is_effective` = 1") -> sum('rest_points');
                $total = $register_sum + $sign_sum + $investment_sum;
                return $total;
            }

            switch ($opt){
                // 历史荣誉
                case 'lsry':
                    $arr = array();
                    $titles = array(
                        "“瀚亚资本”荣膺“2012年全球责任中国行动",
                        "“瀚亚资本”荣获“卓越表现奖”",
                        "“瀚亚资本”荣膺“2011-2012年度中国卓越",
                        "“瀚亚资本”荣膺“金融服务行业TOP10奖”",
                        "“瀚亚资本”荣获“2012卓越竞争力金融机构",
                        "“瀚亚资本”荣获“中国第三方理财机构最具领导力品牌”奖",
                        "“瀚亚资本”荣获“中国房地产金融行业最具影响力领军品牌企业”奖",
                        "“瀚亚资本”荣获“2013 中国金融论坛合作伙伴”奖",
                        "“瀚亚资本”荣获“最具社会责任感企业”奖",
                        "“瀚亚资本”荣获“2013 优秀非公企业”奖",
                        "“瀚亚资本”荣获“2013 非公经济杰出贡献企业”奖",
                        "“瀚亚资本”荣获“2013优秀非公企业”奖",
                        "“瀚亚资本”荣获“亚洲十大最具公信力品牌”",
                        "“瀚亚资本”荣获“金典奖--中国地产金融投资公众满意典范品牌”奖",
                        "“瀚亚资本”荣获第十届中国国际金融论坛授予“合作伙伴”奖",
                        "“瀚亚资本”荣获“华尊奖--中国财富管理行业最具影响力十大品牌”奖",
                        "“瀚亚资本”荣获“2014年度中国最具成长性财富管理机构”奖",
                        "“瀚亚资本”荣获“浙江省3.15金承诺示范单位”奖",
                        "“瀚亚资本”荣获“2015中国公益奖-集体奖”",
                        "“瀚亚资本”荣获“独立资产管理最佳表现”奖"
                    );
                    $contents = array(
                        "2012年11月10日，“瀚亚资本”凭借高度的企业社会责任和社会公信力获“2012年全球责任中国行动奖”。",
                        "2012年11月10日，“瀚亚资本”凭借引领行业的创新商业模式、积极承担社会责任而获评“2012年卓越表现奖”。",
                        "2012年12月12日，“瀚亚资本”凭借2011-2012年度的出色的公司业绩、较高的客户美誉度、高度的社会责任感和卓越的产品管理与产品创新最终荣获“2011-2012年度中国卓越金融奖  年度卓越金融第三方理财机构”奖项。",
                        "“瀚亚资本”凭借领先创新的经营模式和高速发展方针荣获“2012中国国际投资理财博览会”金融服务行业TOP10大奖。",
                        "2012年11月10日，“瀚亚资本”凭借综合实力、经营理念和经营方式荣获“2012卓越竞争力金融机构”大奖。",
                        "2013 年4月瀚亚资本荣获“中国第三方理财机构最具领导力品牌”奖。",
                        "2013 年5月瀚亚资本荣获“中国房地产金融行业最具影响力领军品牌企业”奖。",
                        "2013 年5月瀚亚资本荣获“2013中国金融论坛合作伙伴”奖。",
                        "2013 年6月瀚亚资本荣获“最具社会责任感企业”奖。",
                        "2013 年7月瀚亚资本荣获“2013 优秀非公企业”奖。",
                        "2013 年7月瀚亚资本荣获“2013 非公经济杰出贡献企业”奖。",
                        "2013年7月瀚亚资本荣获“2013优秀非公企业”奖。",
                        "2013年9月9日瀚亚资本荣获“亚洲十大最具公信力品牌”。",
                        "2013 年10月瀚亚资本荣获“金典奖--中国地产金融投资公众满意典范品牌”奖。",
                        "2013 年10月荣获第十届中国国际金融论坛授予“合作伙伴”奖。",
                        "2014 年4月瀚亚资本荣获“华尊奖--中国财富管理行业最具影响力十大品牌”奖。",
                        "2014年11月1日瀚亚资本荣获“2014年度中国最具成长性财富管理机构”奖。",
                        "2015年瀚亚资本荣获“浙江省3.15金承诺示范单位”奖。",
                        "2015年瀚亚资本荣获“2015中国公益奖-集体奖”。",
                        "2016年1月瀚亚资本荣获胡润百富“独立资产管理最佳表现”奖。"
                    );
                    $images = array(
                        "http://www.hanyacapital.com/uploads/allimg/130221/1-13022116262R53.jpg",
                        "http://www.hanyacapital.com/uploads/allimg/130221/1-130221162I0511.jpg",
                        "http://www.hanyacapital.com/uploads/allimg/130221/ry1.jpg",
                        "http://www.hanyacapital.com/uploads/allimg/130221/1-13022116111L60.jpg",
                        "http://www.hanyacapital.com/uploads/allimg/130221/1-130221162612122.jpg",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-4_1.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-5_1.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-5_2.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-6_1.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-7_1.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-7_2.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-7_3.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-9-9_1.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-10_1.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2013-10_2.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2014-4_1.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2014-11-1_1.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2015_1.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2015_2.png",
                        "http://www.hanyacapital.com/uploads/allimg/160418/2016-1_1.png"
                    );
                    for ($i=0;$i<10;$i++){
                        $arr[$i]['title'] = $titles[$i];
                        $arr[$i]['content'] = $contents[$i];
                        $arr[$i]['image'] = $images[$i];
                    }
                    
                    $msg = json_encode($arr,true);
                    echo $msg;
                    return true;
                    
                    break;
                // 预约理财师
                case 'book':
                    // 获取当前用户名
                    // $username = $decode_json_arr['username'];
                    // $User = M('cw_user') -> field('id') -> where("username = '{$username}'") -> find();
                    // $uid = $User['id'];
                    // 添加预约记录
                    $book = M('cw_book');
                    $tel = isset($map['tel'])?$map['tel']:'';
                    unset($map['tel']);
                    if ($tel != ''){
                        $planner = M('cw_planner');
                        $plannerid = $planner -> field('id') -> where('tel='.$tel) -> find();
                        if (!empty($plannerid)){
                            $pid = $plannerid['id'];
                            $map['pid'] = $pid;
                            // $map['user_id'] = $uid;
                            $map['date'] = date('Y-m-d',time());
                        }
                    }
                    $result = $book->data($map)->add();
                    if ($result){
                        $status = 'succeed';
                    }
                    else {
                        $status= 'fail';
                    }
                    
                    $msg = json_encode(array('submit' => $status),true);
                    
                    // 返回值
                    echo $msg;
                    return true;
    
                    break;
                // 销售心得
                case 'experience':
                    $planner = M('cw_planner')->field('picture')->where('is_show = 1')->order('order_id asc')->select();
                    for ($i=0; $i < count($planner); $i++) { 
                        if ($planner[$i]['picture'] == '') {
                            unset($planner[$i]);
                        }
                        else {
                            $planner[$i]['picture'] = "http://app.hanyalicai.com/Public/".$planner[$i]['picture'];
                            $info[] = $planner[$i];
                        }
                        
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // MVP
                case 'MVP':
                    $achievement = M('cw_achievement')->field('name,company,amount')->order('amount desc')->select();
                    $info['info'] = $achievement;
                    $info['date'] = '2016年10月20日';
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 预约有礼
                case 'canlottery':
                    // 获取当前用户名
                    $username = $decode_json_arr['username'];
                    $User = M('cw_user') -> field('id') -> where("username = '{$username}'") -> find();
                    $uid = $User['id'];

                    // 判断当前用户是否有预约记录
                    $nums = M('cw_book') -> where("user_id = '{$uid}'") -> count();
                    if ($nums == 1) {
                        $status = 'no';
                    }
                    else {
                        $status = 'no';
                    }
                    $msg = json_encode(array('status'=>$status),true);
                    echo $msg;
                    return true;

                    break;

                // 理财师列表
                case 'star':
                    $planner = M('cw_planner');
                    $planners = $planner -> where("is_show = 1") -> order('order_id asc') -> select();
                    foreach ($planners as $k => $vo){
                        $image = "http://app.hanyalicai.com/Public/".$vo['image'];
                        $vo['image'] = $image;
                        $arr['planners'][$k] = $vo;
                    }
                    
                    $arr['banner'] = 'http://app.hanyalicai.com/Public/Uploads/Images/planners/banner.png';
                    
                    $msg = json_encode($arr,true);
                    
                    // 返回值
                    
//                     $comment = M('cw_comment');
//                     $ini['address'] = $msg;
//                     $result = $comment->data($ini)->add();
                    
                    echo $msg;
                    return true;
                    
                    break;
                // 手机号验证
                case 'isExesited':
                    $user = M('cw_user');
                    // md5加密
                    $ini['username'] = $data;
                    // 判断该用户是否存在
                    $verification = $user->where($ini)->count();
                    if ($verification==0){
                        $status = 'succeed';
                    }
                    else {
                        $status = 'existed';
                    }
                    $msg = json_encode(array('submit' => $status),true);
                    // 返回值
                    echo $msg;
                    return true;
                    
                    break;
                    
                // 注册
                case 'register':
                    $user = M('cw_user');
                    // md5加密
                    $ini1['username'] = $map['username'];
                    $where['username'] = $map['username'];
                    // 判断该用户是否存在
                    $verification = $user->where($where)->find();
                    if ($verification==''){
                        $ini1['password'] = md5($map['password']);
                        $time = time();
                        $ini1['date'] = date('Y-m-d',$time);
                        $result = $user->data($ini1)->add();
                        if ($result){
                            // 送注册积分
                            $User = $user->where($where)->find();
                            $uid = $User['id'];
                            $RegisterPoints = M('pt_register_points');
                            $count = $RegisterPoints->where("user_id = '{$uid}'")->count();
                            if ($count == '0') {
                                $ini['user_id'] = $uid;
                                $ini['points'] = 100;
                                $ini['rest_points'] = $ini['points'];
                                $ini['register_date'] = $ini1['date'];
                                $ini['time'] = $time;
                                $RegisterPoints->data($ini)->add();
                            }
                            $status = 'succeed';
                        }
                        else {
                            $status= 'fail';
                        }
                    }
                    else {
                        $status = 'existed';
                    }
                    $arr = array('submit' => $status);
                    $msg = json_encode($arr,true);
                    // 返回值
                    echo $msg;
                    return true;
                    
                    break;
                // 绑定身份证
                case 'bond':
                    // 获取当前用户名
                    $username = $decode_json_data['phoneNum'];
                    
                    // 判断当前用户名是否为空
                    if ($username!=''){
                        
                        $user = M('cw_user');
                        $rs = $user -> where('username='.$username) -> find();
                        $uid = $rs['id'];
                        // 判断当前用户是否存在
                        if (!empty($rs)){
                            
                            $idnumber = $decode_json_data['IDNum'];
                            // 判断真实姓名和身份证号是否为空
                            if ($idnumber!=''){
                                
                                // 进行绑定操作
                                $ini['idnumber'] = $idnumber;
                                
                                $rs = $user -> where('username='.$username) -> save($ini);
                                if ($rs){

                                    $investment_points = M('pt_investment_points');

                                    $count = $investment_points->where("id_number = '{$idnumber}'")->count();

                                    if ($count == 0){

                                        $mysql_server_name = '103.242.175.34:6666';

                                        $mysql_username = 'root';
                                    
                                        $mysql_password = '256wty@%^WTY';

                                        $mysql_database = 'app';
                                        
                                        $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                                        
                                        mysql_query("set names 'utf8'");
                                        
                                        mysql_select_db($mysql_database);
                                        
                                        $sql = "select * from `pro_info` where `id_number` = '{$idnumber}'";
                                        
                                        $result = mysql_query($sql,$link);
                                        
                                        while ($row = mysql_fetch_assoc($result)) {
                                            $arr[] = $row;
                                        }
                                        $overdue_points = M('pt_overdue_points');
                                        for ($i=0; $i < count($arr); $i++) {
                                            $time = time();
                                            $ini1['is_effective'] = 1;
                                            $ini1['user_id'] = $uid;
                                            $ini1['id_number'] = $idnumber;
                                            $ini1['points'] = floor($arr[$i]['investment_amount']/100);
                                            $ini1['rest_points'] = $ini1['points'];
                                            $ini1['expiration_date'] = $arr[$i]['expiration_date'];
                                            $ini1['update_date'] = date('Y-m-d',$time);
                                            $ini1['time'] = $time;
                                            if ($arr[$i]['contract_states'] != '合同执行') {
                                                $ini1['is_effective'] = 0;
                                                $ini2['user_id'] = $uid;
                                                $ini2['expired_points'] = floor($arr[$i]['investment_amount']/100);
                                                $ini2['expired_date'] = $arr[$i]['expiration_date'];
                                                $expired_time = strtotime($ini2['expired_date']);
                                                $ini2['time'] = $expired_time;
                                                $overdue_points->data($ini2)->add();
                                            }
                                            $investment_points->data($ini1)->add();
                                        }
                                    }
                                    else {
                                        $saveini['user_id'] = $uid;
                                        $investment_points->where("id_number = '{$idnumber}'")->save($saveini);
                                    }


                                    $status = 'succeed';
                                }
                                else {
                                    $status = 'fail';
                                }
                                
                            }
                            else {
                                $status = 'null_username or null_idnumber';
                            }
                        }
                        else {
                            $status = 'wrong_username';
                        }
                        
                    }
                    else {
                        $status = 'null_username';
                    }
                    
                    $arr = array('submit' => $status);
                    $msg = json_encode($arr,true);
                    // 返回值
                    echo $msg;
                    return true;
                    break;
                // 登录验证
                case 'login':
                    $username = $map['userName'];
                    $password = $map['password'];
                    $user = M('cw_user');
                    // 判断当前用户名是否存在
                    $ini['username'] = $username;
                    $rs = $user->where($ini)->find();
                    if ($rs==''){
                        $status = 'wrong_username';
                    }
                    else {
                        $ini['password'] = md5($password);
                        $rs = $user->where($ini)->find();
                        if ($rs==''){
                            $status = 'wrong_password';
                        }
                        else {
                            session('USER',$username);
                            if ($rs['sex'] == 1){
                                $rs['sex'] = '男';
                            }
                            else{
                                $rs['sex'] = '女';
                            }
                            $status = 'succeed';
                        }
                    }
                    if ($status != 'succeed'){
                        $msg = json_encode(array('submit' => $status),true);
                    }
                    else {
                        $msg = json_encode(array('submit' => $status,'data' => $rs),true);
                    }
                    // 返回值
//                     $comment = M('cw_comment');
//                     $ini1['address'] = $msg;
//                     $result = $comment->data($ini1)->add();
                    echo $msg;
                    return true;
                    
                    break;
                // 忘记密码
                case 'find':
                    // 用户名
                    $username = $map['userName'];
                    // 新密码
                    $password = $map['newPassword'];
                    
                    $ini['password'] = md5($password);
                    
                    $user = M('cw_user');
                    $rs = $user->where('username='.$username)->save($ini);
                    if ($rs == 0 || $rs){
                        $status = 'succeed';
                    }
                    else {
                        $status = 'fail';
                    }
                    
                    $arr = array('submit' => $status);
                    $msg = json_encode($arr,true);
                    // 返回值
                    echo $msg;
                    return true;
                    
                    break;
                // 绑定数据
                case 'save':
                    
                    $username = $map['userNum'];
                    
                    $user = M('cw_user');
                    
                    $rs = $user->field('username')->where('username='.$username)->find();
                    
                    if (empty($rs)){
                        $status = 'wrong_username';
                    }
                    else {
                        $map['birth'] = $map['birthday'];
                        unset($map['birthday']);
                        unset($map['date']);
                        $rs = $user->where('username='.$username)->data($map)->save();
                        if ($rs || $rs==0){
                            $status = 'success';
                        }
                        else{
                            $status = 'fail';
                        }
                    }
                    
                    $arr = array('submit' => $status);
                    $msg = json_encode($arr,true);
                    // 返回值
                    echo $msg;
                    return true;
                    
                    break;
                // 获取客户个人信息
                case 'userInfo':
                    // 获取用户名
                    $username = $decode_json_arr['username'];
                    $info = M('cw_user')->field('realname,sex,birth,location,job,hobby,email')->where("username = '{$username}'")->find();
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 消息推送
                case '':
                	
                	break;
               	// 活动中心（个人）
               	case '':
               		# code...
               		break;
                // 收藏理财师
                case 'update_licaishi':
                    $username = $decode_json_data['username'];
                    $adviser_tel = $decode_json_data['phonenumber'];
                    $where['username'] = $username;
                    $User = M('cw_user');
                    $result = M('cw_user')->where($where)->find();
                    if ($result['collection']=='') {
                        // 收藏为空
                        if($decode_json_data['state']=="save"){ // 收藏操作
                            $save_ini['collection'] = $adviser_tel;
                            M('cw_user')->where("username = '{$username}'")->save($save_ini);
                        }
                        elseif ($decode_json_data['state']=="cancel") { // 忽略取消收藏
                    
                        }
                    }
                    else {
                        // 收藏不为空
                        if (strstr($result['collection'],$adviser_tel)) {
                            // 已收藏过该理财师
                            if($decode_json_data['state']=="save"){ // 收藏操作（忽略）
                    
                            }
                            elseif ($decode_json_data['state']=="cancel") { // 进行删除理财师操作
                                $collection = $result['collection'];
                                $collection_arr = explode(',',$collection);
                                foreach ($collection_arr as $k => $v) {
                                    if ($v == $adviser_tel) {
                                        unset($collection_arr[$k]);
                                    }
                                    $new_collection_arr = $collection_arr;
                                }
                                $new_collection = implode(',',$new_collection_arr);
                                $save_ini['collection'] = $new_collection;
                                $User->where($where)->save($save_ini);
                            }
                        }
                        else { // 未收藏过该理财师
                            if($decode_json_data['state']=="save"){ // 收藏操作
                                $save_ini['collection'] = $result['collection'].','.$adviser_tel;
                                M('cw_user')->where("username = '{$username}'")->save($save_ini);
                            }
                            elseif ($decode_json_data['state']=="cancel") { // 忽略取消收藏
                    
                            }
                        }
                    }
                    
                    break;

                // 收藏产品
                case 'update_product':
                    $username = $decode_json_data['username'];
                    $product_number = $decode_json_data['product_number'];
                    $where['username'] = $username;
                    $User = M('cw_user');
                    $result = M('cw_user')->where($where)->find();
                    if ($result['products_collection']=='') {
                        // 收藏为空
                        if($decode_json_data['state']=="save"){ // 收藏操作
                            $save_ini['products_collection'] = $product_number;
                            M('cw_user')->where("username = '{$username}'")->save($save_ini);
                        }
                        elseif ($decode_json_data['state']=="cancel") { // 忽略取消收藏
                    
                        }
                    }
                    else {
                        // 收藏不为空
                        if (strstr($result['products_collection'],$product_number)) {
                            // 已收藏过该理财师
                            if($decode_json_data['state']=="save"){ // 收藏操作（忽略）
                    
                            }
                            elseif ($decode_json_data['state']=="cancel") { // 进行删除理财师操作
                                $products_collection = $result['products_collection'];
                                $products_collection_arr = explode(',',$products_collection);
                                foreach ($products_collection_arr as $k => $v) {
                                    if ($v == $product_number) {
                                        unset($products_collection_arr[$k]);
                                    }
                                    $new_products_collection_arr = $products_collection_arr;
                                }
                                $new_products_collection = implode(',',$new_products_collection_arr);
                                $save_ini['products_collection'] = $new_products_collection;
                                $User->where($where)->save($save_ini);
                            }
                        }
                        else { // 未收藏过该理财师
                            if($decode_json_data['state']=="save"){ // 收藏操作
                                $save_ini['products_collection'] = $result['products_collection'].','.$product_number;
                                M('cw_user')->where("username = '{$username}'")->save($save_ini);
                            }
                            elseif ($decode_json_data['state']=="cancel") { // 忽略取消收藏
                    
                            }
                        }
                    }
                    
                    break;
                // 查看收藏信息
                case 'getCollection':
                    $username = $decode_json_arr['username'];
                    // 查看当前收藏的理财师信息
                    $user_info = M('cw_user')->field('products_collection,collection')->where("username = '{$username}'")->find();
                    
                        $collection = $user_info['collection'];
                        if ($collection != ''){
                            // 收藏的理财师信息
                            $collection_arr = explode(',',$collection);
                            $where['tel'] = array('in',$collection_arr);
                            $advisers = M('cw_planner')->field('name,tel,company,slogan,resume,image')->where($where)->select();
                            foreach ($advisers as $v) {
                                $v['image'] = "http://app.hanyalicai.com/Public/".$v['image'];
                                $adviser[] = $v;
                            }
                            $info['advisers'] = $adviser;
                        }
                        else {
                            $info['advisers'] = array();
                        }
                    
                    // 查看当前收藏的产品信息
                    
                        $products_collection = $user_info['products_collection'];
                        if ($products_collection != ''){
                            // 收藏的产品信息
                            $products_collection_arr = explode(',',$products_collection);
                            $where['product_number'] = array('in',$products_collection_arr);
                            $Shuju = M('Shuju','yunying_','DB_CONFIG2');
                            $products = $Shuju->field()->where($where)->select();
                            foreach ($products as $key => $value) {
                            $value['raise_progress'] = (sprintf( "%.3f ",$value['target_money']/$value['product_scale'])*100).'%';
                            $products[$key] = $value;
                    }
                            $info['products'] = $products;
                        }
                        else {
                            $info['products'] = array();
                        }
                    
                    
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;

                    break;
                // 查看顾问信息
                case 'getadviser':
                    $id_number = $decode_json_arr['userid'];
                    $empty_arr1 = array('name'=>'','tel'=>'','company'=>'','slogan'=>'','resume'=>'','head'=>'');
                    $empty_arr2 = array('slogan'=>'','resume'=>'','head'=>'');
                    $empty_arr3 = array('tel'=>'','slogan'=>'','resume'=>'','head'=>'');
                    if ($id_number != '') {                        
                        $Info = M('Info','pro_','DB_CONFIG1');
                        $info = $Info->field('consultant_name as name,consultant_tel as tel,branch as company')->where("id_number = '{$id_number}'")->find();
                        if ($info != '') {
                            if ($info['tel'] != '') {
                                $where['tel'] = $info['tel'];
                                $planner = M('cw_planner')->field('name,tel,company,slogan,resume,head')->where($where)->find();
                                if ($planner != '') {
                                    $planner['head'] = 'http://app.hanyalicai.com/Public/'.$planner['head'];
                                    $arr = $planner;
                                }
                                else {
                                    $arr = array_merge($info,$empty_arr2);
                                }
                            }
                            else {
                                $arr = array_merge($info,$empty_arr3);
                            }
                        }
                        else {
                            $arr = $empty_arr1;
                        }
                    }
                    else {
                        $arr = $empty_arr1;
                    }
                    $msg = json_encode(array($arr),true);
                    echo $msg;
                    return true;
                    break;
                // 持仓信息
                case 'query':
                    // 1.判断当前用户是否绑定身份证号（查询时须使用身份证号进行查询）
//                     $user = M('cw_user');
                    // 当前用户名
                    
//                     $idnumbers = $user->field('idnumber')->where('username='.$username)->find();
                    
//                     if ($idnumbers['idnumber'] == ''){
//                         $user  = M('User','pro_','mysql://admin:lzd0921@106.2.178.118:3306/hanyaziben');
//                         unset($idnumbers);
//                         $idnumbers = $user->field('idnumber')->where('mobile='.$username)->find();
//                     }
//                         $idnumber = $idnumbers['idnumber'];
                        
                    $idnumber = $decode_json_arr['idNumber'];
                    
                    /**************记录开始*****************/
                    
                    if ($idnumber!=''){
                        
                        $data['idnumber'] = $idnumber;
                        $data['time'] = date('Y-m-d H:i:s',time());
                        $record = M('cw_record');
                        $record -> add($data);
                        
                    
                    
                    
                    /**************记录结束*****************/
                    
                        $mysql_server_name = '103.242.175.34:6666';
            
                        $mysql_username = 'root';
                    
                        $mysql_password = '256wty@%^WTY';

                        $mysql_database = 'app';
                        
                        $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                        
                        mysql_query("set names 'utf8'");
                        
                        mysql_select_db($mysql_database);
                        
                        $sql = "call chicangchaxun('{$idnumber}')";
                        
                        $result = mysql_query($sql,$link);

                        while ($row = mysql_fetch_assoc($result)) {
                            $position[] = $row;
                        }
                        
                        mysql_close($link);

                        if (empty($position)){
                            
                            $arr = array('submit'=>'empty');
                            
                        }
                        else {
                            $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                
                            mysql_query("set names 'utf8'");
                            
                            mysql_select_db($mysql_database);

                            $sql = "call chicangchaxun_total('{$idnumber}')";
                             
                            $result = mysql_query($sql,$link);

                            $row = mysql_fetch_assoc($result);
                            
                            $amount[] = $row;

                            //20161104 $arr['total']=$amount;
                            $arr['total']=array(array("total_amount"=>"0"));
                             
                            $arr['product']=$position;
                            
                            $arr['submit']='succeed';
                        }


                    }
                    else{
                        $arr['total']= array(array("total_amount"=>"0"));
                        $arr['product']= array(array());
                        $arr['submit']='succeed';
                    }

                    $msg = json_encode($arr,true);
                    // 返回值
                    echo $msg;
                    return true;
                    
                    break;
                // 持仓信息（新版本）
                case 'query1':
                    // 1.判断当前用户是否绑定身份证号（查询时须使用身份证号进行查询）
//                     $user = M('cw_user');
                    // 当前用户名
                    
//                     $idnumbers = $user->field('idnumber')->where('username='.$username)->find();
                    
//                     if ($idnumbers['idnumber'] == ''){
//                         $user  = M('User','pro_','mysql://admin:lzd0921@106.2.178.118:3306/hanyaziben');
//                         unset($idnumbers);
//                         $idnumbers = $user->field('idnumber')->where('mobile='.$username)->find();
//                     }
//                         $idnumber = $idnumbers['idnumber'];
                        
                    $idnumber = $decode_json_arr['idNumber'];
                    
                    /**************记录开始*****************/
                    
                    if ($idnumber!=''){
                        
                        $data['idnumber'] = $idnumber;
                        $data['time'] = date('Y-m-d H:i:s',time());
                        $record = M('cw_record');
                        $record -> add($data);
                        
                    
                    
                    
                    /**************记录结束*****************/
                        // 投资明细

                        $mysql_server_name = '103.242.175.34:6666';
            
                        $mysql_username = 'root';
                    
                        $mysql_password = '256wty@%^WTY';

                        $mysql_database = 'app';
                        
                        $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                        
                        mysql_query("set names 'utf8'");
                        
                        mysql_select_db($mysql_database);
                        
                        $sql = "call chicangchaxun('{$idnumber}')";
                        
                        $result = mysql_query($sql,$link);

                        while ($row = mysql_fetch_assoc($result)) {
                            $position[] = $row;
                        }
                        
                        mysql_close($link);

                        if (empty($position)){
                            
                            $arr = array('submit'=>'empty');
                            
                        }
                        else {
                            // 在投总额
                            $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                
                            mysql_query("set names 'utf8'");
                            
                            mysql_select_db($mysql_database);

                            $sql = "call chicangchaxun_total('{$idnumber}')";
                             
                            $result = mysql_query($sql,$link);

                            $row = mysql_fetch_assoc($result);
                            
                            $total[] = $row;

                            $arr['total']=$total;

                            // 全部总额
                            
                            $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                
                            mysql_query("set names 'utf8'");
                            
                            mysql_select_db($mysql_database);

                            $sql = "call chicangchaxun_all('{$idnumber}')";
                             
                            $result = mysql_query($sql,$link);

                            $row = mysql_fetch_assoc($result);
                            
                            $all[] = $row;

                            $arr['all']=$all;

                            // 全部产品明细
                            $arr['product_all']=$position;

                            // 在投产品明细
                            foreach ($position as $v) {
                                if ($v['contract_states'] == '合同执行') {
                                    $product_sale[]=$v;
                                }
                            }

                            $arr['product_sale']=$product_sale;
                            
                            //20161104 $arr['submit']='succeed';
                            $arr['submit']='empty';
                        }


                    }
                    else{
                        $arr['total']= 0;
                        $arr['product']= array();
                        //20161104 $arr['submit']='succeed';
                        $arr['submit']='empty';
                    }

                    $msg = json_encode($arr,true);
                    // 返回值
                    echo $msg;
                    return true;
                    
                    break;
/****************************************************积分商城******************************************************/
                // 判断积分商城是否开放
                case 'isopen':
                	$mall = M('pt_points_mall')->field('status')->find();
                	if ($mall['status'] == '1') {
                		$status = 'yes';
                	}
                	else {
                		$status = 'no';
                	}
                    $msg = json_encode(array('status'=>$status),true);
                    echo $msg;
                    return true;
                    break;
                // 积分商城首页（热门礼品信息）
                case 'allgoods':
                    // 获取全部商品信息
                    $prize = M('pt_prizes');
                    $prize_arr = $prize -> where('is_effective = 1') -> order('order_id asc') -> select();
                    for ($i=0; $i < count($prize_arr); $i++) { 
                        $prize_image = 'http://app.hanyalicai.com/Public/'.$prize_arr[$i]['prize_image'];
                        $detail_image = 'http://app.hanyalicai.com/Public/'.$prize_arr[$i]['detail_image'];
                        $prize_arr[$i]['prize_image'] = $prize_image;
                        $prize_arr[$i]['detail_image'] = $detail_image;
                        $prize_arr[$i]['prize_id'] = strval($prize_arr[$i]['id']);
                        $all_prize_arr[] = $prize_arr[$i];
                        if ($prize_arr[$i]['is_hot'] == 1) {
                            $hot_image = 'http://app.hanyalicai.com/Public/'.$prize_arr[$i]['hot_image'];
                            $prize_arr[$i]['hot_image'] = $hot_image;
                            $hot_prize_arr[] = $prize_arr[$i];
                        }
                    }
                    $first = $hot_prize_arr[0];
                    $second = $hot_prize_arr[2];
                    $third = $hot_prize_arr[1];
                    $hot_prize_arr[0] = $third;
                    $hot_prize_arr[1] = $first;
                    $hot_prize_arr[2] = $second;
                    $info['hot'] = $hot_prize_arr;
                    $info['all'] = $all_prize_arr;

                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                break;
                // 签到情况
                case 'signstatus':
                    // 获取当前用户名
                    $username = $decode_json_arr['username'];
                    $user = M('cw_user');

                    // 确定用户id
                    $user_arr = $user -> where("username='{$username}'") -> find();
                    $uid = $user_arr['id'];
                    $idnumber = $user_arr['idnumber'];

                    // 确定总积分
                    $total = get_total_points($uid);
                    // 确定当前是否签到
                    $sign_point = M('pt_sign_points');
                    $time = time();
                    $today = date('Y-m-d',$time);
                    $count = $sign_point -> where("user_id='{$uid}' and sign_date='{$today}'") -> count();
                    if ($count != 0) {
                        $sign_status = 'yes';
                    }
                    else {
                        $sign_status = 'no';
                    }
                    // 确定已经连续签到几天
                    if ($sign_status == 'yes') {
                        $consecutive_day_arr = $sign_point -> field('consecutive_days') -> where("user_id='{$uid}' and sign_date='{$today}'") -> find();
                        $consecutive_day = $consecutive_day_arr['consecutive_days'];
                    }
                    else {
                        $yestoday = date('Y-m-d',strtotime('-1day'));
                        $consecutive_day_arr = $sign_point -> field('consecutive_days') -> where("user_id='{$uid}' and sign_date='{$yestoday}'") -> find();
                        if (empty($consecutive_day_arr)) {
                            $consecutive_day = 0;
                        }
                        else {
                            $consecutive_day = $consecutive_day_arr['consecutive_days'];
                        }
                    }
  
                    $info['days'] = $consecutive_day;
                    $info['status'] = $sign_status;
                    $info['total'] = $total;
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 点击签到
                case 'signing':
                    // 获取当前用户名
                    $username = $decode_json_arr['username'];
                    // 获取用户id
                    $user = M('cw_user');
                    $user_arr = $user -> field() -> where('username='.$username) -> find();
                    $uid = $user_arr['id'];
                    // 签到天数
                    $time = time();
                    $today = date('Y-m-d',$time);
                    $yestoday = date('Y-m-d',strtotime('-1day'));
                    $sign_point = M('pt_sign_points');
                    $consecutive_day_arr = $sign_point -> field('consecutive_days') -> where("user_id='{$uid}' and sign_date='{$yestoday}'") -> find();
                    if (!empty($consecutive_day_arr)) {
                        $consecutive_day = $consecutive_day_arr['consecutive_days'] + 1;
                    }
                    else {
                        $consecutive_day = 1;
                    }
                    
                    $count = $sign_point->where("user_id = '{$uid}' and sign_date='{$today}'")->count();
                    if ($count == 0) {
                    
                    // 签到积分
                    // 积分规则
                        switch ($consecutive_day) {
                            case 1:
                                $points = 5;
                                break;
                            case 2:
                                $points = 10;
                                break;
                            case 3:
                                $points = 15;
                                break;
                            case 4:
                                $points = 20;
                                break;
                            case 5:
                                $points = 25;
                                break;
                            case 6:
                                $points = 30;
                                break;
                            default:
                                $points = 35;
                                break;
                        }
                        $ini['user_id'] = $uid;
                        $ini['sign_date'] = $today;
                        $ini['time'] = $time;

                        $ini['consecutive_days'] = $consecutive_day;
                        $ini['points'] = $points;
                        $ini['rest_points'] = $ini['points'];
                        
                        $result = $sign_point -> add($ini);
                    }
                    if ($result) {
                        $status = 'succeed';
                    }
                    else {
                        $status = 'fail';
                    }
                    // 签到是否成功状态
                    $info['status'] = $status;
                    // 当前签到已天数
                    $info['days'] = $consecutive_day;
                    // 当前总积分（测试数据）
                    $total = get_total_points($uid);
                    $info['total'] = $total;

                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 积分明细（目前为测试数据）
                case 'pointsdetail':
                    // 获取当前用户名
                    $username = $decode_json_data['username'];
                    $user_arr = M('cw_user') -> field('id,idnumber') -> where("username = '{$username}'") -> find();

                    // 确定用户id
                    $uid = $user_arr['id'];
                    $idnumber = $user_arr['idnumber'];
                    $ini['user_id'] = $uid;

                if ($idnumber != '') {
                
                    $investment_points = M('pt_investment_points');

                    $count = $investment_points->where("id_number = '{$idnumber}'")->count();

                    if ($count == 0){

                                        $mysql_server_name = '103.242.175.34:6666';

                                        $mysql_username = 'root';
                                    
                                        $mysql_password = '256wty@%^WTY';

                                        $mysql_database = 'app';
                                        
                                        $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                                        
                                        mysql_query("set names 'utf8'");
                                        
                                        mysql_select_db($mysql_database);
                                        
                                        $sql = "select * from `pro_info` where `id_number` = '{$idnumber}'";
                                        
                                        $result = mysql_query($sql,$link);
                                        
                                        while ($row = mysql_fetch_assoc($result)) {
                                            $arr[] = $row;
                                        }
                                        $overdue_points = M('pt_overdue_points');
                                        for ($i=0; $i < count($arr); $i++) {
                                            $time = time();
                                            $ini1['is_effective'] = 1;
                                            $ini1['user_id'] = $uid;
                                            $ini1['id_number'] = $idnumber;
                                            $ini1['points'] = floor($arr[$i]['investment_amount']/100);
                                            $ini1['rest_points'] = $ini1['points'];
                                            $ini1['expiration_date'] = $arr[$i]['expiration_date'];
                                            $ini1['update_date'] = date('Y-m-d',$time);
                                            $ini1['time'] = $time;
                                            if ($arr[$i]['contract_states'] != '合同执行') {
                                                $ini1['is_effective'] = 0;
                                                $ini2['user_id'] = $uid;
                                                $ini2['expired_points'] = floor($arr[$i]['investment_amount']/100);
                                                $ini2['expired_date'] = $arr[$i]['expiration_date'];
                                                $expired_time = strtotime($ini2['expired_date']);
                                                $ini2['time'] = $expired_time;
                                                $overdue_points->data($ini2)->add();
                                            }
                                            $investment_points->data($ini1)->add();
                                        }
                                    }
                                    else {
                                        $saveini['user_id'] = $uid;
                                        $investment_points->where("id_number = '{$idnumber}'")->save($saveini);
                                    }

                }

                    // 查询注册积分表
                    $register_points = M('pt_register_points') -> field('points as point,register_date as date,time') -> where($ini) -> find();
                    // 查询资产积分表
                    $investment_points_arr = M('pt_investment_points') -> field('points as point,update_date as date,time') -> where($ini) -> select();
                    // 查询签到积分表
                    $sign_points_arr = M('pt_sign_points') -> field('points as point,sign_date as date,time') -> where($ini) -> select();
                    // 查询积分兑换表
                    $ini1['user_id'] = $uid;
                    $ini1['is_effective'] = 1;
                    $exchange_points_arr = M('pt_exchange_orders') -> field('points as point,exchange_date as date,time') -> where($ini1) -> select();
                    // 积分失效表
                    $expired_points_arr = M('pt_overdue_points') -> field('expired_points as point,expired_date as date,time') -> where($ini) -> select();
                    if (!empty($register_points)) {

                        $register_points['msg'] = '注册积分';
                        $register_points['point'] = "+".$register_points['point'];
                        $point_detail[] = $register_points;
                    }

                    if (!empty($investment_points_arr)) {
                        for ($i = 0;$i < count($investment_points_arr);$i ++){
                        $investment_points_arr[$i]['msg'] = '产品积分';
                        $investment_points_arr[$i]['point'] = "+".$investment_points_arr[$i]['point'];
                        $point_detail[] = $investment_points_arr[$i];
                        }
                    }
                    
                    if (!empty($sign_points_arr)) {
                        for ($i = 0;$i < count($sign_points_arr);$i ++){
                        $sign_points_arr[$i]['msg'] = '签到积分';
                        $sign_points_arr[$i]['point'] = "+".$sign_points_arr[$i]['point'];
                        $point_detail[] = $sign_points_arr[$i];
                        }
                    }

                    if (!empty($exchange_points_arr)) {
                        for ($i = 0;$i < count($exchange_points_arr);$i ++){
                        $exchange_points_arr[$i]['msg'] = '兑换积分';
                        $exchange_points_arr[$i]['point'] = "-".$exchange_points_arr[$i]['point'];
                        $point_detail[] = $exchange_points_arr[$i];
                        }
                    }

                    if (!empty($expired_points_arr)) {
                        for ($i = 0;$i < count($expired_points_arr);$i ++){
                        $expired_points_arr[$i]['msg'] = '过期积分';
                        $expired_points_arr[$i]['point'] = "-".$expired_points_arr[$i]['point'];
                        $point_detail[] = $expired_points_arr[$i];
                        }
                    }
                    
                    function hello($e){
                        
                        $date = '0';
                        $key = 0;
                        
                        for ($i = 0;$i < count($e);$i ++){
                            
                            if($date < $e[$i]['time']){
                                $date = $e[$i]['time'];
                                $key = $i;
                            }
                        }
                        
                        return $key;
                    }
                    
                    function max_date($e){
                        while(count($e) != 0){
                            $key = hello($e);
                            $Arr[] = $e[$key];
                            unset($e[$key]);
                            sort($e);
                        }
                        return $Arr;
                    }
                    
                    $arr = max_date($point_detail);

                    $page = $decode_json_data['page'];
                    // 限制每页显示的条数
                    $limit = 20;
                    // 本页数据的开始位置
                    $start = ($page - 1)*$limit;
                    // 本页数据的结束位置
                    $end = $page*$limit;
                    // 确定总积分
                    $total_points = get_total_points($uid);
                    $total_points = strval($total_points);
                    
                    // 总共的条数
                    $total = count($arr);

                    // 页数限制
                    $page_limit = ceil($total/$limit);

                    if ($page > $page_limit) {
                        $Arr['status'] = 'end';
                    }
                    else {
                        $Arr['status'] = 'continue';
                    }

                    // 仅当请求第一页的时候传总积分
                    if ($page == 1) {
                        //20161104 $Arr['total'] = $total_points;
                        $Arr['total'] = "0";
                    }

                    // 判断当前页的最后条数与总条数的数量关系
                    if ($total < $end) {
                        $end = $total;
                        // $Arr['status'] = 'end';
                    }
                    else {
                        // $Arr['status'] = 'continue';
                    }

                    for ($i=$start;$i<$end;$i++){
                        $new[] = $arr[$i];
                    }
                    
                    $Arr['detail'] = $new;

                    $msg = json_encode($Arr,true);
                    // $comment = M('cw_comment');
                    // $ini['address'] = $msg;
                    // $result = $comment->data($ini)->add();
                    echo $msg;
                    return true;
                    break;
                //更新地址数据
                case 'editaddress':

                    $username = $decode_json_arr['userName'];
                    $addrId = $decode_json_arr['addrId'];

                    $map= array();
                    $map['area_address']= $decode_json_data['areaAddr'];
                    $map['name']= $decode_json_data['personName'];
                    $map['phone']= $decode_json_data['phoneNum'];
                    $map['detail_address']= $decode_json_data['detailAddr'];
                    $map['sex'] = $decode_json_data['personSex'];
                
                    $address = M('cw_address');

                        $prize_arr = $address  -> where('addrid='.$addrId)->data($map)-> save();

                        if($prize_arr || $prize_arr==0){
                            $status = 'success';
                        }else{
                            $status = 'fail';

                        }

                        $arr = array('submit' => $status);
                        $msg = json_encode($arr,true);
                        echo $msg;
                        return true;
                    break;

                //添加地址数据
                case 'addaddress':

                    $ini= array();

                    $ini['username']=  $decode_json_arr['userName'];
                    $ini['area_address']= $decode_json_data['areaAddr'];
                    $ini['name']= $decode_json_data['personName'];
                    $ini['phone']= $decode_json_data['phoneNum'];
                    $ini['detail_address']= htmlspecialchars($decode_json_data['detailAddr']);
                    // $ini['sex'] = $decode_json_data['personSex'];

                    $address = M('cw_address');

                    $result = $address -> data($ini) -> add();
                    
                        if($result){
                            $status = 'success';
                        }else{
                            $status = 'fail';

                        }

                        $arr = array('submit' => $status);
                        $msg = json_encode($arr,true);
                        echo $msg;
                        return true;
                    break;

                //删除地址
                case 'deladdress':
                    $addrId = $decode_json_arr['addrid'];
                
                    $prize = M('cw_address');

                        $result = $prize  -> where('addrid='.$addrId)-> delete();

                        if($result){
                            $status = 'success';
                        }else{
                            $status = 'fail';
                        }
                        
                        $arr = array('submit' => $status);
                        $msg = json_encode($arr,true);
                        echo $msg;
                        return true;
                    break;

                //获取数据
                case 'getaddress':
                    $username = $decode_json_data['username'];
                
                    $address = M('cw_address');

                        $prize_arr = $address  -> where('username='.$username)-> select();

                        $msg = json_encode($prize_arr,true);
                        echo $msg;
                        return true;
                    break;

                // 提交订单
                case 'confirm':
                    // 获取当前用户名
                    $username = $decode_json_arr['username'];

                    $User = M('cw_user') -> field('id') -> where("username = '{$username}'") -> find();

                    $uid = $User['id'];

                    $time = time();

                    $ini['user_id'] = $uid;
                    $ini['order_number'] = $time;
                    $ini['consignee_name'] = $decode_json_data['address']['name'];          //收货人姓名
                    $ini['consignee_tel'] = $decode_json_data['address']['phone'];          //收货人联系方式
                    $ini['whole_address'] = $decode_json_data['address']['whole_address'];  //收货人地址
                    $ini['time'] = $time;

                    $ini1['user_id'] = $uid;
                    $ini1['order_number'] = $time;
                    $ini1['consignee_name'] = $decode_json_data['address']['name'];          //收货人姓名
                    $ini1['consignee_tel'] = $decode_json_data['address']['phone'];          //收货人联系方式
                    $ini1['whole_address'] = $decode_json_data['address']['whole_address'];  //收货人地址
                    $ini1['time'] = $time;

                    $prize_ids = '';
                    $prize_amounts = '';
                    $Prize = M('pt_prizes');
                    $status = 1;
                    for ($j=0; $j < count($decode_json_data['goods']); $j++) {
                        $where['id'] = $decode_json_data['goods'][$j]['good'];
                        $stocks = $Prize->where($where)->find();
                        $stock = $stocks['prize_stock'];
                        $rest_nums = $stock - intval($decode_json_data['goods'][$j]['num']);
                        if($rest_nums < 0){
                            $status = 0;
                            for ($i=0; $i < count($decode_json_data['goods']) ; $i++) {
                                $where['id'] = $decode_json_data['goods'][$i]['good'];
                                $prizes = $Prize -> field('exchange_points,prize_stock') -> where($where) ->find();
                                $points_arr[] = intval($prizes['exchange_points']);
                                $amounts_arr[] = intval($decode_json_data['goods'][$i]['num']);
                                
                                if ($prize_ids == '') {
                                    $prize_ids = $decode_json_data['goods'][$i]['good'];
                                }
                                else {
                                    $prize_ids .= ';'.$decode_json_data['goods'][$i]['good'];
                                }
                                if ($prize_amounts == '') {
                                    $prize_amounts = $decode_json_data['goods'][$i]['num'];
                                }
                                else {
                                    $prize_amounts .= ';'.$decode_json_data['goods'][$i]['num'];
                                }
                            }
                            $ini1['user_id'] = $uid;

                            $ini1['prize_ids'] = $prize_ids;

                            $ini1['prize_amounts'] = $prize_amounts;

                            $ini1['exchange_date'] = date('Y-m-d',time());

                            $ini1['is_effective'] = 0;

                            // 计算需要扣除的总积分
                            for ($i=0; $i < count($points_arr); $i++) {
                                $total_amounts += $points_arr[$i]*$amounts_arr[$i];
                            }
                            $ini1['points'] = $total_amounts;
                            M('pt_exchange_orders') -> data($ini1) -> add();
                        }
                    }
                    if ($status == 1) {

                    
                        for ($i=0; $i < count($decode_json_data['goods']) ; $i++) {
                            $where['id'] = $decode_json_data['goods'][$i]['good'];
                            $prizes = $Prize -> field('exchange_points,prize_stock') -> where($where) ->find();
                            $points_arr[] = intval($prizes['exchange_points']);
                            $amounts_arr[] = intval($decode_json_data['goods'][$i]['num']);
                            $prize_stock = $prizes['prize_stock'];
                            $ini1['prize_stock'] = $prize_stock - intval($decode_json_data['goods'][$i]['num']);
                            $Prize->where($where)->save($ini1);
                            if ($prize_ids == '') {
                                $prize_ids = $decode_json_data['goods'][$i]['good'];
                            }
                            else {
                                $prize_ids .= ';'.$decode_json_data['goods'][$i]['good'];
                            }
                            if ($prize_amounts == '') {
                                $prize_amounts = $decode_json_data['goods'][$i]['num'];
                            }
                            else {
                                $prize_amounts .= ';'.$decode_json_data['goods'][$i]['num'];
                            }
                        }   
                        
                        $ini['prize_ids'] = $prize_ids;
                        $ini['prize_amounts'] = $prize_amounts;

                        $ini['exchange_date'] = date('Y-m-d',time());

                        // 计算需要扣除的总积分
                        for ($i=0; $i < count($points_arr); $i++) {
                            $total_amounts += $points_arr[$i]*$amounts_arr[$i];
                        }

                        $user_id = $uid;
                        $amount = $total_amounts;

                        $ini['points'] = $amount;
                        $result = M('pt_exchange_orders') -> data($ini) -> add();

                        if($result && $status != 2){
                            
                            require './Application/Common/Common/includes/point.php';

                            $status = 1;
                        }
                        else {
                            $status = 0;
                        }

                    }

                    $msg = json_encode(array('status'=>$status),true);
                    echo $msg;
                    return true;

                    break;
                // 全部订单
                case 'allorders':
                    // 获取当前用户名
                    $username = $decode_json_arr['user'];
                    $user = M('cw_user');

                    // 确定用户id
                    $user_arr = $user -> where("username='{$username}'") -> find();
                    $uid = $user_arr['id'];

                    $ini['user_id'] = $uid;
                    $ini['is_effective'] = 1;

                    $records = M('pt_exchange_orders') -> field('order_number,order_status,prize_ids,prize_amounts') -> where($ini) -> order('id desc') -> select();
                    // if (!empty($records)) {
                    $info = array();
                        for ($i=0; $i < count($records); $i++) {
                            $info[$i]['order_number'] = $records[$i]['order_number'];
                            $info[$i]['status'] = intval($records[$i]['order_status']);
                            $prize_ids = explode(';',$records[$i]['prize_ids']);
                            $prize_amounts = explode(';',$records[$i]['prize_amounts']);
                            for ($j = 0;$j < count($prize_ids);$j ++){
                                $prize_id = $prize_ids[$j];
                                $prizes = M('pt_prizes') -> where("id = '{$prize_id}'") -> find();
                                $prize_info[$j]['sname'] = $prizes['prize_sname'];
                                $prize_info[$j]['image'] = 'http://app.hanyalicai.com/Public/'.$prizes['prize_image'];
                                $prize_info[$j]['points'] = $prizes['exchange_points'];
                                $prize_info[$j]['amounts'] = $prize_amounts[$j];
                            }
                            $info[$i]['prize_info'] = $prize_info;
                        }

                        $msg = json_encode($info,true);
                    // }                    
                    echo $msg;
                    return true;
                    
                    break;
                // 确认收货
                case 'queren':
                    $order_number = $decode_json_data['order_number'];
                    $ini['order_status'] = 1;
                    $result = M('pt_exchange_orders') -> where("order_number = '{$order_number}'") -> save($ini);
                    if ($result || $result == 0) {
                        $status = 1;
                    }
                    else {
                        $status = 0;
                    }

                    $msg = json_encode(array('status'=>$status),true);
                    echo $msg;
                    return true;

                    break;
                // 查看物流
                case 'logistics':
                    $order_number = $decode_json_arr['order_number'];
                    $records = M('pt_exchange_orders') -> field('logistics_number') -> where("order_number = '{$order_number}'") -> find();
                    $logistics_number = $records['logistics_number'];
                    require './Application/Common/Common/includes/KdApiSearchDemo.php';
                    $logisticResult = getOrderTracesByJson('SF',$logistics_number);
                    $info = json_decode($logisticResult,true);
                    $traces = $info['Traces'];
                    rsort($traces);
                    $info['Traces'] = $traces;
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;

                    break;
/***************************************************投顾之家******************************************************/
                // 投资顾问注册
                case 'adviserRegist':
                    $name = $decode_json_data['name'];
                    $phone = $decode_json_data['phone'];
                    // $password = $decode_json_data['password'];
                    $post = $decode_json_data['post'];
                    $company = $decode_json_data['company'];
                    // 判断该顾问是否已经注册
                    $User = M('ad_user');
                    $once = $User->where("user_name = '{$name}'")->count();
                    if ($once == 0){
                        $ini['user_name'] = $name;
                        $ini['phone_number'] = $phone;
                        $ini['password'] = $password;
                        $ini['post'] = $post;
                        $ini['brach_company'] = $company;
                        $ini['registration_date'] = date('Y-m-d',time());
                        
                        if ($User->data($ini)->add()){
                            $status = 1;
                        }
                        else {
                            $status = 0;
                        }
                    }
                    else {
                        $status = 0;
                    }
                    $msg = json_encode(array('status'=>$status),true);
                    echo $msg;
                    return true;
                    
                    break;
                // 投资顾问注册2
                case 'adviserRegist2':
                    $name = $decode_json_data['name'];
                    $phone = $decode_json_data['phone'];
                    $password = $decode_json_data['password'];
                    $post = $decode_json_data['post'];
                    $company = $decode_json_data['company'];
                    // 判断该顾问是否已经注册
                    $User = M('ad_user');
                    $once = $User->where("phone_number = '{$phone}'")->count();
                    if ($once == 0){
                        $ini['user_name'] = $name;
                        $ini['phone_number'] = $phone;
                        $ini['password'] = $password;
                        $ini['post'] = $post;
                        $ini['brach_company'] = $company;
                        $ini['registration_date'] = date('Y-m-d',time());
                        
                        if ($User->data($ini)->add()){
                            $status = 1;
                        }
                        else {
                            $status = 0;
                        }
                    }
                    else {
                        $status = 0;
                    }
                    $msg = json_encode(array('status'=>$status),true);
                    echo $msg;
                    return true;
                    
                    break;
                // 投资顾问登录
                case 'adviserLogin':
                    $name = $decode_json_data['name'];
                    $phone = $decode_json_data['phone'];
                    // $password = $decode_json_data['password'];
                    if ($name != ''){
                        $User = M('ad_real_user');
                        $user = $User->where("user_name = '{$name}'")->find();
                        // $user = $User->where("phone_number = '{$phone}'")->find();
                        if (!empty($user)){
                            $pwd = $user['phone_number'];
                            // $pwd = $user['password'];
                            if ($pwd == $phone){
                            // if ($password == $pwd){
                                $status = 'ok';
                                unset($user['id']);
                                unset($user['password']);
                                unset($user['registration_date']);
                                $info = array('status'=>$status,'detail'=>$user);
                            }
                            else {
                                $status = 'no';
                                $info = array('status'=>$status);
                            }
                        }
                        else {
                            $status = 'no';
                            $info = array('status'=>$status);
                        }
                    }
                    else {
                        $status = 'no';
                        $info = array('status'=>$status);
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    
                    break;
                // 投资顾问登录2
                case 'adviserLogin2':
                    // $name = $decode_json_data['name'];
                    $phone = $decode_json_data['phone'];
                    $password = $decode_json_data['password'];
                    if ($phone != ''){
                        $User = M('ad_real_user');
                        // $user = $User->where("user_name = '{$name}'")->find();
                        $user = $User->where("phone_number = '{$phone}'")->find();
                        if (!empty($user)){
                            // $pwd = $user['phone_number'];
                            $pwd = $user['password'];
                            // if ($pwd == $phone){
                            if ($password == $pwd){
                                $status = 'ok';
                                unset($user['id']);
                                unset($user['password']);
                                unset($user['registration_date']);
                                $info = array('status'=>$status,'detail'=>$user);
                            }
                            else {
                                $status = 'no';
                                $info = array('status'=>$status);
                            }
                        }
                        else {
                            $status = 'no';
                            $info = array('status'=>$status);
                        }
                    }
                    else {
                        $status = 'no';
                        $info = array('status'=>$status);
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    
                    break;
                // 投资顾问登录3
                case 'adviserLogin3':
                    // $name = $decode_json_data['name'];
                    $phone = $decode_json_data['phone'];
                    $password = $decode_json_data['password'];
                    if ($phone != ''){
                        $User = M('ad_real_user');
                        // $user = $User->where("user_name = '{$name}'")->find();
                        $user = $User->where("phone_number = '{$phone}'")->find();
                        if (!empty($user)){
                            // $pwd = $user['phone_number'];
                            $pwd = $user['password'];
                            // if ($pwd == $phone){
                            if ($password == $pwd){
                                $status = 'ok';
                                unset($user['id']);
                                unset($user['password']);
                                unset($user['registration_date']);
                                $user['head'] = 'http://app.hanyalicai.com/'.$user['head'];
                                $info = array('status'=>$status,'detail'=>$user);
                            }
                            else {
                                $status = 'no';
                                $info = array('status'=>$status);
                            }
                        }
                        else {
                            $status = 'no';
                            $info = array('status'=>$status);
                        }
                    }
                    else {
                        $status = 'no';
                        $info = array('status'=>$status);
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    
                    break;
                // 更改密码
                case 'changePassword':
                    $phone_number = $decode_json_data['phone'];
                    $password = $decode_json_data['oldPassword'];
                    $where['phone_number'] = $phone_number;
                    $where['password'] = $password;                   
                    $user = M('ad_real_user');
                    $result = $user->where($where)->find();
                    if ($result != '') {
                        $newpwd = $decode_json_data['newPassword'];
                        $save['password'] = $newpwd;
                        $result = $user->where("phone_number = '{$phone_number}'")->save($save);
                        if ($result || $result == 0) {
                            $status = 'yes';
                        }
                        else {
                            $status = 'no';
                        }
                    }
                    else {
                        $status = 'wrong';
                    }
                    $msg = json_encode(array('status'=>$status),true);
                    echo $msg;
                    return true;
                    break;
                // 产品中心
                // 全部产品
                case 'product':
                    $page = $decode_json_arr['page'];

                    $start = ($page-1)*4;

                    $end = $page*4;

                    $mysql_server_name = '103.242.175.34:6666';
        
                    $mysql_username = 'root';
                
                    $mysql_password = '256wty@%^WTY';

                    $mysql_database = 'app';
                    
                    $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                    
                    mysql_query("set names 'utf8'");
                    
                    mysql_select_db($mysql_database);

                    $sql = "select count(*) as `nums` from `bom_coefficient`";

                    $result = mysql_query($sql,$link);

                    $row = mysql_fetch_assoc($result);

                    if ($row['nums'] < $end) {
                        $status = 'end';
                    }
                    else {
                        $status = 'countinue';
                    }
                    $sql = "select * from `bom_coefficient` limit {$start},4";
                    
                    $result = mysql_query($sql,$link);
                     
                    while ($row = mysql_fetch_assoc($result)) {
                        $info[] = $row;
                    }

                    for ($i=0;$i<count($info);$i++){
                        $product_scale = intval($info[$i]['product_scale']);
                        $target_money = intval($info[$i]['target_money']);
                        $info[$i]['raise_progress'] = (sprintf( "%.3f ",$target_money/$product_scale)*100).'%';
                        if ($info[$i]['announcement_date'] == null) {
                            $info[$i]['announcement_date'] = '';
                        }
                        if ($info[$i]['announcement_enddate'] == null) {
                            $info[$i]['announcement_enddate'] = '';
                        }
                    }

                    $arr['info'] = $info;
                    $arr['status'] = $status;
                    

                    // for ($i=0; $i < count($info); $i++) { 
                    //  # code...
                    // }
                    $msg = json_encode($arr,true);
                    echo $msg;
                    return true;

                    break;
                // 募集中产品
                case 'mujizhong':
                    $mysql_server_name = '103.242.175.34:6666';
        
                    $mysql_username = 'root';
                
                    $mysql_password = '256wty@%^WTY';

                    $mysql_database = 'app';
                    
                    $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                    
                    mysql_query("set names 'utf8'");
                    
                    mysql_select_db($mysql_database);
                    
                    $sql = "select * from `bom_coefficient` where `product_state` = '募集中'";
                    
                    $result = mysql_query($sql,$link);
                    
                    while ($row = mysql_fetch_assoc($result)) {
                        $info[] = $row;
                    }

                    for ($i=0;$i<count($info);$i++){
                        $product_scale = intval($info[$i]['product_scale']);
                        $target_money = intval($info[$i]['target_money']);
                        $info[$i]['raise_progress'] = (sprintf( "%.3f ",$target_money/$product_scale)*100).'%';
                        if ($info[$i]['announcement_date'] == null) {
                            $info[$i]['announcement_date'] = '';
                        }
                        if ($info[$i]['announcement_enddate'] == null) {
                            $info[$i]['announcement_enddate'] = '';
                        }
                        $key = $info[$i]['product_number'];
                        $arr[$key] = $info[$i];
                    }
                    sort($arr);
                    $msg = json_encode($arr,true);
                    echo $msg;
                    return true;

                    break;
                // 募集结束产品
                case 'mujiwan':
                    $mysql_server_name = '103.242.175.34:6666';
        
                    $mysql_username = 'root';
                
                    $mysql_password = '256wty@%^WTY';

                    $mysql_database = 'app';
                    
                    $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                    
                    mysql_query("set names 'utf8'");
                    
                    mysql_select_db($mysql_database);
                    
                    $sql = "select * from `bom_coefficient` where `product_state` = '已结束'";
                    
                    $result = mysql_query($sql,$link);
                    
                    while ($row = mysql_fetch_assoc($result)) {
                        $info[] = $row;
                    }

                    for ($i=0;$i<count($info);$i++){
                        $product_scale = intval($info[$i]['product_scale']);
                        $target_money = intval($info[$i]['target_money']);
                        $info[$i]['raise_progress'] = (sprintf( "%.3f ",$target_money/$product_scale)*100).'%';
                        if ($info[$i]['announcement_date'] == null) {
                            $info[$i]['announcement_date'] = '';
                        }
                        if ($info[$i]['announcement_enddate'] == null) {
                            $info[$i]['announcement_enddate'] = '';
                        }
                        $key = $info[$i]['product_number'];
                        $arr[$key] = $info[$i];
                    }
                    sort($arr);
                    $msg = json_encode($arr,true);
                    echo $msg;
                    return true;

                    break;
                // 全部产品（新版本）
                case 'product1':
                    $Shuju = M('Shuju','yunying_','DB_CONFIG2');
                    $info = $Shuju->select();
                    foreach ($info as $key => $value) {
                        $value['raise_progress'] = (sprintf( "%.3f ",$value['target_money']/$value['product_scale'])*100).'%';
                        $info[$key] = $value;
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 募集中的产品（新版本）
                case 'mujizhong1':
                    $where['product_state'] = '募集中';
                    $Shuju = M('Shuju','yunying_','DB_CONFIG2');
                    $info = $Shuju->where($where)->select();
                    foreach ($info as $key => $value) {
                        $value['raise_progress'] = (sprintf( "%.3f ",$value['target_money']/$value['product_scale'])*100).'%';
                        $info[$key] = $value;
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 募集结束的产品（新版本）
                case 'mujiwan1':
                    $where['product_state'] = '已结束';
                    $Shuju = M('Shuju','yunying_','DB_CONFIG2');
                    $info = $Shuju->where($where)->limit('30')->select();
                    foreach ($info as $key => $value) {
                        $value['raise_progress'] = '100%';
                        $info[$key] = $value;
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 精选产品
                case 'newproduct':
                    $where['product_state'] = '募集中';
                    $Shuju = M('Shuju','yunying_','DB_CONFIG2');
                    $info = $Shuju->where($where)->select();
                    foreach ($info as $key => $value) {
                        $value['raise_progress'] = (sprintf( "%.3f ",$value['target_money']/$value['product_scale'])*100).'%';
                        $info[$key] = $value;
                    }
                    $msg = json_encode(array('info'=>$info),true);
                    echo $msg;
                    return true;
                    break;
                // 当前投资顾问的客户信息
                case 'custmerList':
                    // 获取当前投资顾问用户名
                    $phone = $decode_json_arr['phone'];

                    $mysql_server_name = '103.242.175.34:6666';
        
                    $mysql_username = 'root';
                
                    $mysql_password = '256wty@%^WTY';

                    $mysql_database = 'app';
                    
                    $link = @mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die('erro connect');
                    
                    mysql_query("set names 'utf8'");
                    
                    mysql_select_db($mysql_database);
                    
                    $sql = "select * from `pro_info` where `consultant_tel` like '%{$phone}%' order by `expiration_date` desc,`investor_name` asc";
                    
                    $result = mysql_query($sql,$link);
                    
                    while ($row = mysql_fetch_assoc($result)) {
                        $info[] = $row;
                    }
                    foreach ($info as $key => $value) {
                        if ($value['contract_number'] == null) {
                            $value['contract_number'] = '';
                        }
                        $info[$key] = $value;
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 活动预告
                case 'activity':
                    $info = M('cw_activity')->where('is_show = 1')->select();
                    for ($i=0; $i < count($info); $i++) { 
                        $info[$i]['activity_picture'] = 'http://app.hanyalicai.com/Public/'.$info[$i]['activity_picture'];
                        $info[$i]['activity_image'] = 'http://app.hanyalicai.com/Public/'.$info[$i]['activity_image2'];
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 团建活动
                case 'team':
                    $info = M('cw_activity')->field('activity_name,activity_image,activity_date,wechat_link')->where('activity_type = 1 and is_show = 1')->select();
                    for ($i=0; $i < count($info); $i++) { 
                        $info[$i]['activity_image'] = 'http://app.hanyalicai.com/Public/'.$info[$i]['activity_image'];
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 每日播报
                case 'report':
                	// 获取每日战报数据表中的最近日期
                	$Report = M('cw_report');
		        	$nearly_report_date = $Report->field('report_date')->max('report_date');
		            $info = $Report->where("report_date = '{$nearly_report_date}'")->find();
		            $time = strtotime($nearly_report_date);
            		$month = date("m",$time);
                    $title = array(
                        '昨日成交金额',
                        "{$month}月累计成交情况",
                        '在售产品募集进度',
                        '项目运营事项',
                        '兑付项目情况',
                        '特殊情况说明'
                    );
                    $info['title'] = $title;
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 每日播报图片
                case 'reportPicture':
                    $type = $decode_json_arr['type'];
                    $report = M('cw_report')->order("report_date desc")->find();
                    switch ($type) {
                        case '0':
                            $image = $report['image1'];
                            break;
                        case '1':
                            $image = $report['image2'];
                            break;
                        case '2':
                            $image = $report['image3'];
                            break;
                        case '3':
                            $image = $report['image4'];
                            break;
                        case '4':
                            $image = $report['image5'];
                            break;
                        default:
                            $image = "";
                            break;
                    }
                    $info = array('image' => $image);
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 投顾之家新闻
                case 'adviserNews':
                	$info = M('cw_information')->where("type = 'adviser'")->find();
                    $arr['news'] = $info['msg'];
                    $msg = json_encode($arr,true);
                    echo $msg;
                    return true;
                    break;
                // 首页新闻
                case 'indexNews':
                	$info = M('cw_information')->where("type = 'index'")->find();
                    $arr['news'] = $info['msg'];
                    $msg = json_encode($arr,true);
                    echo $msg;
                    return true;
                    break;
                // 顾问头像上传
                case 'ttt':
                    $decode_json_image = $decode_json_arr['image'];
                    $json_decode_image = json_decode($decode_json_image,true);
                    $img = base64_decode($json_decode_image['image']);
                    $path = "Public/Uploads/Images/adviser/head/".md5(uniqid(rand())).".jpg";
                    if (file_put_contents($path, $img)){
                        // 将图片地址保存至数据库中
                        $adviser_tel = $decode_json_arr['adviserNum'];
                        $save_ini['head'] = $path;
                        $result = M('ad_real_user')->where("phone_number = '{$adviser_tel}'")->save($save_ini);
                        if ($result || $result == 0) {
                            $status = 'yes';
                        }
                        else {
                            $status = 'no';
                        }
                    }
                    else {
                        $status = 'no';
                    }
                    $msg = json_encode(array('status'=>$status,'head'=>'http://app.hanyalicai.com/'.$path),true);
                    echo $msg;
                    return true;
                    break;
                // 首页轮播图
                case 'lunbo1':
                	$banner_arr = M('cw_banner')->where("type = 1 and is_effective = 1")->select();
		            for ($i=0;$i<count($banner_arr);$i++){
		                $image = $banner_arr[$i]['image'];
		                $info[] = $image;
		            }
                    
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                case 'lunbo2':
                    $banner_arr = array(
                        'http://app.hanyalicai.com/Public/Uploads/Images/banners/banner1.png',
                        'http://app.hanyalicai.com/Public/Uploads/Images/banners/banner2.png',
                        'http://app.hanyalicai.com/Public/Uploads/Images/banners/banner3.png',
                        // 'http://app.hanyalicai.com/Public/Uploads/Images/banners/banner4.png'
                    );
                    $msg = json_encode($banner_arr,true);
                    echo $msg;
                    return true;
                    break;
                // 房产信息
                case 'fangchan':
                    $FC = M('Shuju','fangchan_',DB_CONFIG3);
                    $fangchan = $FC->select();
                    foreach ($fangchan as $key => $value) {
                        $picAry = explode(",",$value["picary"]);
                        $huxingAry = explode(",",$value["huxingary"]);
                        $huxingDetailAry = explode(",",$value["huxingdetailary"]);
                        foreach ($picAry as $key1 => $value1) {
                            $value1 = "http://app.hanyalicai.com/Public".$value1;
                            $picAry[$key1] = $value1;
                        }
                        foreach ($huxingAry as $key2 => $value2) {
                            $value2 = "http://app.hanyalicai.com/Public".$value2;
                            $huxingAry[$key2] = $value2;
                        }
                        foreach ($huxingDetailAry as $key3 => $value3) {
                            $value3 = "http://app.hanyalicai.com/Public".$value3;
                            $huxingDetailAry[$key3] = $value3;
                        }
                        $value["mainpic"] = "http://app.hanyalicai.com/Public".$value["mainpic"];
                        $value["picary"] = $picAry;
                        $value["huxingary"] = $huxingAry;
                        $value["huxingdetailary"] = $huxingDetailAry;
                        $info[$key] = $value;
                    }
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 服务问答
                case 'service':
                    $info = M("cw_service")->field("question,answer")->order("order_id asc")->select();
                    $msg = json_encode($info,true);
                    echo $msg;
                    return true;
                    break;
                // 便于测试
                default:
                    $test = M('cw_test');
                    $ini['json'] = $ios_return_json;
                    $result = $test->data($ini)->add();
            }
        }
    }
}