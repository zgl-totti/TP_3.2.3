<?php
namespace Mobile\Controller;
use Think\Controller;
class IntegralController extends Controller
{
    public function showlist(){
        $integralgoods = D('Category')->getGoodsList();
        $this->assign('integralgoods',$integralgoods);
        $this->display();
    }

    public function detail(){
        /*根据传过来的gid找到商品相关信息*/
        $list = D('Goods')->GoodsDetail(I('get.gid'));
        $goodspic =M('Goods_pic')->where(array("gid"=>I('get.gid')))->select();
        /*同类推荐(由于商品少的原因  不用写limit 正常只要四个)*/
        $integralgoods = D('Category')->getGoodsList();
        $comment = D('Goods')->GoodsComment(I('get.gid'));

        /*查找用户是否收藏过商品*/
        $find = D('Collect')->where(array("mid"=>session('mid'),"gid"=>I('get.gid')))->field("id")->find();
        $this->assign('cid',$find['id']);
        $this->assign('gid',I('get.gid'));
        $this->assign('empty',"<h1 style='color:red;'>暂无相关评价!</h1>");
        $this->assign('comment',$comment);
        $this->assign('integral',$integralgoods);
        $this->assign('goodspic',$goodspic);
        $this->assign('list',$list);
        $this->assign('mid',session('mid'));
        $this->display();
    }

    public function integral(){
        $member = D('Member')->where(array("id"=>session('mid')))->field('credit')->find();
        $where['order_status']=array('in','2,3,4,5');
        $where['mid']=session('mid');
        $order = M('Order')->where($where)->field('addtime,order_price')->select();
        $this->assign('order',$order);
        $this->assign('member',$member);
        $this->display();
    }
    //立即购买tologin
    public function tologin(){
        $gid=I("get.gid");
        session("gid",$gid);
        $this->display();
    }

    public function collect($gid){
        $mid = session('mid');
        /*先查找gid 和 mid 存不存在*/
        $find = D('Collect')->where(array("mid"=>$mid,"gid"=>$gid))->find();
        if($find != ''){
            $this->ajaxReturn(array("status"=>2,"msg"=>"商品已收藏"));
        }
        else {
            $collect = D('Collect')->addCollect($gid, $mid);
            if ($collect) {$this->ajaxReturn(array("status" => 1, "msg" => "收藏成功"));}
            else {$this->ajaxReturn(array("status" => 0, "msg" => "收藏失败"));}
        }
    }


}