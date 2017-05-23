<?php
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model{
    //获取列表时，根据订单标的会员ID和状态值，得到用户名和状态名重组的数组信息
    public function getList($list){
        foreach($list as $k=>$v){
            //更具订单表的会员ID【关联会员表】得到会员名
            $member=M("Member");
            $mid=$v["mid"];
            $info1=$member->where("id='{$mid}'")->find();
            $list[$k]["username"]=$info1["username"];
            //更具订单表的状态值【关联状态表】，都得到状态名
            $status=M("Order_status");
            $order_status=$v["order_status"];
            $info2=$status->where("id='{$order_status}'")->find();
            $list[$k]["status_name"]=$info2["status_name"];
        }
        //返回重新处理好的记录信息
        return $list;
    }
    //发货时，处理会员信息，以及选择快递
    public function toSend($id){
        $o_info=$this->where("id={$id}")->find();
        //查找用户名
        $member=M("Member");
        $mid=$o_info["mid"];
        $m_info=$member->where("id={$mid}")->find();
        //查找用户地址和电话
        $address=M("Address");
        $add_id=$o_info["address"];
        $a_info=$address->where("id={$add_id}")->find();
        //拼装发货单所需信息
        $data["id"]=$id;
        $data["username"]=$m_info["username"];
        $data["address"]=$a_info["address"];
        $data["mobile"]=$a_info["mobile"];
        $data["order_syn"]=$o_info["order_syn"];
        $data["delivery"]=$o_info["delivery"];
        return $data;
    }
    //订单详情，根据订单号，查出订单详情所需要的所有信息
    public function getDetail($id){
        //1.更具订单ID得到会员的信息，地址信息，快递信息，本身订单信息
        //【1】原生SQL语句链表查询
        //$sql="select o.addtime,o.order_price,o.order_syn,m.username,a.mobile,a.address,a.name,s.status_name from
              //shop_order o,shop_member m,shop_address a,shop_order_status s where
              //o.mid=m.id and o.address=a.id and o.order_status=s.id and o.id={$id}";
        //$data=$this->query($sql);
        //【2】连贯操作连表查询
        $data=$this->field("shop_address.name,shop_order.order_syn,shop_member.username,shop_order.order_price,shop_address.mobile,shop_address.address,shop_order.addtime,shop_order_status.status_name")
                   ->join("shop_member on shop_order.mid=shop_member.id")
                   ->join("shop_address on shop_order.address=shop_address.id")
                   ->join("shop_order_status on shop_order.order_status=shop_order_status.id")
                   ->where("shop_order.id={$id}")->select();
        return $data;
    }
    //订单商品表中的商品信息
    public function getGoodsInfo($id){
        //根据订单ID获得订单中的商品信息
        $sql="select g.goodsname,g.pic,og.buynum,g.price from shop_order as o,shop_order_goods as og,shop_goods as g
              where og.oid=o.id and og.gid=g.id and o.id={$id}";
        $goodsInfo=$this->query($sql);
        return $goodsInfo;
    }
}