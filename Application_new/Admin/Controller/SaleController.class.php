<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use \Think\Page;
class SaleController extends BaseController{
    //展示列表
    public function showlist(){
        $goods=M('Goods');
        $activity=I('get.activity');
        $where['activity']=I('get.activity');
        //dump($where);
        if(IS_POST){
            $goods=M('Goods');
            $goodsname=I('post.goodsname');
            $this->assign('scwords',$goodsname);
            $where['activity']=I('get.activity');
            //dump(I('get.activity'));
            $where['goodsname']=array('like',"%$goodsname%");
            $count=$goods->where($where)->count();// 查询满足要求的总记录数
            //dump($count);
            $page=new Page($count,1);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show=$page->show();// 分页显示输出
            $goodslist=$goods->alias('g')->join('shop_category c ON g.cid= c.id')->join('shop_brand b ON g.bid= b.id')->field('g.*,catename,bname')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $count=$goods->where($where)->count();// 查询满足要求的总记录数
            $page=new Page($count,1);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show=$page->show();// 分页显示输出
            $goodslist=$goods->alias('g')->join('shop_category c ON g.cid= c.id')->join('shop_brand b ON g.bid= b.id')->field('g.*,catename,bname')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        }
        //dump($count);

        $this->assign('page',$show);// 赋值分页输出
        //$this->assign('firstRow',$page->firstRow);// 赋值分页输出
        $this->assign('goods',$goodslist);
        $this->display();
    }

    //查看抢购商品详情
    public function addlist(){
        $where['id']=I('get.gid');
        /*dump($where);
        exit;*/
        $goods=M('Goods')->where($where)->select();
        $this->assign('goods',$goods);
        $this->display();
    }
    //抢购
    public function qianggou(){
        $active=M('Activity');
        if(IS_POST){
            $starttime=I('post.starttime');
            $where['starttime']=strtotime($starttime);
            $this->assign('scwords',$starttime);
            $count= $active->where($where)->count();// 查询满足要求的总记录数
            $page = new Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $page->show();// 分页显示输出
            $list=$active->alias('a')->join('shop_goods g ON g.id= a.gid')->field('a.id,gid,goodsname,pic,starttime,endtime,addvote')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $count=$active->count();// 查询满足要求的总记录数
            $page=new Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show=$page->show();// 分页显示输出
            $list=$active->alias('a')->join('shop_goods g ON g.id= a.gid')->field('a.id,gid,goodsname,pic,starttime,endtime,addvote')->limit($page->firstRow.','.$page->listRows)->select();
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('firstRow',$page->firstRow);// 赋值分页输出
        $this->display(); // 输出模板
    }



    public function edictlist()
    {
        if (IS_GET) {
            $active = M('Activity');
            $id = I('get.id');
            $gid = I('get.gid');
            $where['a.id'] = $id;
            $list = $active->alias('a')->join('shop_goods g ON g.id= a.gid')->field('goodsname,pic,a.*')->where($where)->select();
            $this->assign('list', $list);
            $this->display(); // 输出模板
        }
        if (IS_POST) {
            $active = M('Activity');
            $map['id'] = I('post.id');
            $data['starttime'] = strtotime((I('post.starttime')));
            $data['endtime'] = strtotime(I('post.endtime'));
            if ($data['starttime'] > $data['endtime']) {
                $this->ajaxReturn(array('status' => 'error', 'msg' => '截止时间小于活动开始时间'));
            }
            $info = $active->where($map)->save($data);
            if ($info) {
                $this->ajaxReturn(array('status' => 'ok', 'msg' => '编辑完成'));
            } else {
                $this->ajaxReturn(array('status' => 'error', 'msg' => '编辑失败'));

            }
        }
    }
        //禁用投票
        public function disabled($id){
            $activity=M("Activity");
            $where["id"]=$id;
            $data["addvote"]=0;
            //执行修改
            $info=$activity->where($where)->save($data);
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"禁用成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"禁用失败"));
            }
        }
        //启用投票
        public function enabled($id){
            $activity=M("Activity");
            $where["id"]=$id;
            $data["addvote"]=1;
            //执行修改
            $info=$activity->where($where)->save($data);
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"启用成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"启用失败"));
            }


    }





}