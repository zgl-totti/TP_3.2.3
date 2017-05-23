<?php
namespace Home\Model;
use Think\Model;
class CategoryModel extends Model{
    //获得详情页同类商品分类
    public function getFondCate($gid){
        //根据商品ID，查出商品类别
        $cateOne=$this->table("shop_goods")->field("cid")->where("id={$gid}")->find();
        $cate=$cateOne["cid"];
        //根据类别ID，找出父ID
        $p_cate=$this->field("pid")->where("id={$cate}")->find();
        $pid=$p_cate["pid"];
        //根据类别ID查出所有相似商品
        $cateList=$this->alias("c")->order("g.addtime desc")->where("c.pid={$pid} AND g.id!={$gid}")
                       ->limit(0,3)->join("shop_goods as g on g.cid=c.id")->select();
        return $cateList;
    }
    /*积分兑换详情页中获取同类推荐*/
    public function getLikeGoods($gid){
        $cateOne=$this->table("shop_goods")->field("cid")->where("id={$gid}")->find();
        $cate=$cateOne["cid"];
        //根据类别ID，找出父ID
        $p_cate=$this->field("path")->where("id={$cate}")->find();
        //根据类别ID查出所有相似商品
        $pid=substr($p_cate["path"],0,1);
        $where['path']=array('like',"{$pid}%");
        $list = $this->field('id')->where($where)->select();
        $str = '';
        foreach($list as $k=>$v){$str .= $v['id'].",";}
        $str = substr($str,0,-1);
        $map['cid']=array('in',$str);
        $map['display']=0;
        $goods = M('Goods')->where($map)->limit(3)->select();
        return $goods ;
    }
}