<?php
namespace Admin\Controller;
use Think\Controller;
class IpadController extends Controller{
    public function api(){
        // Kiki's api for iPad
        header("Content-type:application/json");
        if (IS_POST){
            $ios_return_json = file_get_contents("php://input");
        
            $decode_json_arr = json_decode($ios_return_json,true);
            
//             if (isset($decode_json_arr["data"])){
//                 $decode_json_data = json_decode($decode_json_arr["data"],true);
//             }
//             else {
//                 $decode_json_data = "";
//             }
            
            $decode_json_opt = isset($decode_json_arr["opt"])?$decode_json_arr["opt"]:"";
            switch ($decode_json_opt){
                // 指南图片
                case "zhinan":
                    $resources = M("pad_resources")->field("resource_url")->where("type = 'zhinan'")->select();
                    foreach ($resources as $value){
                        $url = "http://app.hanyalicai.com/Public".$value["resource_url"];
                        $arr[] = $url;
                    }
                    $return_json = json_encode($arr,true);
                    echo $return_json;
                    return true;
                    break;
                // 季度报告
                case "jidureport":
                    $resources = M("pad_resources")->field("resource_url")->where("type = 'jidureport'")->select();
                    foreach ($resources as $value){
                        $url = "http://app.hanyalicai.com/Public".$value["resource_url"];
                        $arr[] = $url;
                    }
                    $return_json = json_encode($arr,true);
                    echo $return_json;
                    return true;
                    break;
                case "":
                    break;
            }
            
        }
    }
}