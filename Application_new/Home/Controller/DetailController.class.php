<?php
namespace Home\Controller;
use Think\Controller;
use Home\Controller\HistoryController;
use Home\Controller\CategoryController;
class DetailController extends Controller{
    //详情页
    //根据商品ID，获得商品的详情信息
    public function detail(){
        $gid=I("get.gid");
        //获得商品名字，商品价格，市场价格，上架时间
        $goodsDetail=D("Goods");
        $history=new HistoryController();
        $cateLike=new CategoryController();
        //调用模型方法，获得商品的详情信息
        $detailInfo=$goodsDetail->getGoodsDetail($gid);
        //调用模型的相关方法，获得商品的所有图片信息
        $goodsPicInfo=$goodsDetail->getGoodsPic($gid);
        //把该商品存入浏览历史中
        $history->addHistory($gid);
        //从浏览历史中取出该商品的信息
        $historyInfo=$history->selectHistory();
        //获得同类商品推荐
        $cateLikeList=$cateLike->getLikeCate($gid);
        $zongji=D("goods_comment");
        $zong=$zongji->where("gid=$gid")->count();
        //分配给模板
        $this->assign("gid",$gid);
        $this->assign("zong",$zong);
        $this->assign("detailInfo",$detailInfo);
        $this->assign("goodsPicInfo",$goodsPicInfo);
        $this->assign("historyInfo",$historyInfo);
        $this->assign("cateLikeList",$cateLikeList);
        //调用模板展示
        $comment=M('goods_comment');
        $count=$comment->count('id');
        /*$com=$comment
            ->join('shop_member ON shop_goods_comment.mid=shop_member.id')
            ->where('shop_goods_comment.isshow=1')
            ->field('commentcontent,username,replycontent,shop_goods_comment.addtime,isreply')
            ->select();$this->assign('com',$com);*/

        //收藏
        $goods=D('Goods');
        $collect=D('collect');
        if(session('mid') && session('mid')>0){
            $mid=session('mid');
            $col1=$collect->where("gid=$gid AND mid=$mid")->select();
            if($col1){
                $col = $goods->where("id=$gid")->field('id,bid')->select();
            }else{
                $col = $goods->where("id=$gid")->field("id")->select();
            }
        }else{
                $col = $goods->where("id=$gid")->field('id')->select();
        }
        $this->assign('col',$col);
        $this->assign('count',$count);
        $this->display();
    }
    //去登陆
    public function tologin(){
        $this->display();
    }
    
    //留言展示
    /*public function shower(){
        $gid=I("post.gid/d");
            if($gid){
                $comment=M('goods_comment');
                $com=$comment
                    ->join('shop_member ON shop_goods_comment.mid=shop_member.id')
                    ->where('shop_goods_comment.isshow=1')
                    ->where("gid={$gid}")
                    ->field('commentcontent,username,replycontent,shop_goods_comment.addtime,isreply')
                    ->select();
                

                $this->ajaxReturn($com);
            }else{
                $this->ajaxReturn('失败');
            }
    }*/
    //留言异步
    public function shower()
    {
        if (IS_AJAX) {
            $gid = I("post.gid");
            $cond = I("post.star");//获得前台获得评价等级
            if ($cond == 1) {
                $condition['start'] = array('between', '4,5');
            } elseif ($cond == 2) {
                $condition['start'] = 3;
            } elseif ($cond == 3) {
                $condition['start'] = array('between', '1,2');
            } else {
                $condition['start'] = array('between', '1,5');
            }
            if ($gid) {
                $where['start'] = $condition['start'];
                $com = M('Goods_comment')->alias('gc')->where($where)->join('shop_member as m on m.id=gc.mid')
                    ->field('username,pic,gc.*')->where(array('gc.gid' => $gid))->select();
                foreach ($com as $k => $v) {

                    $map['gid']=$gid;
                    $map['mid']=$v['mid'];
                    $map['addtime']=array(array('gt',$v['addtime']-10),array('lt',$v['addtime']+10)) ;
                    $comment_pic = M('Comment_pic')->field('picname')->where($map)->limit(3)->select();

                    $com[$k]['picname'] = $comment_pic;
                    $com[$k]['addtime'] = date("Y-m-d H:i:s",$v["addtime"]);

                $cou = M('Goods_comment')->alias('gc')->join('shop_member as m on m.id=gc.mid')
                    ->field('username,pic,gc.*')->where(array('gc.gid' => $gid))->count();
                $condit['start'] = array('between', '4,5');
                $cong = M('Goods_comment')->alias('gc')->join('shop_member as m on m.id=gc.mid')
                    ->where($condit)
                    ->field('username,pic,gc.*')->where(array('gc.gid' => $gid))->count();
                $cad = round((float)($cong / $cou)*(100),2);
                $com[$k]['cad'] =$cad;
                }
                    $this->ajaxReturn($com);
            } else {
                $this->ajaxReturn('失败');
            }
        /*} else {
            $gid = I("post.gid");
            $cou = M('Goods_comment')->alias('gc')->join('shop_member as m on m.id=gc.mid')
                ->field('username,pic,gc.*')->where(array('gc.gid' => $gid))->count();
            $condit['start'] = array('between', '4,5');
            $cong = M('Goods_comment')->alias('gc')->join('shop_member as m on m.id=gc.mid')
                ->where($condit)
                ->field('username,pic,gc.*')->where(array('gc.gid' => $gid))->count();
            $cad = $cong / $cou;
            $this->assign('cad', $cad);
            dump($cad);
            $this->display();*/
        }//获得的所有等级评价
    }








    //收藏

    public function collect(){
        $collect=D('Collect');
        if(session('mid') && session('mid')>0){
            $col['mid']=session('mid');
            $col['gid']=I('post.id');
            $info=$collect->where($col)->select();
            if($info){
                $collect->where($col)->delete();
                $this->ajaxReturn(array('status'=>1,'msg'=>'取消收藏成功'));
            }else{
                $col['addtime']=time();
                $info2=$collect->add($col);
                if($info2){
                    $this->ajaxReturn(array('status'=>2,'msg'=>'收藏成功'));
                }else{
                    $this->ajaxReturn(array('status'=>3,'msg'=>'收藏失败'));
                }
            }
        }else{
            $this->ajaxReturn(array('status'=>4));
        }


    }


}