<?php
namespace Home\Controller;
use Think\Controller;
class HistoryController extends Controller{
    //用户浏览历史
    public function addHistory($gid){
        //判断用户是否登陆
        if(session("mid") && session("mid")>0){
            //会员登录状态，写入数据库
            $foot=D("Foot");
            $data["mid"]=session("mid");
            $data["gid"]=$gid;
            //判断该商品是否已经被浏览过
            $info1=$foot->where($data)->find();
            if($info1){
                //足迹表中如果已经存在，就改变浏览时间
                $time["addtime"]=time();
                $foot->where($data)->save($time);
            }else{
                //足迹表中不存在，就直接写入足迹表中
                $data["addtime"]=time();
                $foot->add($data);
            }
        }else{
            //会员未登录状态，存入session中
            session("goods_id.$gid",$gid);
        }
    }
    //取浏览的历史数据
    public function selectHistory(){
        $goods=D("Goods");
        $foot=D("Foot");
        //判断用户是否登陆
        if(session("mid")&&session("mid")>0){
            //用户登录状态，从数据库中取数据
            $mid=session("mid");
            $historyList=$foot->alias("f")->order("f.addtime desc")->where("f.mid={$mid}")->join("shop_goods as g on g.id=f.gid")->select();
        }else{
            //用户未登录状态，从session中取数据
            foreach(session() as $val1) {
                foreach($val1 as $val2){
                    $data['id']=$val2;
                    $historyList[]=$goods->order("addtime desc")->where($data)->find();
                    $historyList=array_reverse($historyList);
                }
            }
        }
        return $historyList;
    }
}