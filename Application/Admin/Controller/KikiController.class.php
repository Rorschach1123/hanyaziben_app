<?php
namespace Admin\Controller;
// use Admin\Controller\CommonController;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
// class KikiController extends CommonController{
class KikiController extends Controller{
    // 查看所有已注册用户的积分余量
    public function rest(){
        
        $where['user_id'] = array('not in','0,8');
        $where['is_effective'] = 1;
        $sum = M('pt_investment_points')->where($where)->sum('rest_points');
        echo $sum;
        
        exit();
    }
    // 过期积分处理
    public function overdue(){
        $now = date("Y-m-d");
        $where1['expiration_date'] = array("lt",$now);
        $where1['user_id'] = array("not in","8,79");
        $where1['is_effective'] = 1;
        $InvestmentPoints = M("pt_investment_points");
        $info = $InvestmentPoints->where($where1)->select();
        
        for ($i=0;$i<count($info);$i++){
            $id = $info[$i]['id'];
            $where2['id_number'] = $info[$i]['id_number'];
            $points = $info[$i]['points']*100;
            $where2['investment_amount'] = $points;
            $where2['expiration_date'] = $info[$i]['expiration_date'];
            $where2['contract_states'] = "合同完结";
            $info2 = M('Info','pro_','DB_CONFIG1')->field("contract_states")->where($where2)->find();
            if (!empty($info2)){
                $data1['id'] = $id;
                $data1['is_effective'] = 0;
                if ($InvestmentPoints->data($data1)->save()){
                    echo "<br>id：{$id}&nbsp;&nbsp;更新成功<br>";
                }
                else {
                    echo "<br>id：{$id}&nbsp;&nbsp;更新失败<br>";
                }
                $info3 = $InvestmentPoints->field("user_id,rest_points,expiration_date")->where("id = '{$id}'")->find();
                $data2["user_id"] = $info3["user_id"];
                $data2['expired_points'] = $info3["rest_points"];
                $data2['expired_date'] = $info3['expiration_date'];
                $data2['time'] = strtotime($info3['expiration_date']);
                $OverduePoints = M("pt_overdue_points");
                if($OverduePoints->data($data2)->add()){
                    echo "新增过期积分成功";
                }
                else {
                    echo "新增过期积分失败";
                }
                //                     echo '<pre>';
                //                     print_r($data1);
                //                     print_r($data2);
                echo '<br><hr>';
                unset($data1);
                unset($data2);
            }
            else {
                echo "<br>id：{$id}&nbsp;&nbsp;无需更新<br><hr>";
            }
        }
        exit();
    }
    // 积分更新操作
    public function update(){
        $User = M("cw_user");
        $InvestmentPoints = M("pt_investment_points");
        $OverduePoints = M("pt_overdue_points");
        $Info = M('Info','pro_','DB_CONFIG1');
        $userArr = $User->field("id,idnumber")->select();
        foreach ($userArr as $value1){
            if (!empty($value1['idnumber'])){
                $user_id = $value1['id'];
                $where1['id_number'] = $value1['idnumber'];
                $infoArr1 = $Info->field("id_number,investment_amount,expiration_date")->where($where1)->select();
                if (!empty($infoArr1)){
                    foreach ($infoArr1 as $value2){
                        $points = $value2['investment_amount']/100;
                        $iArr1[] = array("id_number"=>$value2['id_number'],"points"=>$points,"expiration_date"=>$value2['expiration_date']);
                    }
                    
                    
                    echo '<pre>';
                    print_r($iArr1);
                    echo '<hr>';
                    
                    
                    
                    $iArr2 = $InvestmentPoints->field("id_number,points,expiration_date")->where($where1)->select();
                    
                    print_r($iArr2);
                    echo '<hr color="red">';
                    
                    $iArr3 = array_diff($iArr1,$iArr2);
                    
                    if (!empty($iArr3)){
                        echo '<span style="font-size:30px;color:blue;font-weight:bold">需要更新</span><br>';
                        print_r($iArr3);
                        
                        foreach ($iArr3 as $value3){
                            $where2['id_number'] = $value3['id_number'];
                            $investment_amount = $value3['points']*100;
                            $where2['investment_amount'] = $investment_amount;
                            $where2['expiration_date'] = $value3['expiration_date'];
                            $infoArr2 = $Info->field("product_name,contract_number,arrival_date,contract_states")->where($where2)->find();
                            unset($where2["investment_amount"]);
                            $where2["user_id"] = $user_id;
                            $where2["product_name"] = $infoArr2['product_name'];
                            $contract_number = $infoArr2['contract_number'];
                            if ($contract_number == null){
                                $contract_number = '';
                            }
                            $where2["contract_number"] = $contract_number;
                            $where2["points"] = $value3['points'];
                            $where2["rest_points"] = $value3['points'];
                            $where2["arrival_date"] = $infoArr2["arrival_date"];
                            $where2["time"] = strtotime($infoArr2["arrival_date"]);
                            $where2["update_date"] = date("Y-m-d",time());
                            if ($infoArr2['contract_states'] == "合同执行"){
                                $where2["is_effective"] = 1;
                            }
                            else {
                                $where2["is_effective"] = 0;
                                print_r($where2);
                                $data["user_id"] = $user_id;
                                $data['id_number'] = $value3['id_number'];
                                $data['expired_points'] = $value3['points'];
                                $data["expired_date"] = $value3['expiration_date'];
                                $data["time"] = strtotime($value3['expiration_date']);
                                print_r($data);
                                if ($OverduePoints->data($data)->add()){
                                    echo '<br>新增过期积分成功<br>';
                                }
                                else {
                                    echo '<br>新增过期积分失败<br>';
                                }
                            }
                            
                            if ($InvestmentPoints->data($where2)->add()){
                                echo '<br>新增产品积分成功<br>';
                            }
                            else {
                                echo '<br>新增产品积分失败<br>';
                            }
                            
                            unset($where2);
                            unset($data);
                        }
                        
                    }
                    else {
                        echo '无需更新';
                    }
                    echo '<hr color="red">';
                    
                    unset($iArr1);
                    unset($iArr2);
                    unset($iArr);
//                     exit();
                }
            }
            unset($where1);
        }
    }
    
    
    
    
    
}