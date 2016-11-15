<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class BannerController extends CommonController{
    // 首页轮播图
    public function index(){
        if (IS_POST){
            
            $status = I("post.");
            
            $Banner = M('cw_banner');
            
        
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/'; // 设置附件上传根目录
            $upload->savePath  =     '/Uploads/Images/banner/index/'; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                //                 $this->error($upload->getError());
                for($i=0;$i<4;$i++){
                    $data['id'] = $i+1;
                    $data['is_effective'] = $status[$i];
                    $Banner->data($data)->save();
                    unset($data);
                }
                $this->success('没有轮播图被上传',$_SERVER['HTTP_REFERER'],1);
            }else{// 上传成功 获取上传文件信息
                if ($info[0] != ''){
                    $data['id'] = 1;
                    $data['is_effective'] = $status[0];
                    $data['image'] = "http://app.hanyalicai.com/Public".$info[0]['savepath'].$info[0]['savename'];
                    $Banner->data($data)->save();
                    unset($data['image']);
                }
                if ($info[1] != ''){
                    $data['id'] = 2;
                    $data['is_effective'] = $status[1];
                    $data['image'] = "http://app.hanyalicai.com/Public".$info[1]['savepath'].$info[1]['savename'];
                    $Banner->data($data)->save();
                    unset($data['image']);
                }
                if ($info[2] != ''){
                    $data['id'] = 3;
                    $data['is_effective'] = $status[2];
                    $data['image'] = "http://app.hanyalicai.com/Public".$info[2]['savepath'].$info[2]['savename'];
                    $Banner->data($data)->save();
                    unset($data['image']);
                }
                if ($info[3] != ''){
                   $data['id'] = 4;
                   $data['is_effective'] = $status[3];
                    $data['image'] = "http://app.hanyalicai.com/Public".$info[3]['savepath'].$info[3]['savename'];
                    $Banner->data($data)->save();
                    unset($data['image']);
                }
                $this->success('轮播图更新成功',U('Admin/Banner/index'),1);
            }
            exit();
        }
        else {
            $banner_arr = M('cw_banner')->where("type = 1")->select();
            for ($i=0;$i<count($banner_arr);$i++){
                $image = $banner_arr[$i]['image'];
                $is_effective = $banner_arr[$i]['is_effective'];
                $info[] = array('image'=>$image,'is_effective'=>$is_effective);
            }
            
            $this->assign('info',$info);
            $this->display();
        }
    }
    public function adviser(){
    if (IS_POST){
                
            $Banner = M('cw_banner');
            
        
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/'; // 设置附件上传根目录
            $upload->savePath  =     '/Uploads/Images/adviser/'; // 设置附件上传（子）目录
            $upload->autoSub = false;
            for ($i=0;$i<3;$i++){
                $j = $i+1;
                if ($_FILES["banner$j"]['name'] != ''){
                    $name = "banner{$j}";
                    $upload->saveName  =  $name;
                    $info   =   $upload->uploadOne($_FILES[$name]);
                    if (!$info){
                        $this->show("轮播图{$j}上传失败");
                    }
                    else {
                        if ($info != ''){
                            $data['id'] = $i+5;
                            $data['image'] = "http://app.hanyalicai.com/Public".$info['savepath'].$info['savename'];
                            $Banner->data($data)->save();
                            unset($data['image']);
                        }
                    }
                }
                else {
                }
            }
            $this->success('轮播图更新成功',U('Admin/Banner/adviser'),1);
            exit();
        }
        else {
            $banner_arr = M('cw_banner')->where("type = 2")->select();
            for ($i=0;$i<count($banner_arr);$i++){
                $image = $banner_arr[$i]['image'];
                $info[] = $image;
            }
            $this->assign('info',$info);
            $this->display();
        }
    }
}