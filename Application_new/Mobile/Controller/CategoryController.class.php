<?php
namespace Mobile\Controller;
use Think\Controller;
class CategoryController extends Controller{
    //分类展示
    public function cateList(){
        //分类
        $cate=M('Category');
        $where['pid']=0;
        $catelist=$cate->where($where)->select();
        $this->assign('catelist',$catelist);
        //新品推荐
        $goods=M('Goods');
        $goodslist=$goods->order('addtime desc')->limit(4)->select();
        $this->assign('goodslist',$goodslist);

        //热销专区
        $goodssale=$goods->order('salenum desc')->limit(4)->select();
        $this->assign('salelist',$goodssale);

        $this->display();
    }
}