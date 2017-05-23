<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Page;
class OrderController extends BaseController{
    //订单展示列表
    public function showlist(){
        //实例化管理员表数据模型
        $order=D("Order");
        //代表有没有传递状态值
        $status=I("get.status")?I("get.status"):'';
        //搜索条件传递给前台模板
        $order_syn=I("get.order_syn");
        $order_status=I("get.order_status");
        $username=I("get.username");
        //订单号或者价格搜索条件
        $search["order_syn|order_price"]=array("like","%".I('get.order_syn')."%");
        //订单状态搜索条件
        $search["order_status"]=array("like","%".I('get.order_status')."%");
        //根据用户名，查找该用户在订单表中所对应的【mid】作为搜索条件
        $usersearch["username"]=array("like","%".I('get.username')."%");
        $user=M("Member");
        $userinfo=$user->where($usersearch)->find();
        if(!$username){
            $search["mid"]=array("like","%".''."%");
        }else{
            $search["mid"]=$userinfo["id"];
        }
        //数据库所有记录总数
        //根据订单状态进行数据展示
        if($status){
            $count=$order->where("order_status={$status}")->where($search)->count("id");
        }else{
            $count=$order->where($search)->count("id");
        }
        //实例化分页类 传入总记录数和每页显示的记录数为 2
        $page=new Page($count,2);
        $page->rollPage=3;
        $page->setConfig("first","首页");
        //分页显示输出(分页的页码输出)
        $show=$page->show();
        //进行分页数据查询 注意limit方法的参数要使用Page类的属性
        // $list代表每页显示的数据记录数
        //根据订单状态进行数据展示
        if($status){
            $list = $order->where("order_status={$status}")->where($search)->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $list = $order->where($search)->limit($page->firstRow.','.$page->listRows)->select();
        }
        //根据会员ID和订单状态值，得到订单名和状态名的新【$list】数组
        $list=$order->getList($list);
        //查询条件分页传递
        $map["key"]=I("get.status");
        foreach($map as $key=>$v){
            $page->parameter[$key]=urlencode($v);
        }
        //赋值数据集
        $this->assign("list",$list);
        $this->assign("firstRow",$page->firstRow);
        $this->assign("page",$show);
        $this->assign("username",$username);
        $this->assign("order_status",$order_status);
        $this->assign("order_syn",$order_syn);
        $this->display();
    }
    //订单列表发货功能
    public function tosend(){
        $order=D("Order");
        if(IS_POST){
            $address=I("post.address");
            $mobile=I("post.mobile");
            if(empty($mobile)){
                $this->ajaxReturn(array("status"=>"error","msg"=>"请填写联系方式"));
            }else{
                if(empty($address)){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"请填写收货地址"));
                }else{
                    $data['id']=I("post.id");
                    $data['order_status']=3;
                    $info=$order->save($data);
                    if($info){
                        $this->ajaxReturn(array("status"=>"ok","msg"=>"发货成功"));
                    }else{
                        $this->ajaxReturn(array("status"=>"error","msg"=>"发货失败"));
                    }
                }
            }
        }else{
            $id=I("get.id");
            $data=$order->toSend($id);
            $this->assign("val",$data);
            $this->display();
        }
    }
    //订单详情
    public function orderDetail($id){
        //根据订单号拼装所有信息
        $order=D("Order");
        //返回用户订单的相关信息
        $data=$order->getDetail($id);
        //查询订单中商品的信息
        $goodsInfo=$order->getGoodsInfo($id);
        $this->assign("data",$data);
        $this->assign("goodsInfo",$goodsInfo);
//        dump($data);
        $this->display();
    }
    //订单删除
    public function delete($oid){
        $order=M("Order");
        $orderGoods=M("Order_goods");
        //根据订单ID，删除订单中该订单的信息
        $info1=$order->where("id={$oid}")->delete();
        //根据订单ID，删除订单商品表中，该订单中的商品信息
        $data=$orderGoods->field('id')->where("oid={$oid}")->select();
        foreach($data as $k=>$v){
            $orderGoods->where("id={$v['id']}")->delete();
        }
        if($info1){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"订单移除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"订单移除失败"));
        }
    }
}