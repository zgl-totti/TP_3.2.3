<?php
namespace Home\Model;
use Think\Model;
class OrderModel extends Model{
    //获取订单信息
    public function orderInfo($oid){
        //多表原生查询
        //$sql="select o.order_syn,o.addtime,m.username,s.status_name from shop_order as o,shop_order_status as s,
        //      shop_member as m where o.order_status=s.id and o.mid=m.id and o.id={$oid}";
        //$orderInfo=$this->query($sql);
        //连贯操作，多表查询订单信息
        $orderInfo=$this->alias("o")->field("o.id,o.order_syn,o.addtime,m.username,s.status_name")->where('o.id='.$oid)
                        ->join("shop_order_status s on o.order_status=s.id")
                        ->join("shop_member m on o.mid=m.id")->select();
        return $orderInfo;
    }
    //获取用户收货地址信息
    public function addInfo($mid){
        //多表原生查询
        //$sql="select name,address,mobile,postcode from shop_address where mid={$mid}";
       //$addInfo=$this->query($sql);
        //多表连贯操作查询
        $addInfo=$this->table("shop_address")->order("isdefault desc")->limit(0,4)
                      ->field("id,address,mobile,postcode,name,isdefault")->where("mid={$mid}")->select();
        return $addInfo;
    }
   //获取订单中商品的信息
    public function goodsInfo($oid){
        $goodsInfo=$this->alias("o")->field("o.order_price,g.pic,g.id,g.goodsname,g.marketprice,g.price,og.buynum")->where("o.id=".$oid)
                        ->join("shop_order_goods og on og.oid=o.id")
                        ->join("shop_goods g on og.gid=g.id")->select();
        return $goodsInfo;
    }
}