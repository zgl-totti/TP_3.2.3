<?php
namespace Home\Controller;
use Think\Controller;
class MessageController extends Controller{
    //未读信息列表
    public function notRead(){
        $message=D("Message");
        $mid=session("mid");
        $msgInfo=$message->where("mid={$mid} AND msgstatus=0")->select();
        $this->assign("msgInfo",$msgInfo);
        $this->display();
    }
    //已读信息列表
    public function hasRead(){
        $message=D("Message");
        $mid=session("mid");
        $msgInfo=$message->where("mid={$mid} AND msgstatus=1")->select();
        $this->assign("msgInfo",$msgInfo);
        $this->display();
    }
    //查看详情
    public function checkDetail(){
        $ag_id=I("get.ag_id");
        $mid=session("mid");
        $auction=D("Auction");
        $message=D("Message");
        $auctionGoods=M("Auction_goods");
        $auctionSuccess=M("Auction_deposit");
        $member=M("Member");
        $auctionInfo=$auctionGoods->alias("ag")->where("msg.ag_id={$ag_id} AND msg.mid={$mid}")
                                  ->field("g.pic,g.goodsname,ag.gid,s.price,msg.addtime")
                                  ->join("shop_goods g on g.id=ag.gid")
                                  ->join("shop_auction_success s on s.ag_id=ag.id")
                                  ->join("shop_message msg on msg.ag_id=ag.id")->find();
        $memberInfo=$member->field("username")->where("id={$mid}")->find();
        $auctionInfo["username"]=$memberInfo["username"];
        //查询交易保证金
        $auctionprice=$auctionSuccess->where("mid={$mid} AND ag_id={$ag_id}")->field("deposit")->find();
        $auctionInfo["deposit"]=$auctionprice["deposit"];
        //改变点击的这条信息的状态
        $data["msgstatus"]=1;
        $message->where("mid={$mid} AND ag_id={$ag_id}")->save($data);
        //给查看详情分配变量信息
        $this->assign("auctionInfo",$auctionInfo);
        $this->display();
    }
}