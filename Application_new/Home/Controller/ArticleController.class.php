<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends Controller{
    public function article(){
        $Article=D('Article');
        $info=$Article->field('title')->group('title')->select();
//        print_r($info);
        foreach($info as $k=>$v){
            $info[$k][]=$Article->where($v)->where("active=1")->field('cate,id')->select();
        }
      //print_r($info);
        return   $info;
    }
//文档详情页
    public function articledetail(){
        $rand=rand(1,13);
        $Article=D('Article');
        $id['id']=I('get.id');
        $info=$Article->where($id)->select();
        $info1=$Article->field('cate,addtime,id')->limit($rand,7)->select();
        $this->assign('info',$info);
        $this->assign('info1',$info1);
        $this->display();
    }
}