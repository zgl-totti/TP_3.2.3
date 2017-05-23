<?php
namespace Home\Controller;
use Think\Controller;
class IntegralController extends Controller{
    public function showlist(){
        /*列出商品*/
        $list = M('Goods')->where(array('display'=>0))->order('addtime desc')->select();
        //循环遍历，把商品价格兑换成积分
        foreach($list as $k=>$v){
            $list[$k]["price"]=$v["price"]*10;
        }
        /*列出广告*/
        $ads = M('ads')->where(array('adposition'=>6))->select();
        $this->assign('ads',$ads);
        $this->assign('list',$list);
        $this->display();
    }
    public function detail(){
        $goodsDetail=D("Goods");
        $list = D('Goods')->where(array("id"=>I('get.gid')))->find();
        $list["price"]=$list["price"]*10;//价格兑换成积分
        $listpic = M('Goods_pic')->where(array("gid"=>I('get.gid')))->select();
        $history=new HistoryController();
        //获得同类商品推荐
        $cateLikeList=D('Category')->getLikeGoods(I('get.gid'));
        //把该商品存入浏览历史中
        $goodsPicInfo=$goodsDetail->getGoodsPic(I('get.gid'));
        //获取图片信息
        $history->addHistory(I('get.gid'));
        //从浏览历史中取出该商品的信息
        $historyInfo=$history->selectHistory();
        //分配给模板
        $this->assign('gid',I('get.gid'));
        $this->assign("cateLikeList",$cateLikeList);
        $this->assign('list',$list);
        $this->assign("goodsPicInfo",$goodsPicInfo);
        $this->assign('listpic',$listpic);
        $this->assign('historyInfo',$historyInfo);
        $this->display();
        //收藏
/*        $goods=D('Goods');
        $gid=I('get.gid');
        $collect=D('collect');
        if(session('mid') && session('mid')>0){
            $mid=session('mid');
            $col1=$collect->where("gid=$gid AND mid=$mid")->select();
            if($col1){$col = $goods->where("id=$gid")->field('id,bid')->select();}
            else{$col = $goods->where("id=$gid")->field("id")->select();}
        }else{$col = $goods->where("id=$gid")->field('id')->select();}
        $this->assign('col',$col);*/

    }
    
    public function ads(){

            $pathInfo = M('Category')->where("id=1")->field('path')->select();
            $path=$pathInfo[0]['path'];
            $map['path']=(array('like',"{$path}%"));
            $cateArr =  M('Category')->where($map)->field('id')->select();
            $catestr='';
            foreach ($cateArr as $k=>$v){
                $catestr.=$v['id'].',';
            }
            $catestr=substr($catestr,0,-1);
            $goods = M('Goods');
            $where1['cid']=(array('in',"{$catestr}"));
            $one = $goods->where($where1)->limit(6)->select();
            /*$two =$this->getGoodslist(5);*/
            $this->assign('one',$one);
//            $this->assign('two',$two);
        $this->display();
    }
    //去登陆
    public function tologin(){
        $this->display();
    }
    //显示留言
    public function shower(){
        $gid=I("post.gid/d");
        if($gid){
            $comment=M('goods_comment');
            $com=$comment
                ->join('shop_member ON shop_goods_comment.mid=shop_member.id')
                ->where('shop_goods_comment.isshow=1')
                ->where("gid={$gid}")
                ->field('commentcontent,username,replycontent,shop_goods_comment.addtime,isreply')
                ->select();
            $this->ajaxReturn($com);
        }else{
            $this->ajaxReturn('失败');
        }
    }
    //收藏

/*    public function collect(){
        $collect=D('Collect');
        if(session('mid') && session('mid')>0){
            $col['mid']=session('mid');
            $col['gid']=I('post.id');
            $info=$collect->where($col)->select();
            if($info){
                $collect->where($col)->delete();
                $this->ajaxReturn(array('status'=>1,'msg'=>'取消收藏成功'));
            }else{
                $col['addtime']=time();
                $info2=$collect->add($col);
                if($info2){
                    $this->ajaxReturn(array('status'=>2,'msg'=>'收藏成功'));
                }else{
                    $this->ajaxReturn(array('status'=>3,'msg'=>'收藏失败'));
                }
            }
        }else{
            $this->ajaxReturn(array('status'=>4));
        }
    }*/
    
}