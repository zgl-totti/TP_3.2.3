<?php
namespace Mobile\Controller;
use Think\Controller;
class DetailController extends Controller{
    //详情页展示
    public function detail(){
        //当用户登陆 访问商品页的时候 将商品加到足迹中
        $gid= I('get.gid');
        if(session("mid") && session("mid")>0){
            //当用户登陆 访问商品页的时候 将商品加到足迹中
            $where['mid']=session('mid');
            $where['gid']=I('get.gid');
            $find = M('Foot')->where($where)->find();
            if($find==''){
                //证明足迹表中不存在
                $where["addtime"]=time();
                M('Foot')->add($where);
            }else{
                //足迹表中存在,更改一下时间
                $time["addtime"]=time();
                M('Foot')->where($where)->save($time);}
        }else{
            session("goods_id.$gid",$gid);            //会员未登录状态，存入session中
        }





        //删除session中的数据【立即购买时存放的信息】
        session("gid",null);
        //删除session中的数据结束
        $gid = I('get.gid');        //获取到gid
        $goods = D('Goods');        //实例化goods
        $list = M('Goods_pic')->where(array("gid"=>$gid))->field('picname')->select();
        $detail = $goods->GoodsDetail($gid);
        //商品推荐
        $map['cid']=array('in',D('Category')->getCateList("{$detail['cid']}"));
        $tj = $goods->where($map)->limit(4)->select();
        //商品评价
        $comment = $goods->GoodsComment($gid);
        /*保存商品id*/
        session('gid',$gid);
        $get = session('gid');
        /*查找用户是否收藏过商品*/
        $find = D('Collect')->where(array("mid"=>session('mid'),"gid"=>$gid))->field("id")->find();
        $this->assign('cid',$find['id']);
        $this->assign('gid',$get);
        $this->assign('list',$list);
        $this->assign('detail',$detail);
        $this->assign('tj',$tj);
        $this->assign('comment',$comment);
        $this->assign('empty','<h1 style="color:#ff0632;">暂无相关评价!</h1>');
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