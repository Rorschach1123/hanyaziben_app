<?php
    namespace Admin\Controller;
    use Think\Controller;
    class WeeklyController extends Controller{
        public function form(){
            header("Content-type:text/html;charset=utf-8");
            $resources = M("pad_resources")->field("resource_url")->where("type = 'zhinan'")->select();
            foreach ($resources as $value){
                $url = "http://app.hanyalicai.com/Public".$value["resource_url"];
                $arr[] = $url;
            }
            $return_json = json_encode($arr,true);
            echo $return_json;
            exit();
            $info = M("cw_service")->field("question,answer")->order("order_id asc")->select();
            echo '<pre>';
            print_r($info);
            exit();
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
            echo '<pre>';
            print_r($info);
        }
    }