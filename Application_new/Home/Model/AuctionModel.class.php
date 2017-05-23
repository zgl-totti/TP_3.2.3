<?php
namespace Home\Model;
use Think\Model;
class AuctionModel extends Model{
    //获得每个拍卖品参与的竞拍总人数
    public function getPerpleNum($ag_id){
        $perpleNum=$this->where("ag_id={$ag_id}")->group("mid")->count();
        return $perpleNum;
    }
    //获得每个会员参与每个拍卖品参与的次数
    public function getTotalNum($mid,$ag_id){
        $perNum=$this->where("ag_id={$ag_id} AND mid={$mid}")->count();
        return $perNum;
    }
    //判断在自己出价之后，是否有人出价(根据时间)
    public function getAddtime($mid,$ag_id){
        $addtime1=$this->where("mid={$mid} AND ag_id={$ag_id}")->max("addtime");
        $addtime2=$this->where("mid!={$mid} AND ag_id={$ag_id}")->max("addtime");
        if($addtime2>=$addtime1){
            return false;
        }else{
            return true;
        }
    }
}