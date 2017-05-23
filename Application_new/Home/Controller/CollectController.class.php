<?php
namespace Home\Controller;
use Think\Controller;
class CollectController extends Controller{
    //获得收藏列表
    public function collectList(){
        $this->display();
    }
    //添加收藏商品
    public function addCollect(){
        $collect=D("Collect");
        //先判断用户是否登陆
        if(session("mid") && session("mid")>0){
            $data["mid"]=session("mid");
            $data["gid"]=I("get.gid");
            $selNum=$collect->where($data)->find();
            if($selNum){
                $this->ajaxReturn(array("status"=>"error","msg"=>"该商品已经收藏过了"));
            }else{
                $data["addtime"]=time();
                $collect_id=$collect->add($data);
                if($collect_id){
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"收藏成功"));
                }else{
                    $this->ajaxReturn(array("status"=>"error","msg"=>"收藏失败"));
                }
            }
        }else{
            $this->ajaxReturn(array("status"=>"login","msg"=>"登陆后才能收藏"));
        }
    }
    //收藏商品时，没登陆，去登陆
    public function tologin(){
        $this->display();
    }
}