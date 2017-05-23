<?php
namespace Home\Controller;
use Think\Controller;
class CartController extends Controller{
    //推荐商品
    public function recommend(){
        $goods=D("Goods");
        return $goods->recommendList();
    }
    //我的购物车页
    public function myCart(){
        //判断用户有没有登陆
        $goods=D("Goods");
        $mid=session("mid");
        if(!empty($mid)){
            //用户已经登陆从数据库中读取信息
            $cartList=$goods->getCartList($mid);
        }else{
            //用户没有登陆情况下，从session中取数据
//            foreach(array_reverse($_SESSION["mycart"]) as $k=>$v){
            foreach(array_reverse(session("mycart")) as $k=>$v){
                $cartList[$k]=$goods->getSessionGoods($v["gid"]);
                $cartList[$k]["gid"]=$v["gid"];
                $cartList[$k]["buynum"]=$v["buynum"];
            }
        }
        //获得我的购物车中，推荐商品的信息
        $recommendInfo=$this->recommend();
        //分配商品信息
        $this->assign("cartList",$cartList);
        $this->assign("recommendInfo",$recommendInfo);
        $this->display();
    }
    //加入购物车成功页
    public function addtocart(){
        $goods=D("Goods");
        $cart=D("Cart");
        $goodscontroller=new GoodsController();
        if(IS_POST){
            //购物车加加
            $sum=session("sum")+I("post.buynum");
            session("sum",$sum);
            //判断用户是否登陆
            if(session("mid") && session("mid")>0){
                //【1】用户登录状态,把数据存入数据库
                //判断数据库中有没有该用户购买的该商品
                $data["mid"]=session("mid");
                $data["gid"]=I("post.gid");
                $info=$cart->getCartInfo($data);
                if($info){
                    //数据库中存在，更新商品的购买数量和添加时间
                    $dataArr["buynum"]=$info["buynum"]+I("post.buynum");
                    $dataArr["addtime"]=time();
                    $upd=$cart->where($data)->save($dataArr);
                    if($upd){
                        $this->ajaxReturn(array("status"=>"ok","msg"=>"加入购物车成功"));
                    }else{
                        $this->ajaxReturn(array("status"=>"error","msg"=>"加入购物车失败"));
                    }
                }else{
                    //数据库中不存在，直接写入数据库
                    $cartGoods["mid"]=session("mid");
                    $cartGoods["gid"]=I("post.gid");
                    $cartGoods["buynum"]=I("post.buynum");
                    $cartGoods["addtime"]=time();
                    $add=$cart->add($cartGoods);
                    if($add){
                        $this->ajaxReturn(array("status"=>"ok","msg"=>"加入购物车成功"));
                    }else{
                        $this->ajaxReturn(array("status"=>"error","msg"=>"加入购物车失败"));
                    }
                }

            }else{
                //【1】用户未登录状态,把数据写入session
                //判断session中该用户对应的该商品是否存在
                $gid=I("post.gid");
                $buynum=I("post.buynum");
//                if(!empty($_SESSION["mycart"][$gid])){
//                    //session中存在该商品，更新session数据
//                    $_SESSION["mycart"][$gid]["buynum"]+=$buynum;
//                    $_SESSION["mycart"][$gid]["addtime"]=time();
//                $this->ajaxReturn(array("status"=>"ok","msg"=>"加入购物车成功"));
                if(session("mycart.".$gid)){
                    //session中存在该商品，更新session数据
                    $arr=session("mycart.".$gid);
                    $arr['buynum']=$arr['buynum']+$buynum;
                    $arr['addtime']=time();
                    session("mycart.".$gid,$arr);
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"加入购物车成功"));
                }else{
                    //session中不存在该商品，直接存入
                    $cart_info["gid"]=I("post.gid");
                    $cart_info["buynum"]=I("post.buynum");
                    $cart_info["addtime"]=time();
//                    $_SESSION["mycart"][$gid]=$cart_info;
                    session("mycart.".$gid,$cart_info);
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"加入购物车成功"));
                }
            }
        }else{
            //在加入购物车成功页显示加入的商品信息
            $gid=I("get.gid");
            $goodsInfo=$goods->addCartSuccess($gid);
            //判断用户是否登陆
            if(session("mid") && session("mid")>0){
                //从数据库中读取商品数量
                $mid=session("mid");
                $cart_num=$cart->getGoodsNum($mid,$gid);
                $goodsInfo["buynum"]=$cart_num["buynum"];
            }else{
                //从session中读取商品数量
//                $goodsInfo["buynum"]=$_SESSION["mycart"][$gid]["buynum"];
                $goodsInfo["buynum"]=session("mycart.".$gid)["buynum"];
            }
            //购买此商品的人还购买了
            $someLike=D("Cart")->getSomeLike($gid);
            //分配变量信息
            $this->assign("someLike",$someLike);
            $this->assign("goodsInfo",$goodsInfo);
            $this->display();
        }
    }
    //去登陆
    public function tologin(){
        $this->display();
    }
    //删除购物车中指定的商品
    public function del($gid){
        $cart=M("Cart");
        //判断用户是否登陆
        if(session("mid") && session("mid") > 0){
                //登陆删除数据库中的商品信息
                $mid=session("mid");
                $info=$cart->where("mid={$mid} AND gid={$gid}")->delete();
                if($info){
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
                }else{
                    $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
                }
        }else{
            //未登录伤处session中的商品信息
//            unset($_SESSION["mycart"][$gid]);
            session("mycart.".$gid,null);
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }

    }
    //用户登录时，转移session中的数据到我的购物车中
    public function removeCart(){
        $cart=M("Cart");
        //用户登录时，把session中的商品数据，移到数据库中
//        if(isset($_SESSION["mycart"])){
        if(session("mycart")){
            $mid=session("mid");
//            foreach($_SESSION["mycart"] as $v){
            foreach(session("mycart") as $v){
                $v["mid"]=$mid;
                //判断数据库中是否有该用户的该商品信息
                $ids=$cart->where("gid={$v['gid']} AND mid={$v['mid']}")->find();
                if($ids){
                    $data["buynum"]=$ids["buynum"]+$v["buynum"];
                    $data["addtime"]=time();
                    $res=$cart->where("id={$ids['id']}")->save($data);
                }else{
                    $res=$cart->add($v);
                }
            }
            if($res){
//                unset($_SESSION["mycart"]);
                session("mycart",null);
            }
        }
    }
}