<?php
namespace Home\Controller;
use Think\Controller;
class CategoryController extends Controller{
    //获得详情页同类商品推荐
    public function getLikeCate($gid){
        //根据商品ID，查出商品类别
        $cate=D("Category");
        $cateInfo=$cate->getFondCate($gid);
        return $cateInfo;
    }
}