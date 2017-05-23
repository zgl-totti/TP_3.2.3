<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Page;
class VoteController extends BaseController{
    //投票列表
    public function showlist(){
        $activity=D("Activity");
        //查询条件
        $condition["goodsname"]=array("like","%".I("get.keywords")."%");
        $condition["addvote"]=array("eq",1);
        if($condition["goodsname"]){
            $count=$activity->alias("a")->where($condition)->join("shop_goods g on a.gid=g.id")->count();
        }else{
            $count=$activity->where("addvote=1")->count();
        }

        $page=new Page($count,3);
        $page->rollPage=3;
        $page->setConfig("first","首页");
        //分页显示输出(分页的页码输出)
        $show=$page->show();
        if($condition["goodsname"]){
            $voteList=$activity->alias("a")->field("g.pic,g.goodsname,a.votecount,a.starttime,a.endtime,a.id")
                ->where($condition)->order("votecount desc")->join("shop_goods g on g.id=a.gid")
                ->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $voteList=$activity->alias("a")->field("g.pic,g.goodsname,a.votecount,a.starttime,a.endtime,a.id")
                ->where("a.addvote=1")->order("votecount desc")->join("shop_goods g on g.id=a.gid")
                ->limit($page->firstRow.','.$page->listRows)->select();
        }

        $map["key"]=I("get.keywords");
        foreach($map as $k=>$v){
            $page->parameter[$k]=urlencode($v);
        }
        $this->assign("voteList",$voteList);
        $this->assign("firstRow",$page->firstRow);
        $this->assign("keywords",I("get.keywords"));
        $this->assign("page",$show);
        $this->display();
    }
    //投票总记录表
    public function voteRecord(){
        $vote=D("Vote");
        $count=$vote->count();
        $page=new Page($count,3);
        $page->rollPage=3;
        $page->setConfig("first","首页");
        //分页显示输出(分页的页码输出)
        $show=$page->show();
        $voteList=$vote->alias("v")->field("g.pic,g.goodsname,a.votecount,v.id,v.votetime,v.ip,v.votenum")
            ->join("shop_activity a on a.id=v.aid ")
            ->order("votetime desc")->join("shop_goods g on g.id=a.gid")
            ->limit($page->firstRow.','.$page->listRows)->select();
        foreach($voteList as $k=>$v){
            $dataIP["ip"]=$v["ip"];
            $filterInfo=M("Vote_filter")->where($dataIP)->count();
            $voteList[$k]["black"]=$filterInfo;
        }
        $this->assign("voteList",$voteList);
        $this->assign("firstRow",$page->firstRow);
        $this->assign("page",$show);
        $this->display();
    }
    //增加票数
    public function addVote($id){
        $activity=M("Activity");
        $info=$activity->where("id={$id}")->find();
        $data["votecount"]=$info["votecount"]+10;
        $voteInfo=$activity->where("id={$id}")->save($data);
        if($voteInfo){
            $this->ajaxReturn(array("status"=>"ok"));
        }else{
            $this->ajaxReturn(array("status"=>"error"));
        }
    }
    //减少票数
    public function jianshao($id){
        $activity=M("Activity");
        $info=$activity->where("id={$id}")->find();
        if($info["votecount"]<10){
            $this->ajaxReturn(array("status"=>"error"));
        }else{
            $data["votecount"]=$info["votecount"]-10;
            $voteInfo=$activity->where("id={$id}")->save($data);
            if($voteInfo){
                $this->ajaxReturn(array("status"=>"ok"));
            }else{
                $this->ajaxReturn(array("status"=>"error"));
            }
        }

    }
    //加入黑名单
    public function addBlack($id){
        $vote=M("Vote");
        $voteFilter=M("Vote_filter");
        $voteInfo=$vote->where("id={$id}")->find();
        $data["ip"]=$voteInfo['ip'];
        $info=$voteFilter->add($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok"));
        }else{
            $this->ajaxReturn(array("status"=>"error"));
        }
    }
    //移出黑名单
    public function removeBlack($id){
        $vote=M("Vote");
        $voteFilter=M("Vote_filter");
        $voteInfo=$vote->where("id={$id}")->find();
        $data["ip"]=$voteInfo['ip'];
        $info=$voteFilter->where($data)->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok"));
        }else{
            $this->ajaxReturn(array("status"=>"error"));
        }
    }
}