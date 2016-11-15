<?php
    namespace Admin\Controller;
    use Admin\Controller\CommonController;
    class RecordController extends CommonController{
        public function index(){
            
            //获取参数
            $n = I('get.n');
            $k = I('get.k');
            $num = !empty($n) ? $n : 10;//每页显示的数量
            $keyword = !empty($k) ? $k : '';//检索的关键字
            if($keyword != ''){
                $where['username']=array('like',"%keyword%");
            }
            
            $record = M('cw_record');
            
            //读取总的条数
            $count = $record->where($where)->count();
            //创建分页对象
            $page = new \Think\Page($count,$num);
            //获取limit参数
            $limit=$page->firstRow.','.$page->listRows;
            
            $records = $record -> field('r.*,u.username,u.realname') -> alias('r') -> join('cw_user as u on r.user_id = u.id','left') -> limit($limit) -> select();
            
            //获取页码显示的信息字符串
            $pages = $page->show();

            $this -> assign('count',$count);
            
            $this -> assign('pages',$pages);
            
            $this -> assign('records',$records);
            
            $this -> display();
        }
    }