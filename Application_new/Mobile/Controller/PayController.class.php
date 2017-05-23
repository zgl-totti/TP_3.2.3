<?php
namespace Mobile\Controller;
use Think\Controller;
class PayController extends Controller{
    //支付页展示
    public function payList(){
        if(IS_POST){
            $order=D("Order");
            $feedback=M("Feedback");
            //获得基本信息
            $mid=session("mid");//会员ID
            $oid=I("post.oid");//订单ID
            $address=I("post.address");//收货地址
            if(!$address){
                $this->ajaxReturn(array("status"=>"error","msg"=>"收货地址不能为空，请编辑选择一项"));
            }
//        $pay=I("post.pay");//支付方式
            //更新订单表中的【收货地址信息】和【快递信息】
            $data["id"]=$oid;
            $data["address"]=$address;
            $order->save($data);
            //根据订单ID，查询订单金额和订单序列号
            $orderInfo=$order->field("order_syn,order_price")->where("id={$oid}")->find();
            $orderInfo["oid"]=$oid;
            //给商家反馈信息
            $words["mid"]=$mid;
            $words["content"]=I("post.content");
            $words["addtime"]=time();
            if($words["content"]){
                $feedback->add($words);
            }
            //分配变量信息
            $this->assign("orderInfo",$orderInfo);
            $this->ajaxReturn(array("status"=>"ok","msg"=>"提交成功","order_syn"=>$orderInfo["order_syn"],"order_price"=>$orderInfo["order_price"],"oid"=>$orderInfo["oid"]));
//            $this->display();
        }else{
            $order=D("Order");
            $member=D("Member");
            $orderInfo["mid"]=session("mid");
            $orderInfo["order_syn"]=I("get.order_syn");//订单编号
            $orderInfo["order_price"]=I("get.order_price");//订单价格
            $orderInfo["oid"]=I("get.oid");//订单ID
            //根据订单ID，查询订单状态
            $info=$order->alias("o")->where("o.id={$orderInfo["oid"]}")->field("status_name")
                        ->join("shop_order_status s on s.id=o.order_status")->find();
            $orderInfo["status_name"]=$info["status_name"];
            //根据会员ID，查询会员账号
            $mInfo=$member->alias("m")->where("m.id={$orderInfo["mid"]} AND o.id={$orderInfo["oid"]}")->field("username")
                            ->join("shop_order o on o.mid=m.id")->find();
            $orderInfo["username"]=$mInfo["username"];
            $this->assign("orderInfo",$orderInfo);
            $this->display();
        }
    }
    //支付页展示
    public function integralpayList(){
        if(IS_POST){
            $order=D("Order");
            $feedback=M("Feedback");
            //获得基本信息
            $mid=session("mid");//会员ID
            $oid=I("post.oid");//订单ID
            $address=I("post.address");//收货地址
            if(!$address){
                $this->ajaxReturn(array("status"=>"error","msg"=>"收货地址不能为空，请编辑选择一项"));
            }
//        $pay=I("post.pay");//支付方式
            //更新订单表中的【收货地址信息】和【快递信息】
            $data["id"]=$oid;
            $data["address"]=$address;
            $order->save($data);
            //根据订单ID，查询订单金额和订单序列号
            $orderInfo=$order->field("order_syn,order_price")->where("id={$oid}")->find();
            $orderInfo["oid"]=$oid;
            //给商家反馈信息
            $words["mid"]=$mid;
            $words["content"]=I("post.content");
            $words["addtime"]=time();
            if($words["content"]){
                $feedback->add($words);
            }
            //分配变量信息
            $this->assign("orderInfo",$orderInfo);
            $this->ajaxReturn(array("status"=>"ok","msg"=>"提交成功","order_syn"=>$orderInfo["order_syn"],"order_price"=>$orderInfo["order_price"],"oid"=>$orderInfo["oid"]));
//            $this->display();
        }else{
            $order=D("Order");
            $member=D("Member");
            $orderInfo["mid"]=session("mid");
            $orderInfo["order_syn"]=I("get.order_syn");//订单编号
            $orderInfo["order_price"]=I("get.order_price");//订单价格
            $orderInfo["oid"]=I("get.oid");//订单ID
            //根据订单ID，查询订单状态
            $info=$order->alias("o")->where("o.id={$orderInfo["oid"]}")->field("status_name")
                ->join("shop_order_status s on s.id=o.order_status")->find();
            $orderInfo["status_name"]=$info["status_name"];
            //根据会员ID，查询会员账号
            $mInfo=$member->alias("m")->where("m.id={$orderInfo["mid"]} AND o.id={$orderInfo["oid"]}")->field("username")
                ->join("shop_order o on o.mid=m.id")->find();
            $orderInfo["username"]=$mInfo["username"];
            $this->assign("orderInfo",$orderInfo);
            $this->display();
        }
    }
    //确认支付
    public function topay(){
        $member=M("Member");
        $goods=M("Goods");
        $order=D("Order");
        if(IS_POST){
            $mid=session("mid");//会员ID
            $oid=I("post.oid");
            //根据订单ID，查出订单总价
            $orderInfo=$this->orderTotal($oid);
            $orderTotal=$orderInfo["order_price"];//订单价格
            $orderSyn=$orderInfo["order_syn"];//订单编号
            //根据会员ID，查出会员的余额
            $memberInfo=$this->memberMoney($mid);
            $memberMoney=$memberInfo["money"];//账户余额
            $memberPayPwd=$memberInfo["paypwd"];//支付密码
            $memberCosts=$memberInfo["costs"];//会员消费
            $memberCredit=$memberInfo["credit"];//会员积分
            //根据订单ID，查出订单的所有商品信息【salenum,num】
            $goodsInfo=$this->goodsInfo($oid);
            $paypwd=I("post.paypwd");
            //支付密码不能为空
            if(!$paypwd){
                $this->ajaxReturn(array("status"=>"error","msg"=>"支付密码不能为空"));
            }else{
                //支付密码是否正确
                if($paypwd!=$memberPayPwd){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"支付密码不正确"));
                }else{
                    //判断会员余额是否足够
                    if($memberMoney<$orderTotal){
                        $this->ajaxReturn(array("status"=>"error","msg"=>"余额不足，请去充值"));
                    }else{
                        //支付
                        $data["id"]=$mid;
                        $data["credit"]=$memberCredit+floor($orderTotal/10);
                        $data["costs"]=$memberCosts+$orderTotal;
                        $data["money"]=$memberMoney-$orderTotal;
                        //根据会员的消费，计算会员的等级
                        $costsLevel=$data["costs"];
                        if($costsLevel>0&&$costsLevel<=2999){
                            //普通会员
                            $data["level"]=1;
                        }elseif($costsLevel>2999&&$costsLevel<=4999){
                            //黄铜会员
                            $data["level"]=2;
                        }elseif($costsLevel>4999&&$costsLevel<=7999){
                            //白银会员
                            $data["level"]=3;
                        }elseif($costsLevel>7999&&$costsLevel<=9999){
                            //黄金会员
                            $data["level"]=4;
                        }else{
                            //钻石会员
                            $data["level"]=5;
                        }
                        $member->save($data);
                        //更新商品数量信息
                        foreach($goodsInfo as $k=>$v){
                            $goodsData=$this->getGoodsNum($v["id"]);
                            $goodsNum["id"]=$v["id"];
                            $goodsNum["salenum"]=$goodsData["salenum"]+$v["buynum"];
                            if($goodsData["num"]<$v["buynum"]){
                                $this->ajaxReturn(array("status"=>"error","msg"=>"库存量不足"));
                            }
                            $goodsNum["num"]=$goodsData["num"]-$v["buynum"];
                            $goods->save($goodsNum);
                        }
                        //更新订单表中的状态值为2【代表已付款】
                        $status["order_status"]=2;
                        $status["id"]=$oid;
                        $order->save($status);
                        //返回支付成功信息
                        $this->ajaxReturn(array("status"=>"ok","msg"=>"支付成功","oid"=>$oid,"orderSyn"=>$orderSyn));
                    }
                }

            }
        }else{
            $this->display("Mobile/Pay/payList");
        }
    }
    //根据订单ID，查出订单总价
    public function orderTotal($oid){
        $order=D("Order");
        $orderTotal=$order->field("order_price,order_syn")->where("id={$oid}")->find();
        return $orderTotal;
    }
    //根据会员ID，查出会员的余额
    public function memberMoney($mid){
        $member=D("Member");
        $memberInfo=$member->field("money,paypwd,costs,credit")->where("id={$mid}")->find();
        return $memberInfo;
    }
    //根据订单ID，查出订单的所有商品信息【salenum,num】
    public function goodsInfo($oid){
        $sql="select g.id,g.salenum,g.num,og.buynum from shop_order_goods og,shop_order o,shop_goods g where og.gid=g.id and og.oid=o.id and o.id={$oid}";
        $goodsInfo=M()->query($sql);
        return $goodsInfo;
    }
    //根据商品ID查出商品的salenum销售量和num库存
    public function getGoodsNum($gid){
        $goods=D("Goods");
        $data=$goods->field("salenum,num")->where("id={$gid}")->find();
        return $data;
    }
    //
}