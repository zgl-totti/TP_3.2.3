<?php
namespace Home\Controller;
use Think\Controller;
class SaleController extends Controller
{
    public function showlist()
    {
        $sale = M('Activity');
        $where['starttime'] = array('neq', 0);
        $t = time();
        $where['endtime'] = array('neq', 0);
        /*$where['endtime']=array(array('neq',0),array('gt',$t));*/
        $salelist = $sale->alias('s')->join('shop_goods g ON s.gid=g.id')->field('g.id,pic,price,marketprice,num,starttime,endtime')->where($where)->select();
        foreach($salelist as $k=>$v){
            $salelist[$k]['price']=$v['price']*0.8;
        }
        /*dump($salelist);*/
        $ads=M('Ads');
        $where['adposition']=5;
        $where['top']=array('neq',0);
        $adslist5=$ads->where($where)->order('top desc')->select();
        $this->assign('adslist5',$adslist5);


        $this->assign('salelist', $salelist);
        $this->assign('TIME', $t);
        $this->display();
    }
    public function cele(){
        $good=M('goods');
        $cele=$good->where('activity=2')->select();
        $this->assign('cele',$cele);
        $this->display();
    }
    public function tens(){
        $good=M('goods');
        $decade=$good->where('activity=3')->select();
        $this->assign('decade',$decade);
        $ads=M('Ads');
        $where['adposition']=5;
        $where['top']=array('neq',0);
        $adslist5=$ads->where($where)->order('top desc')->select();
        $this->assign('adslist5',$adslist5);
        $this->display();
        /*dump($decade);*/
    }
    public function limitBuy(){
        $gid=I('get.gid');
        $goods=M('Goods');
        $where['activity']=1;
        $where['display']=1;
        $where['id']=$gid;
        $goodsPic=M("Goods_pic")->field("picname")->where("gid=".$gid)->select();
        $goodslist=$goods->field("id,goodsname,pic,introduction,marketprice,price,addtime,num")->where($where)->find();
        $goodslist['price']=$goodslist['price']*0.8;
        $this->assign('goodslist',$goodslist);
        $this->assign('goodsPic',$goodsPic);
        $goodsactive=M("Activity")->field('id,gid,endtime')->where("gid=".$gid)->select();
        foreach($goodsactive as $k=>$v){
            $goodsactive['id']=$v['id'];
            $goodsactive['gid']=$v['gid'];
            $goodsactive['endtime']=$v['endtime'];
        }

        $gid=I("get.gid");
        //获得商品名字，商品价格，市场价格，上架时间
        $goodsDetail=D("Goods");
        $history=new HistoryController();
        $cateLike=new CategoryController();
        //调用模型方法，获得商品的详情信息
        $detailInfo=$goodsDetail->getGoodsDetail($gid);
        //调用模型的相关方法，获得商品的所有图片信息
        $goodsPicInfo=$goodsDetail->getGoodsPic($gid);
        //把该商品存入浏览历史中
        $history->addHistory($gid);
        //从浏览历史中取出该商品的信息
        $historyInfo=$history->selectHistory();
        //获得同类商品推荐
        $cateLikeList=$cateLike->getLikeCate($gid);
        //分配给模板
        $this->assign("gid",$gid);
        $this->assign("detailInfo",$detailInfo);
        $this->assign("goodsPicInfo",$goodsPicInfo);
        $this->assign("historyInfo",$historyInfo);
        $this->assign("cateLikeList",$cateLikeList);


        //print_r($goodsactive);
        $this->assign('goodsactive',$goodsactive);
        $this->assign("gid",$gid);
        /*dump($goodslist);
        dump($goodsPic);*/
        $this->display();
    }
    //去登陆
    public function tologin(){
        $this->display();
    }
    //展示留言
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
}