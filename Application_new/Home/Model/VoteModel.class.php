<?php
namespace Home\Model;
use Think\Model;
class VoteModel extends Model{
    //投票
    public function voteAdd($aid,$ip){
        $data["aid"]=$aid;
        $data["ip"]=$ip;
        $voteFilter["ip"]=$ip;
        $filter=M("Vote_filter");
        //判断Ip是否被拉入黑名单
        $filterInfo=$filter->where($voteFilter)->find();
        if($filterInfo){
            //被拉入黑名单
            return 1;
        }else{
            //没有被拉入黑名单
            $voteInfo=$this->where($data)->find();
            //判断该投票在数据库中是否存在
            if($voteInfo){
                //数据库中存在，更新数据
                //判断用户投票票次数是否超过指定次数
                if($voteInfo["votenum"]>=3){
                    //超过指定的投票次数
                    return 2;
                }else{
                    //投票次数未超过,更新相关数据表中的信息
                    $update["votenum"]=$voteInfo["votenum"]+1;
                    $update["votetime"]=time();
                    $info1=$this->where($data)->save($update);
                    $activityInfo=M("Activity")->where("id={$aid}")->find();
                    $updateActivity["votecount"]=$activityInfo["votecount"]+1;
                    $info2=M("Activity")->where("id={$aid}")->save($updateActivity);
                    if($info1 && $info2){
                        //投票成功，返回投票总数，余剩投票次数，排名
                        $returnInfo=$this->alias("v")->where("v.aid={$aid} AND v.ip='{$ip}'")->field("a.votecount,v.votenum")
                                         ->order("a.votecount desc")->join("shop_activity a on a.id=v.aid")->find();
                        return $returnInfo;
                    }else{
                        //更新失败
                        return 3;
                    }
                }
            }else{
                //数据库中不存在，直接插入
                $data["votetime"]=time();
                $data["votenum"]=1;
                $info1=$this->add($data);
                $activity=M("Activity")->where("id={$aid}")->find();
                $activityInfo["votecount"]=$activity["votecount"]+1;
                $info2=M("Activity")->where("id={$aid}")->save($activityInfo);
                if($info1 && $info2){
                    //投票成功，返回投票总数，余剩投票次数，排名
                    $returnInfo=$this->alias("v")->where("v.aid={$aid} AND v.ip='{$ip}'")->field("a.votecount,v.votenum")
                        ->order("a.votecount desc")->join("shop_activity a on a.id=v.aid")->find();
                    return $returnInfo;
                }else{
                    //插入失败
                    return 4;
                }
            }
        }
    }
}