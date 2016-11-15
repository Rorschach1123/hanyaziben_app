<?php
namespace Admin\Controller;
use Think\Controller;
class IosController extends Controller{
    public function index(){
        $opt = I('get.opt');
        // 普通实例化
        function instantiation($data,$where='',$limit=10){
            $m = M($data);
            $rs = $m->where($where)->limit($limit)->select();
            return $rs;
        }
        // 新闻类实例化
        function newscomment($where,$limit=10){
            $news = M('cw_news');
            $rs = $news->field('id,title,author,content,pic_name,addtime')->where($where)->limit($limit)->select();
            $info = array();
            foreach ($rs as $vo){
                $ini['cw_news_id'] = $vo['id'];
                $comment = M('cw_comment');
                $comments = $comment->alias('c')->field('c.id,c.address,u.username,c.comment_text,c.addtime')->join('cw_user as u on c.cw_user_id = u.id','left')->where($ini)->select();
                $vo['comment'][] = $comments;
                $info[''] = $vo;
            }
            return $info;
        }
        
        switch ($opt){
            // 新闻模块
            // 1.瀚亚新闻
            case 'hanyanews':
                $map['cw_newscate_id'] = 5;
                $info = newscomment($map);
                break;
            // 2.动态
            case 'dynamic':
                $map['cw_newscate_id'] = 6;
                $info = newscomment($map);
//                 echo '<pre>';
//                 print_r($info);
//                 echo '</pre>';
                break;
            // 3.瀚亚达人
            case 'hanyadaren':
                $map['cw_newscate_id'] = 7;
                $info = newscomment($map);
                break;
            // 4.产品及服务
            case 'products':
                $map['cw_newscate_id'] = 8;
                $info = newscomment($map);
                break;
            // 用户模块
            case 'user':
                $info = instantiation('cw_user');
                break;
            // 功能模块
            // 1.预约理财师
            case 'book':
                $info = instantiation('cw_book');
                break;
            // 2.资产查询
            case 'asset':
                $info = instantiation('cw_asset');
            // 首页服务
            //　1.公告
            case 'notice':
                $info = instantiation('cw_notice');
                break;
            // 2.banner图片
            case 'banner':
                $info = instantiation('cw_images');
                $narr = array();
                foreach ($info as $vo){
                    $images = explode('##', $vo['images']);
                    $vo['images'] = '\/Public\/Uploads\/Images\/'.$images;
                    $narr[] = $vo;
                }
                unset($info);
                $info = $narr;
                break;
            case 'jjdt':
                $economic = M('Addonarticle','dede_','DB_CONFIG2');
                $ini['typeid'] = 41;
                $info = $economic->field('aid,body')->where($ini)->order('aid desc')->limit(13)->select();
                foreach ($info as $k=>$vo){
                    $body = strip_tags($vo['body']);
                    $info[$k]['body']=$body;
                }
                unset($info['5']);
                unset($info['6']);
                unset($info['10']);
//                 echo '<pre>';
//                 print_r($info);
//                 exit();
                break;
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
                $info = $arr;
//                 echo '<pre>';
//                 print_r($info);
//                 exit();
                break;
            default:
                $info = ' unknow ';
        }
        $info_json = json_encode($info,true);
//         $info_json_sub = substr($info_json, 1, -1);
        $this->assign('info',$info_json);
        $this->display();
    }
}