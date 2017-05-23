<?php
namespace Mobile\Model;
use Think\Model;
class CartModel extends Model{
    //判断数据库中是否拥有该用户购买的该商品信息
    public function getCartInfo($data){
        $info=$this->where($data)->find();
        return $info;
    }
    //得到加入购物车成功页面的商品数量
    public function getGoodsNum($mid,$gid){
        $cart_num=$this->where("mid={$mid} And gid={$gid}")->field("buynum")->find();
        return $cart_num;
    }
    //购买此商品的人还购买了
    public function getSomeLike($gid){
        //根据会员ID和商品ID推导出，购买此商品的人还购买了那些商品
        $someLike=$this->alias("c")->field("c.gid,pic,goodsname,price")->limit(0,10)->where("c.gid!={$gid}")->order("c.addtime")
                       ->join("shop_goods g on c.gid=g.id ")->select();
        return $someLike;
    }
    //获取我的购物车中的所有商品列表
    public function getCartList($mid){
        $goodsInfo=$this->alias("c")->field("g.goodsname,g.pic,g.marketprice,g.price,c.buynum,c.gid")
            ->where("c.mid={$mid}")->join("shop_goods as g on g.id=c.gid")->select();
        return $goodsInfo;
    }
}