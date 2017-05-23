<?php
namespace Mobile\Controller;
use Think\Controller;
class ProductListController extends Controller{
    //产品页展示
    public function productList(){
        $goods=M('Goods');
        $cate=M('Category');
        $listGoods=I('get.listGoods');
        if(!empty($listGoods)){
            $pid=$this->getPid(I('get.cateID'));
            $id=I('get.cateID');
            $condition['id']=I('get.cateID');
            $catename=$cate->field('catename')->where($condition)->find();
            $map['cid']=array('in',$pid);
            if(I('get.listGoods')==1){
                $goodslist=$goods->where($map)->order('salenum')->select();
            }elseif(I('get.listGoods')==2){
                $goodslist=$goods->where($map)->order('price')->select();
            }
        }else{
            $cid=$this->getPid(I('get.id'));
            $where['cid']=array('in',$cid);
            $goodslist=$goods->where($where)->select();
            $condition['id']=I('get.id');
            $catename=$cate->field('catename')->where($condition)->find();
            $id=I('get.id');
        }
        $this->assign('listGoods',$listGoods);
        $this->assign('id',$id);
        $this->assign('catename',$catename['catename']);
        $this->assign('goodslist',$goodslist);
        $this->display();


    }
    //获取顶级分类下的商品的cid
    public function getPid($id){
        $cate=M('Category');
        $where['path']=array('like',$id.',%');
        $parent=$cate->field('id')->where($where)->select();
        $pid=array();
        foreach($parent as $k=>$v){
            $pid[]=$v['id'];
        }
        return $pid;
        /*return $parent;*/
    }
}