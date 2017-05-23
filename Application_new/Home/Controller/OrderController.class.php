<?php
namespace Home\Controller;
use Think\Controller;
//use Think\Page;
class OrderController extends Controller{
    //前台订单页展示
    public function showlist(){
        $order=D("Order");
        $oid=I("get.oid");//订单ID【用于查询用户订单信息】
        $mid=session("mid");//用户ID【用于查询用户地址信息】
        //根据订单ID展示订单信息
        $orderInfo=$order->orderInfo($oid);
        //根据会员ID展示用户默认收货地址信息
        $addInfo=$order->addInfo($mid);
        //根据订单ID展示订单的商品信息
        $goodsInfo=$order->goodsInfo($oid);
        //展示订单中商品的信息
        if(I("get.pay")){
            $goodsInfo[0]["price"]=I("get.pay");
            $orderInfo[0]["order_price"]=I("get.pay");
        }
        //选择快递号和支付方式
        $this->assign("orderInfo",$orderInfo);
        $this->assign("addInfo",$addInfo);
        $this->assign("goodsInfo",$goodsInfo);
        $this->display();
    }
    //前台订单页展示
    public function integralOrder(){
        $order=D("Order");
        $oid=I("get.oid");//订单ID【用于查询用户订单信息】
        $mid=session("mid");//用户ID【用于查询用户地址信息】
        //根据订单ID展示订单信息
        $orderInfo=$order->orderInfo($oid);
        //根据会员ID展示用户默认收货地址信息
        $addInfo=$order->addInfo($mid);
        //根据订单ID展示订单的商品信息
        $goodsInfo=$order->goodsInfo($oid);
        //展示订单中商品的信息
        //选择快递号和支付方式
        $this->assign("orderInfo",$orderInfo);
        $this->assign("addInfo",$addInfo);
        $this->assign("goodsInfo",$goodsInfo);
        $this->display();
    }
    //我的购物车--->生成订单
    public function createOrder(){
        $order=D("Order");
        $orderGoods=D("Order_goods");
        if(session("mid")&&session("mid")>0){
            if(IS_POST){
                //判断用户有没有登录
                if(session("mid") && session("mid")>0){
                    //组建订单数据
                    $data["mid"]=session("mid");//购买用户
                    $data["order_syn"]=date("YmdHis",time()).rand(1000000000,9999999999);//订单号
                    $data["order_price"]=I("post.total_price");//订单总价
                    $data["addtime"]=time();//下单时间
                    //判断商品总价是否为空，为空代表没有商品被选中购买
                    if(!I("post.total_price")){
                        $this->ajaxReturn(array("status"=>"error","msg"=>"请至少勾选一项商品进行购买"));
                    }
                    $oid=$order->add($data);//把数据存入订单表
                    //将订单中的商品，更新到订单商品表中
                    foreach(I("post.checkitems") as $gid){
                        $goods["oid"]=$oid;
                        $goods["gid"]=$gid;
                        $goods["buynum"]=I("post.buynum".$gid);
                        $og=$orderGoods->add($goods);
                    }
                    if($oid && $og){
                        //修改拍卖成功的状态
                        $successInfo["isshow"]=0;
                        $auctionInfo["mid"]=session("mid");
                        $auctionInfo["ag_id"]=I("post.ag_id");
                        M("Auction_success")->where($auctionInfo)->save($successInfo);
                        if(I("post.pay")){
                            $this->ajaxReturn(array("status"=>"ok","msg"=>"下单成功","oid"=>$oid,"pay"=>I("post.pay")));
                        }else{
                            $this->ajaxReturn(array("status"=>"ok","msg"=>"下单成功","oid"=>$oid));
                        }
                    }else{
                        $this->ajaxReturn(array("status"=>"error","msg"=>"请至少选择一项商品进行购买"));
                    }
                }
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"请至少选择一项商品进行购买"));
            }
        }else{
            $this->ajaxReturn(array("status"=>"login","msg"=>"请登录后再购买！！！"));
        }
    }
    //立即购买生成订单
    public function toBuyCreateOrder(){
        $order=D("Order");
        $og=D("Order_goods");
        $mid=session("mid");
        if($mid){
            $price=I("post.price");
            $buynum=I("post.buynum");
            $data["order_syn"]=date("YmdHis",time()).rand(1000000000,9999999999);//订单号
            $data["order_price"]=$price*$buynum;
            $data["mid"]=$mid;
            $data["addtime"]=time();
            $oid=$order->add($data);
            if($oid){
                $goods["oid"]=$oid;
                $goods["gid"]=I("post.gid");
                $goods["buynum"]=$buynum;
                $og_id=$og->add($goods);
            }
            if($oid && $og_id){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"订单生成","oid"=>$oid));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"下单失败"));
            }
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"登录后才能下单"));
        }
    }
    //提交订单
    public function submitOrderSuccess(){
        $goods=D("Goods");
        //获得订单ID
        $oid=I("get.oid");
        //获得订单单号
        $orderSyn=I("get.orderSyn");
        //购买该商品的人还购买了
        $goodsInfo=$goods->field("id,goodsname,pic,price")->limit(15,15)->select();
        //分配变量信息
        $this->assign("orderSyn",$orderSyn);
        $this->assign("goodsInfo",$goodsInfo);
        $this->display();
    }
    //会员删除订单
    public function delOrder($id){
        $mid=session("mid");
        //根据会员id和订单Id，删除订单，也删除了订单商品表中的商品信息
        $order=M("Order");
        //根据订单Id，查出订单商品表中的商品ID
        $goodsIds=$order->alias("o")->field("og.gid")->where("o.id={$id}")
                        ->join("shop_order_goods og on og.oid=o.id")->select();
        //把$goodsIds数组转换为一维数组
        $ids=array();
        foreach($goodsIds as $k=>$v){
            $ids[]=$v["gid"];
        }
        //删除指定订单
        $orderInfo=$order->where("mid={$mid} AND id={$id}")->delete();
        //删除订单商品表中的商品信息
        $og=M("Order_goods");
        $where["oid"]=$id;
        $where["gid"]=array("in",$ids);
        $ogInfo=$og->where($where)->delete();
        //判断是否删除成功
        if($orderInfo && $ogInfo){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"订单删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"订单删除失败"));
        }
    }
    //订单签收
    public function qianshou($id){
        $order=D("Order");
        //改变该订单的状态值为4【代表已签收】
        $data["order_status"]=4;
        $info=$order->where("id={$id}")->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"订单签收成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"订单签收失败"));
        }
    }
    //积分处理生成订单
    public function integral(){
        $order=D("Order");
        $og=D("Order_goods");
        $member=M("Member");
        $mid=session("mid");
        if($mid){
            $price=I("post.price");
            //首先判断用户的积分是否充足
            $memberInfo=$member->where("id={$mid}")->field("credit,money")->find();
            if($memberInfo["credit"]<$price){
                $this->ajaxReturn(array("status"=>"error","msg"=>"兑换失败,你的积分不足"));
            }else{
                $buynum=I("post.buynum");
                //积分充足，扣除积分兑换成余额，更新会员的积分
                $memberInfo1["id"]=$mid;
                $memberInfo1["credit"]=floor($memberInfo["credit"])-floor($price);
                $memberInfo1["money"]=$memberInfo["money"]+($price*$buynum)/10;
                $member->save($memberInfo1);
                //记录积分兑换的商品信息,插入到积分兑换表中
                $creditModel=M("Credit_goods");
                $credit["gid"]=I("post.gid");
                $credit["buynum"]=$buynum;
                $credit["mid"]=$mid;
                $credit["credit"]=$price*$buynum;
                $credit["addtime"]=time();
                $creditModel->add($credit);
                //生成订单
                $data["order_syn"]=date("YmdHis",time()).rand(1000000000,9999999999);//订单号
                $data["order_price"]=($price*$buynum)/10;
                $data["mid"]=$mid;
                $data["addtime"]=time();
                $oid=$order->add($data);
                if($oid){
                    $goods["oid"]=$oid;
                    $goods["gid"]=I("post.gid");
                    $goods["buynum"]=$buynum;
                    $og_id=$og->add($goods);
                }
                if($oid && $og_id){
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"订单生成","oid"=>$oid));
                }else{
                    $this->ajaxReturn(array("status"=>"error","msg"=>"兑换失败"));
                }
            }
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"登录后才能兑换"));
        }
    }
}







































