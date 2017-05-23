<?php
namespace Mobile\Model;
use \Think\Model;
class CategoryModel extends Model{
    //详情页根据分类获取
    public function getCateList($cid){
        $search = $this->where(array("id"=>$cid))->field('path')->find();
        $map['id']=array('in',$search['path']);
        $search1 = $this->where($map)->select();
        $str = '';
        foreach ($search1 as $k=>$v){$str.=$v['id'].",";}
        $str = trim($str,',');
        return $str;
    }
//    积分商城获取水果类的商品
    public function getGoodsList(){
        $where['path']=array("like",'5%');
        $cate = $this->where($where)->select();
        $str = '';
        foreach ($cate as $k=>$v){$str .= $v['id'].",";}
        $str = trim($str,',');
        $map['cid']=array("in",$str);
        $goods = D('Goods')->where($map)->select();
        return  $goods;
    }
    /*多加的两层*/
    public function gettwo($c){
        $data['path']=array("like","{$c}%");
        $add1 = D('Category')->where($data)->select();
        $str = '';
        foreach ($add1 as $k=>$v){$str .=$v['id'].",";}
        $str = trim($str,',');
        $date['cid'] = array('in',$str);
        $add2 = D('Goods')->where($date)->limit(9)->select();
        return $add2;
    }
}