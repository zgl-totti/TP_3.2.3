<?php
namespace Mobile\Controller;
use Think\Controller;
class ProductListController extends Controller{
    //产品页展示
    public function productList(){
        session('search',null);
        $goods=M('Goods');
        $cate=M('Category');
        if(IS_AJAX){//价格，销量排序
            $pid=$this->getPid(I('get.cateID'));
            $map['cid']=array('in',$pid);
            if(I('get.listorder')==1){
                $goodslist=$goods->where($map)->order('salenum')->select();
            }elseif(I('get.listorder')==2){
                $goodslist=$goods->where($map)->order('price')->select();
            }else{
                $goodslist=$goods->where($map)->select();
            }
            if($goodslist){
                $this->ajaxReturn($goodslist);
            }else{
                $this->ajaxReturn(array('status'=>'error'));
            }
        }else{//分类展示
            $cid=$this->getPid(I('get.id'));
            $where['cid']=array('in',$cid);
            $goodslist=$goods->where($where)->select();
            $condition['id']=I('get.id');
            $catename=$cate->field('catename')->where($condition)->find();
            $id=I('get.id');
            $this->assign('id',$id);
            $this->assign('catename',$catename['catename']);
            $this->assign('goodslist',$goodslist);
            $this->assign('empty','<span style="color: #c2ccd1;font-size: 32px;margin:0 auto;">没有数据^^</span>');
            $this->display();
        }
    }
    //商品查询
    public function searchList(){
        session('search',I('get.search'));
        if(IS_AJAX){
            $goodslist=$this->getGoods(I('post.search'));
            if($goodslist){
                $this->ajaxReturn($goodslist);
            }else{
                $this->ajaxReturn(array('status'=>'error'));
            }
        }else{
            $goodslist=$this->getGoods(I('get.search'));
            $this->assign('goodslist',$goodslist);
            $this->assign('search',session('search'));
            $this->assign('empty','<span style="color: #c2ccd1;font-size: 32px;margin:0 auto;">没有数据^^</span>');
            $this->display();
        }

    }
    //品牌搜索
    public function brandList(){
        session('search',null);
        $goods=M('Goods');
        session('bid',I('get.bid'));
        /*session('bid',39);*/
        if(IS_AJAX){
            $map['bid']=session('bid');
            if(I('get.listorder')==1){
                $goodslist=$goods->where($map)->order('salenum')->select();
            }elseif(I('get.listorder')==2){
                $goodslist=$goods->where($map)->order('price')->select();
            }else{
                $goodslist=$goods->where($map)->select();
            }
            if($goodslist){
                $this->ajaxReturn($goodslist);
            }else{
                $this->ajaxReturn(array('status'=>'error'));
            }
        }else{
            $brand=M('Brand');
            $where['bid']=I('get.bid');
            /*$where['bid']=39;*/
            $goodslist=$goods->where($where)->select();
            $condition['id']=I('get.bid');
            /*$condition['id']=39;*/
            $brandname=$brand->field('bname')->where($condition)->find();
            $bid=I('get.bid');
            /*$bid=39;*/
            $this->assign('bid',$bid);
            $this->assign('brandname',$brandname['bname']);
            $this->assign('goodslist',$goodslist);
            $this->assign('empty','<span style="color: #c2ccd1;font-size: 32px;margin:0 auto;">没有数据^^</span>');
            $this->display();
        }
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
    //通过goodsname模糊查询商品信息
    public function getGoods($name){
        $goods=M('Goods');
        $where['goodsname']=array('like','%'.$name.'%');
        $goodslist=$goods->where($where)->select();
        return $goodslist;
    }
}