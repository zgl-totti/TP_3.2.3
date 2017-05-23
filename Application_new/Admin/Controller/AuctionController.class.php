<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
class AuctionController extends Controller{
    //拍卖列表
    public function showlist(){
        //获得拍卖商品信息
        $auctionGoods=M("Auction_goods");
        $condition=I("get.keywords")?I("get.keywords"):"";
        $where["goodsname"]=array("like","%".$condition."%");
        //查看记录的总数目
        if($condition){
            $count=$auctionGoods->alias("ag")->where($where)->join("shop_goods g on ag.gid=g.id")->count();
        }else{
            $count=$auctionGoods->count();
        }
        //实例化分页类
        $page=new Page($count,2);
        $page->rollPage=2;
        $page->setConfig("first","首页");
        //分页显示输出
        $show=$page->show();
        //进行分页数据查询 注意limit方法的参数要使用Page类的属性
        // $list代表每页显示的数据记录数
        if($condition){
            $auctionInfo = $auctionGoods->alias("ag")->where($where)->join("shop_goods g on ag.gid=g.id")->field("g.pic,g.goodsname,ag.gid,ag.id as ag_id,ag.starttime,ag.endtime,ag.startprice,ag.commonprice,ag.maxprice,ag.status,ag.isshow")
                ->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $auctionInfo = $auctionGoods->alias("ag")->join("shop_goods g on ag.gid=g.id")->field("g.pic,g.goodsname,ag.gid,ag.id as ag_id,ag.starttime,ag.endtime,ag.startprice,ag.commonprice,ag.maxprice,ag.status,ag.isshow")
                ->limit($page->firstRow.','.$page->listRows)->select();
        }

        $map["key"]=I("get.keywords");
        foreach($map as $k=>$v){
            $page->parameter[$k]=urlencode($v);
        }
//        $auctionInfo=$auctionGoods->alias("ag")->join("shop_goods g on ag.gid=g.id")->select();
        $this->assign("auctionInfo",$auctionInfo);
        $this->assign("page",$show);
        $this->assign("firstRow",$page->firstRow);
        $this->assign("keywords",$condition);
        $this->display();
    }
    //设置拍卖时间范围
    public function settime(){
        $auctionGoods=M("Auction_goods");
        if(IS_POST){
            $data["id"]=I("post.id");
            $data['starttime']=strtotime(I("post.starttime"));
            $data['endtime']=strtotime(I("post.endtime"));
            $info=$auctionGoods->save($data);
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"设置成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"设置失败"));
            }
        }else{
            $ag_id=I("get.ag_id");
            $auctionInfo=$auctionGoods->alias("ag")->join("shop_goods g on ag.gid=g.id")->where("ag.id={$ag_id}")
                ->field("g.pic,g.goodsname,ag.gid,ag.id as ag_id,ag.starttime,ag.endtime")->select();
            $this->assign("auctionInfo",$auctionInfo);
            $this->display();
        }
    }
    //隐藏
    public function disabled($ag_id){
        $auctionGoods=M("Auction_goods");
        $data["isshow"]=0;
        $data["id"]=$ag_id;
        $info=$auctionGoods->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"隐藏成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"隐藏失败"));
        }
    }
    //展示
    public function enabled($ag_id){
        $auctionGoods=M("Auction_goods");
        $data["isshow"]=1;
        $data["id"]=$ag_id;
        $info=$auctionGoods->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"展示成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"展示失败"));
        }
    }
    //竞价记录
    public function recordList(){
        $auctionGodos=M("Auction_goods");
        $auction=D("Auction");
        $condition["goodsname"]=array("like","%".I('get.keywords')."%");
        if(I("get.keywords")){
            $count=$auction->alias("a")->where($condition)->join("shop_auction_goods ag on ag.id=a.ag_id")
                            ->join("shop_goods g on g.id=ag.gid")->count();
        }else{
            $count=$auction->count();
        }
        $page=new Page($count,3);
        $page->rollPage=3;
        $page->setConfig("first","首页");
        $show=$page->show();
        if(I("get.keywords")){
            $recordInfo=$auction->alias("a")->where($condition)->field("a.id,g.pic,g.goodsname,m.username,a.auctionprice,a.addtime")->order("mid")
                ->join("shop_auction_goods ag on ag.id=a.ag_id")
                ->join("shop_goods g on g.id=ag.gid")
                ->join("shop_member m on m.id=a.mid")
                ->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $recordInfo=$auction->alias("a")->field("a.id,g.pic,g.goodsname,m.username,a.auctionprice,a.addtime")->order("mid")
                                ->join("shop_auction_goods ag on ag.id=a.ag_id")
                                ->join("shop_goods g on g.id=ag.gid")
                                ->join("shop_member m on m.id=a.mid")
                                ->limit($page->firstRow.','.$page->listRows)->select();
        }
        //查询分页绑定参数
        $map["key"]=I("get.keywords");
        foreach($map as $k=>$v){
            $page->parameter[$k]=urlencode($v);
        }
        $this->assign("recordInfo",$recordInfo);
        $this->assign("firstRow",$page->firstRow);
        $this->assign("page",$show);
        $this->assign("keywords",I("get.keywords"));
        $this->display();
    }
    //成交记录
    public function submitList(){
        $auctionGodos=M("Auction_goods");
        $auctionSuccess=M("Auction_success");
        $auction=D("Auction");
        //【1】把拍卖表中状态为0的拍卖品转入拍卖成功交易表
        $info1=$auctionGodos->where("status=0")->select();
        //遍历连表auction查询信息
        foreach($info1 as $k=>$v){
            //先得到目前每个商品的最高价
            $data["ag_id"]=$v["id"];
            $maxprice=$auction->where("ag_id={$v['id']}")->max("auctionprice");
            //根据最高价格，和拍卖品主键ID，唯一定位会员ID
            $midArr=$auction->field("mid")->where("ag_id={$v['id']} AND auctionprice='{$maxprice}'")->find();
            if($midArr["mid"]){
                $data["mid"]=$midArr["mid"];
            }
            $data["price"]=$maxprice;
            //查看拍卖交易成功表中是否存在
//            $info2=$auctionSuccess->where($data)->find();
            //有人出价时
            if($data["price"]){
                //查看拍卖交易成功表中是否存在
                $info2=$auctionSuccess->where($data)->find();
            }
            //当不存在时，直接写入拍卖交易成功表中
            if(!$info2){
                $auctionSuccess->add($data);
            }
        }
        //【2】交易成功列表页
        $condition["goodsname"]=array("like","%".I('get.keywords')."%");
        if(I('get.keywords')){
            //从交易成功表中查询出交易成功的记录
            $count=$auctionSuccess->alias("s")->where($condition)->join("shop_auction_goods ag on s.ag_id=ag.id")
                                  ->join("shop_goods g on g.id=ag.gid")->count();
        }else{
            //从交易成功表中查询出交易成功的记录
            $count=$auctionSuccess->count();
        }
        $page=new Page($count,2);
        $show=$page->show();
        $page->rollPage=3;
        $page->setConfig("first","首页");
        if(I("get.keywords")){
            $successInfo=$auctionSuccess->alias("s")->where($condition)->field("s.id,g.pic,g.goodsname,m.username,s.price")
                ->join("shop_auction_goods ag on s.ag_id=ag.id")
                ->join("shop_goods g on g.id=ag.gid")
                ->join("shop_member m on m.id=s.mid")->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $successInfo=$auctionSuccess->alias("s")->field("s.id,g.pic,g.goodsname,m.username,s.price")
                ->join("shop_auction_goods ag on s.ag_id=ag.id")
                ->join("shop_goods g on g.id=ag.gid")
                ->join("shop_member m on m.id=s.mid")->limit($page->firstRow.','.$page->listRows)->select();
        }

        $map["key"]=I("get.keywords");
        foreach($map as $k=>$v){
            $page->parameter[$k]=urlencode($v);
        }
        $this->assign("successInfo",$successInfo);
        $this->assign("firstRow",$page->firstRow);
        $this->assign("page",$show);
        $this->assign("keywords",I("get.keywords"));
        $this->display();
    }
    //前台拍卖控制器调用
    public function autoDY(){
        $auctionGodos=M("Auction_goods");
        $auctionSuccess=M("Auction_success");
        $auction=D("Auction");
        //【1】把拍卖表中状态为0的拍卖品转入拍卖成功交易表
        $info1=$auctionGodos->where("status=0")->select();
        //遍历连表auction查询信息
        foreach($info1 as $k=>$v){
            //先得到目前每个商品的最高价
            $data["ag_id"]=$v["id"];
            $maxprice=$auction->where("ag_id={$v['id']}")->max("auctionprice");
            //根据最高价格，和拍卖品主键ID，唯一定位会员ID
            $midArr=$auction->field("mid")->where("ag_id={$v['id']} AND auctionprice='{$maxprice}'")->find();
            if($midArr["mid"]){
                $data["mid"]=$midArr["mid"];
            }
            $data["price"]=$maxprice;
            //有人出价时
            if($data["price"]){
                //查看拍卖交易成功表中是否存在
                $info2=$auctionSuccess->where($data)->find();
            }
            //当不存在时，直接写入拍卖交易成功表中
            if(!$info2){
                $auctionSuccess->add($data);
            }
        }
    }
    //竞价记录---》删除操作
    public function del($id){
        $auction=D("Auction");
        $info=$auction->where("id={$id}")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
    //查看详情
    public function checkDetail(){
        $as_id=I("get.id");//拍卖成功表主键ID
        $as=M("Auction_success");
        $ag=D("Auction_goods");
        $auction=M("Auction");
        $deposit=M("Auction_deposit");
        $member=D("Member");
        $asInfo=$as->where("id={$as_id}")->find();//查找该主键所对应的基本拍卖信息
        $goodsInfo=$as->alias("s")->join("shop_auction_goods ag on ag.id=s.ag_id")->join("shop_goods g on g.id=ag.gid")
                      ->where("s.id={$as_id}")->field("g.pic,g.goodsname,s.price,ag.gid")->find();
        $memberInfo=$member->field("username")->where("id={$asInfo['mid']}")->find();
        $goodsInfo["username"]=$memberInfo["username"];
        //查询交易保证金
        $auctionprice=$deposit->where("mid={$asInfo['mid']} AND ag_id={$asInfo['ag_id']}")->field("deposit")->find();
        $goodsInfo["deposit"]=$auctionprice["deposit"];
        //查找最后一次交易的时间
        $addtime=$auction->where("mid={$asInfo['mid']} AND ag_id={$asInfo['ag_id']}")->max("addtime");
        $goodsInfo["addtime"]=$addtime;
       //分配变量
        $this->assign("auctionInfo",$goodsInfo);
        $this->display();
    }
    //交易成功删除
    public function subDel($id){
        $auctionSuccess=M("Auction_success");
        $info=$auctionSuccess->where("id={$id}")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
}