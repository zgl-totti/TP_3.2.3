<?php
namespace Home\Model;
use Think\Model;
class GoodsModel extends Model{
    //获取详情页商品展示信息
    public function getGoodsDetail($gid){
        $detailGoods=$this->field("id,goodsname,pic,introduction,marketprice,price,addtime,num")->where("id=".$gid)->find();
        return $detailGoods;
    }
    //获取加入购车成功页的商品信息
    public function addCartSuccess($gid){
        $goodsInfo=$this->where("id=".$gid)->field("pic,goodsname,price")->find();
        return $goodsInfo;
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
    //获得商品的所有图片信息
    public function getGoodsPic($gid){
        $goodsPic=M("Goods_pic")->field("picname")->where("gid=".$gid)->select();
        return $goodsPic;
    }
    //获得购物车页，推荐商品列表，依据条件，添加时间最新和销量最多
    public function recommendList(){
        $recommendInfo=$this->field("id,goodsname,pic,price")->order("addtime desc,salenum desc")->limit(0,6)->select();
        return $recommendInfo;
    }


}