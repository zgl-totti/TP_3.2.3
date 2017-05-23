<?php
namespace Home\Controller;
use Think\Controller;
class VoteController extends Controller{
    //投票页展示
    public function showlist(){
        //
        $sale=M('Activity');
        $where['starttime']=array('neq',0);
        $t=time();
        $where['endtime']=array(array('neq',0),array('gt',$t));
        $where["addvote"]=1;
        $salelist=$sale->alias('s')->order("s.votecount desc")->join('shop_goods g ON s.gid=g.id')->field('g.*,s.id as aid,votecount,starttime,endtime')->where($where)->select();
        //dump($salelist);
        $this->assign('salelist',$salelist);

        //轮播
        $ads=M('Ads');
        $where['adposition']=5;
        $where['top']=array('neq',0);
        $adslist5=$ads->where($where)->order('top desc')->select();
        $this->assign('adslist5',$adslist5);

        $this->display();
    }
    //投票增加
    public function addVote($id){
        $vote=D("Vote");
        $aid=$id;
        $ip=$_SERVER["REMOTE_ADDR"];
        $voteInfo=$vote->voteAdd($aid,$ip);
        if($voteInfo==1){
            $this->ajaxReturn(array("status"=>"error","msg"=>"对不起，你恶意投票，已被拉入黑名单"));
        }else{
            if($voteInfo==2){
                $this->ajaxReturn(array("status"=>"error","msg"=>"对不起，该商品的投票次数你已经用完"));
            }else{
                if($voteInfo==3){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"投票更新失败"));
                }else{
                    if($voteInfo==4){
                        $this->ajaxReturn(array("status"=>"error","msg"=>"投票插入失败"));
                    }else{
                        $num=3-$voteInfo["votenum"];
                        $this->ajaxReturn(array("status"=>"ok","msg"=>"投票成功,该商品你还有{$num}次投票机会","votecount"=>$voteInfo["votecount"]));
                    }
                }
            }
        }
      /*  if($voteInfo==1){
            $this->ajaxReturn(array("status"=>"error","msg"=>"对不起，你恶意投票，已被拉入黑名单"));
        }elseif($voteInfo==2){
            $this->ajaxReturn(array("status"=>"error","msg"=>"对不起，该商品的投票次数你已经用完"));
        }elseif($voteInfo==3){
            $this->ajaxReturn(array("status"=>"error","msg"=>"投票更新失败"));
        }elseif($voteInfo==4){
            $this->ajaxReturn(array("status"=>"error","msg"=>"投票插入失败"));
        }else{
            $num=3-$voteInfo["votenum"];
            $this->ajaxReturn(array("status"=>"ok","msg"=>"投票成功,该商品你还有{$num}次投票机会","votecount"=>$voteInfo["votecount"]));
        }*/
    }
}