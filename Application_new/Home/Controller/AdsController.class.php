<?php
namespace Home\Controller;
use Think\Controller;
class AdsController extends  Controller{
    public function index(){
        /*$ads=M('Ads');
        $where['adposition']=0;
        $adslist=$ads->where($where)->select();
        $this->assign('adslist',$adslist);
        $where['adposition']=1;
        $adslist1=$ads->where($where)->select();
        $this->assign('adslist1',$adslist1);
        $where['adposition']=2;
        $adslist2=$ads->where($where)->select();
        $this->assign('adslist2',$adslist2);
        $where['adposition']=3;
        $adslist3=$ads->where($where)->select();
        $this->assign('adslist3',$adslist3);
        $where['adposition']=4;
        $adslist4=$ads->where($where)->select();
        $this->assign('adslist4',$adslist4);
        $this->display('Home/Index/index');*/
        $goods=M('Goods');
        $where['price']=array('between','1,50');
        $goodslist1=$goods->where($where)->limit(3)->select();
        $this->assign('goodslist1',$goodslist1);

        //dump($goodslist1);

        $map['price']=array('between','50,150');
        $goodslist2=$goods->where($map)->limit(3)->select();
        $this->assign('goodslist2',$goodslist2);

        $condition['price']=array('between','150,250');
        $goodslist3=$goods->where($condition)->limit(3)->select();
        $this->assign('goodslist3',$goodslist3);

        $this->display();
    }
}