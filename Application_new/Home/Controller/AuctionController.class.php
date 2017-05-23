<?php
namespace Home\Controller;
use Think\Controller;
class AuctionController extends Controller{
    //拍卖商场
    public function showlist(){
        //获取拍卖商品的基本信息
        $auctionGoods=M("Auction_goods");
        $auction=D("Auction");
        $auctionSuccess=M("Auction_success");
        $mid=session("mid");
        $auctionInfo=$auctionGoods->alias("ag")->field("ag.status,ag.id,ag.maxprice,g.pic,ag.starttime,ag.endtime")->join("shop_goods g on g.id=ag.gid")->select();
        $this->assign("auctionInfo",$auctionInfo);
        $this->assign("time",time());//把当前时间戳传到前台页面


        //轮播
        $ads=M('Ads');
        $where['adposition']=5;
        $where['top']=array('neq',0);
        $adslist5=$ads->where($where)->order('top desc')->select();
        $this->assign('adslist5',$adslist5);


        $this->display();
    }
    //拍卖商场
    public function auctionDetail(){
        $auctionGoods=M("Auction_goods");
        $auction=M("Auction");
        $mid=session("mid");
        if(IS_POST){

        }else{
            //加载拍卖弹层,并载入拍卖数据信息
            $ag_id=I("get.ag_id");//接收拍卖商品表的主键ID
            $auctionInfo=$auctionGoods->alias("ag")->where("ag.id={$ag_id}")->field("ag.*,g.goodsname,g.num")
//                                      ->join("shop_auction a on a.ag_id=ag.id")
                                      ->join("shop_goods g on g.id=ag.gid")
                                      ->find();
            $auctionprice=$auction->alias("a")->where("a.ag_id={$ag_id}")->join("shop_auction_goods ag on ag.id=a.ag_id")->max("a.auctionprice");
            $auctionInfo["auctionprice"]=$auctionprice;
            $sql="select count(c) as num from (select count(*) as c from shop_auction where ag_id={$ag_id} group by mid) as test";
            $totalInfo=$auction->query($sql);
            $totalNum=$totalInfo[0]["num"];//出价的总人数
            $perpleNum=$auction->where("ag_id={$ag_id}")->count();//出价的总次数
            $totalNum=$totalNum?$totalNum:0;
            $perpleNum=$perpleNum?$perpleNum:0;
            $auctionInfo["auctionprice"]=$auctionInfo["auctionprice"]?$auctionInfo["auctionprice"]:$auctionInfo["startprice"];
            $auctionInfo["totalNum"]=$totalNum;
            $auctionInfo["perpleNum"]=$perpleNum;
            $this->assign("auctionInfo",$auctionInfo);
            $this->display();
        }
    }
    //我要竞拍时需要先登录---》去登陆
    public function tologin(){
        $this->display();
    }
    //报名交保证金
    public function deposit(){
        $auctionDeposit=M("Auction_deposit");
        $auctionGoods=M("Auction_goods");
        $auction=D("Auction");
        //收集要插入保证金表的数据的信息
        $data["mid"]=session("mid");//会员ID
        $data["ag_id"]=I("post.ag_id");//拍卖商品表ID
        $deposit=I("post.deposit");//交易保证金
        //根据拍卖商品表主键ID查询出最高价格
        $auctionInfo=$auctionGoods->where("id={$data['ag_id']}")->field("maxprice")->find();
        $lowPrice=floor($auctionInfo["maxprice"]/2);//交易保证金的最低限度
        if($lowPrice>$deposit){
            $this->ajaxReturn(array("status"=>"error","msg"=>"保证金不能低于{$lowPrice}元"));
        }else{
                //判断会员在指定时间范围内是否已经提交过保证金
                $submitDeposit=$auctionDeposit->where($data)->find();
                if($submitDeposit){
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"你已经交过保证金了，可以直接参与竞价"));
                }else{
                    $data["deposit"]=$deposit;
                    //插入表中
                    $info=$auctionDeposit->add($data);
                    if($info){
                        $this->ajaxReturn(array("status"=>"ok","msg"=>"谢谢你的参与,请去竞价"));
                    }else{
                        $this->ajaxReturn(array("status"=>"error","msg"=>"保证金提交失败，请重试"));
                    }
                }
            }
    }
    //确认出价
    public function auctionprice(){
        $auctionDeposit=M("Auction_deposit");//拍卖保证金表对象
        $auctionGoods=M("Auction_goods");//拍卖商品表对象
        $auction=D("Auction");//拍卖记录表对象
        $message=D("Message");
        //首先判断用户是否交过保证金
        $data["auctionprice"]=I("post.auctionprice");
        $data["ag_id"]=I("post.ag_id");
        $mid=session("mid");//获得会员的ID号
        $info=$auctionDeposit->where("mid={$mid} AND ag_id={$data['ag_id']}")->field("deposit")->find();
        $auctionInfo=$auctionGoods->where("id={$data['ag_id']}")->find();
        //判断会员是否交过保证金
        if(!$info){
            //没交保证金
            $this->ajaxReturn(array("status"=>"error","msg"=>"你没交过保证金,不能直接参与竞拍"));
        }else{
            //交过保证金
            //判断出价的人数是否超过十人
            $perpleNum=$auction->getPerpleNum($data["ag_id"]);
            if($perpleNum>10){
                //该商品的参与总人数超过十人
                $this->ajaxReturn(array("status"=>"error","msg"=>"对不起，你来晚了，参赛人数已到上限"));
            }else{
                //该商品的参与总人数未超过十人
                //判断每人出价的次数是否超过三次
                $perNum=$auction->getTotalNum($mid,$data["ag_id"]);
                if($perNum>=3){
                    //每人出价的次数超过三次
                    $this->ajaxReturn(array("status"=>"error","msg"=>"对不起，每个人只能竞拍三次，你已用完"));
                }else{
                    //每人出价的次数未超过三次
                    //判断出价的价格是否低于起拍价格
                    if($data["auctionprice"]<$auctionInfo["startprice"]){
                        $this->ajaxReturn(array("status"=>"error","msg"=>"对不起,竞拍价不能低于起拍价"));
                    }else{
                        //首先判断自己出价之后是否还有人出过价（根据出价时间）
                        $addtimeInfo=$auction->getAddtime($mid,$data["ag_id"]);
                        if($addtimeInfo){
                            $this->ajaxReturn(array("status"=>"error","msg"=>"对不起,你不能连续竞价"));
                        }else{
                            //判断出价的价格是否高于当前的最高价格
                            if($data["auctionprice"]>$auctionInfo["maxprice"]){
                                $this->ajaxReturn(array("status"=>"error","msg"=>"对不起,竞拍价不能高于最高价"));
                            }else{
                                if($data["auctionprice"]==$auctionInfo["maxprice"]){
                                    $auctionGoodsInfo["status"]=0;
                                    $auctionGoodsInfo["id"]=I("post.ag_id");
                                    //更新状态
                                    $auctionGoods->save($auctionGoodsInfo);
                                    //收集竞拍信息
                                    $data["mid"]=$mid;
                                    $data["addtime"]=time();
                                    //把信息插入记录表
                                    $auctionInfo=$auction->add($data);
                                    //给会员发送信息
                                    $msgInfo["mid"]=$mid;
                                    $msgInfo["ag_id"]=$data["ag_id"];
                                    $msgInfo["message"]="恭喜你获得该宝贝，请去我的拍卖查看，付款，逾期将扣除保证金，谢谢合作";
                                    $msgInfo["addtime"]=time();
                                    $message->add($msgInfo);
                                    //跟新到拍卖成功表
                                    A("Admin/Auction")->autoDY();
                                    //判断拍卖是否完成
                                    if($auctionInfo){
                                        $num=3-($perNum+1);
                                        $this->ajaxReturn(array("status"=>"ok","msg"=>"恭喜你拍得该宝贝,请去会员中心查看"));
                                    }else{
                                        $this->ajaxReturn(array("status"=>"error","msg"=>"出价失败"));
                                    }
                                }else{
                                    //收集竞拍信息
                                    $data["mid"]=$mid;
                                    $data["addtime"]=time();
                                    //把信息插入记录表
                                    $auctionInfo=$auction->add($data);
                                    if($auctionInfo){
                                        $num=3-($perNum+1);
                                        $this->ajaxReturn(array("status"=>"ok","msg"=>"出价成功,该商品你还有{$num}次竞拍机会"));
                                    }else{
                                        $this->ajaxReturn(array("status"=>"error","msg"=>"出价失败"));
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
    }
    //时间已到，拍卖结束,挑选出最大出价者
    public function timeOver(){

    }
}