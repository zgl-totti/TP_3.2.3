<?php
namespace Mobile\Controller;
use Think\Controller;
class IndexController extends Controller {
    //首页展示
    public function index(){

        $goods = D('Goods');
        $list =  $goods->getGoodslist('salenum',1,9);/*销量特卖*/
        $list1 = $goods->getGoodslist("addtime",1,3);/*新品上市*/
        $list2 = $goods->getGoodslist("num",1,3);/*根据库存量推卖*/
        $list3 = $goods->where(array('display'=>1))->find();
        $list4 = M('Brand')->limit(4)->select();        /*四个品牌*/
        $list5 = M('Ads')->order('id desc')->where(array("adposition"=>0))->limit(3)->select();

        /*新加的两层*/
        $add1 = D('Category')->gettwo(2);
        $add2 = D('Category')->gettwo(1);
        $this->assign('add2',$add2);
        $this->assign('add1',$add1);
        $this->assign('list',$list);
        $this->assign('list1',$list1);
        $this->assign('list2',$list2);
        $this->assign('list3',$list3);
        $this->assign('list4',$list4);
        $this->assign('list5',$list5);
        //热门搜索
        $goodsList=$goods->field('id,goodsname')->order('salenum')->limit(8)->select();
        $this->assign('goodsList',$goodsList);
        $this->display();
    }
    /*猜你喜欢*/
    public function like(){
        /*根据点击量查找*/
        $likelist = D('Goods')->getLikeList();
        $this->assign('likelist',$likelist);
        $this->display();
    }
    /*推荐*/
    public function recommend(){
        $recommend = D('Goods')->getRecommendList();
        $this->assign('recommend',$recommend);
        $this->display();
    }

    /*淘抢购*/
    public function seckill(){
        $list = D('Goods')->seckill();
        $this->assign('time',time());
        $this->assign('list',$list);
        $this->display();
    }
}