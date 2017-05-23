<?php
namespace Mobile\Model;
use Think\Model;

class GoodsModel extends Model{
//首页根据相关信息找到相关数据
    public function getGoodslist($where,$display,$num){
        $list = $this->order("{$where} desc")->where(array('display'=>$display))->limit($num)->select();
        return $list;
    }
    //获取详情页中商品信息
    public function GoodsDetail($gid){
        $list = $this->where(array('id'=>$gid))->find();
        return $list;
    }
    //获取相关商品评价
    public function GoodsComment($gid){
        $list = M('Goods_comment')->alias('gc')->join('shop_member as m on m.id=gc.mid')
                                               ->field('username,pic,gc.*')->where(array('gc.gid'=>$gid))->select();
        foreach ($list as $k=>$v){
            $where['addtime']=array(array('gt',$v['addtime']-10),array('lt',$v['addtime']+10));
            $where['gid']=$gid;
            $where['mid']=$v['mid'];
            $comment_pic = M('Comment_pic')->where($where)->field('picname')->limit(3)->select();
            $list[$k]['picname']=$comment_pic;
        }
        return $list;
    }
    //获取我的购物车中的所有商品列表
    public function getCartList($mid){
        $goodsInfo=$this->alias("g")->field("g.goodsname,g.pic,g.marketprice,g.price,c.buynum,c.gid")
            ->where("c.mid={$mid}")->join("shop_cart as c on g.id=c.gid")->select();
        return $goodsInfo;
    }
    //获取session中指定id的商品信息
    public function getSessionGoods($gid){
        $goodsInfo=$this->field("id,marketprice,price,pic,goodsname")->where("id={$gid}")->find();
        return $goodsInfo;
    }
    //猜你喜欢
    public function getLikeList(){
     $goodslike = $this->order("clicknum")->limit(16)->select();
        return $goodslike;
    }
    //商城推荐
    public function getRecommendList(){
        $recommentdlist = $this->order("salenum,addtime desc")->limit(16)->select();
        return $recommentdlist;
    }
    //抢购促销
    public function seckill(){
        $list = $this->join('shop_activity as a on shop_goods.id = a.gid')->select();
        return $list;
    }

}