<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index()
    {
        
        /*盖楼*/
        $goodslist1=$this->getGoodslist(1);
        $goodslist2=$this->getGoodslist(2);
        $goodslist3=$this->getGoodslist(3);
        $goodslist4=$this->getGoodslist(4);
        $cart=M('cart');
        if (session("mid") && session("mid") > 0){
            $mid=session('mid');
            $dat=$cart->where("mid={$mid}")->select();
            foreach ($dat as $k=>$v){
                $abc[]=$v['buynum'];
                $sum='';
                foreach ($abc as $k1=>$v1){
                    $sum+=$v1;
                }
            }
        }
        else{
//                $abc=$_SESSION['mycart'];
            $abc=session("mycart");
                $sum = '0';
                foreach ($abc as $k=>$v){
                    $sum+=$v['buynum'];
                }
            }
        session("sum",$sum);
        $this->assign('sum',$sum);
        $show=$this->lastlist();
        $this->assign('lastlist',$show);
        //循环商标
        $brand = M('Brand');
//        改动第二处
       /* $bpic = $brand->field('logo')->limit(10)->select();*/
        $bpic = $brand->limit(10)->select();
//            改动结束
        $activity =M('Goods');
        $act = M('Activity');
//        第一排促销活动
        $firsttj = $activity->order('addtime desc')->where(array("activity"=>0,'display'=>1))->limit(10)->select();
        //循环促销商品
        $h = $act->field('gid,endtime')->limit(5)->select();
        foreach ($h as $k=>$v){
            $where1['id']=$v['gid'];
            $cxlist = $activity->where($where1)->select();
            foreach($cxlist as $k1=>$v1){
                $cxlist[$k1]['endtime']=$v['endtime'];
            }
            $sumlist[]=$cxlist[0];
        }
        //循环推荐商品
        $tjlist = $activity->order('salenum desc')->where(array("activity"=>0,'display'=>1))->limit(8)->select();
        //主页菜单
        $cate = M('Category');
        $firstcate = $cate->where(array('pid'=>0,'active'=>1))->field('catename,id,path' )->select();
        foreach ($firstcate as $k=>$v){
            $where['pid'] = $v['id'];
            $where['active']=1;
            $firstcate[$k]['second'] = $cate->where($where)->field('catename,id,path')->select();
            foreach ($firstcate[$k]['second'] as $k1=>$v1){
                $firstcate[$k]['second'][$k1]['third'] = $cate->where(array('pid'=>$v1['id'],'active'=>1))->select();
            }
        }

        //猜我喜欢
        $like = $activity->order('clicknum desc')->limit(6)->select();
        //获取session
        $mid = session('mid');
        $this->assign('time',time());
        $this->assign('mid',$mid);
        $this->assign('like',$like);
        $this->assign('firstcate',$firstcate);
//        $this->assign('secondcate',);
        $this->assign('sumlist',$sumlist);
        $this->assign('tjlist',$tjlist);
        $this->assign('firsttj',$firsttj);
        $this->assign('bpic',$bpic);
        $this->assign('goodslist1',$goodslist1);
        $this->assign('goodslist2',$goodslist2);
        $this->assign('goodslist3',$goodslist3);
        $this->assign('goodslist4',$goodslist4);
        /*广告部分*/
        $where['top']=array('neq',0);
        $ads=M('Ads');
        $where['adposition']=0;
        $adslist=$ads->where($where)->order('top desc')->select();
        $this->assign('adslist',$adslist);
        $where['adposition']=1;
        $adslist1=$ads->where($where)->order('top desc')->select();
        $this->assign('adslist1',$adslist1);
        $where['adposition']=2;
        $adslist2=$ads->where($where)->order('top desc')->select();
        $this->assign('adslist2',$adslist2);
        $where['adposition']=3;
        $adslist3=$ads->where($where)->order('top desc')->select();
        $this->assign('adslist3',$adslist3);
        $where['adposition']=4;
        $adslist4=$ads->where($where)->order('top desc')->select();
        $this->assign('adslist4',$adslist4);
        $this->assign('indexTag','index');
        $nasd=A('News');
        $newsinfo=$nasd->getNewsList();
        $this->assign('xinwen',$newsinfo);
        //文档列表
        $articleInfo=A('Home/Article')->article();
        $this->assign('articleInfo',$articleInfo);
        $this->display();

    }


    public function getGoodslist($cid){
        $cate = M('Category');
        $where['id'] = $cid;
        $pathInfo = $cate->where($where)->field('path')->select();
        $path=$pathInfo[0]['path'];
        $map['path']=(array('like',"{$path}%"));
        $cateArr = $cate->where($map)->field('id')->select();
        $catestr='';
        foreach ($cateArr as $k=>$v){
            $catestr.=$v['id'].',';
        }
        $catestr=substr($catestr,0,-1);
        $goods = M('Goods');
        $where1['cid']=(array('in',"{$catestr}"));
        $where1['delete']=0;
        $where1['display']=1;
        $goodslist = $goods->where($where1)->limit(8)->select();
        return $goodslist;
    }
/*广告页面*/
    public function ads(){
        $list1= $this->getGoodslist(3);
        $list2 =$this->getGoodslist(5);
        $list3 = $this->getGoodslist(1);
        $list4 = $this->getGoodslist(2);
        $this->assign('list1',$list1);
        $this->assign('list2',$list2);
        $this->assign('list3',$list3);
        $this->assign('list4',$list4);
        $this->display();
    }
/**/
     public function buyCart()
     {
         $cart = M('cart');
         $goods=D("Goods");
             if (session("mid") && session("mid") > 0) {
                 $user["mid"]=session("mid");
                 $ceshi=$cart->where($user)->field('id')->select();
                 if ($ceshi){
                 $data=$cart->where($user)
                 ->join('shop_goods ON shop_cart.gid=shop_goods.id')
                 ->select();
                 echo json_encode($data);
                 }
             }else{
//                 foreach(array_reverse($_SESSION["mycart"]) as $k=>$v){
                   foreach(array_reverse(session("mycart")) as $k=>$v){
                     $data[$k]=$goods->getSessionGoods($v["gid"]);
                     $data[$k]["gid"]=$v["gid"];
                     $data[$k]["buynum"]=$v["buynum"];
                 }
                 echo json_encode($data);
             }
     }
    public function buycar(){
        $cart=M('cart');
        if (session("mid") && session("mid") > 0){
            $user['mid']=session('mid');
            $dat=$cart->where($user)->select();
            $this->assign('buynum',$dat);
            $this->display('Home/Index/index');
        }
    }
    public function lastlist(){
         $goods=M('order_goods');
         $show=$goods
            ->join('shop_order ON shop_order_goods.oid=shop_order.id')
            ->join('shop_goods ON shop_order_goods.gid=shop_goods.id')
            ->where('order_status>1')
             ->order('shop_order.addtime desc')
            ->field('shop_order.order_price,shop_order.addtime,shop_goods.goodsname,shop_goods.pic,shop_goods.id')
            ->select();
         return $show;
    }
}