<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Page;
class NewsController extends BaseController{
    //展示列表
    public function showlist(){
        $news=D("News");
        //实例化分页类 传入总记录数和每页显示的记录数为 2
        //拼装查询条件
        $condition["title"]=array("like","%".I("get.keywords")."%");
        //查询满足查询条件的总记录数
        $count=$news->where($condition)->count("id");
        $page=new Page($count,4);
        $page->rollPage=3;
        $page->setConfig("first","首页");
        //分页显示输出(分页的页码输出)
        $show=$page->show();
        //进行分页数据查询 注意limit方法的参数要使用Page类的属性
        //查询满足查询条件的数据
        $list = $news->where($condition)->order('addtime')->limit($page->firstRow.','.$page->listRows)->select();
        $map["key"]=I("get.keywords");
        foreach($map as $key=>$v){
            $page->parameter[$key]=urlencode($v);
        }
        //赋值数据集
        $this->assign("list",$list);
        $this->assign("firstRow",$page->firstRow);
        $this->assign("page",$show);
        $this->assign("keywords",I("get.keywords"));
        $this->display();
    }
    //添加列表
    public function addlist(){
        $news=D("News");
        if(IS_POST){
            $data=I("post.");
            $data["addtime"]=time();
            $info=$news->add($data);
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"发布成功,继续发布？"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"发布失败"));
            }
        }else{
            $this->display();
        }
    }
    //删除新闻
    public function del($id){
        $news=D("News");
        $info=$news->where("id='{$id}'")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
    //新闻隐藏
    public function tohide($id){
        $news=D("News");
        $data["id"]=$id;
        $data["isshow"]=0;
        $info=$news->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"隐藏成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"隐藏失败"));
        }
    }
    //新闻显示
    public function toshow($id){
        $news=D("News");
        $data["id"]=$id;
        $data["isshow"]=1;
        $info=$news->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"显示成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"显示失败"));
        }
    }
    //新闻置顶
    public function totop($id){
        $news=D("News");
        $data["id"]=$id;
        $data["top"]=1;
        $info=$news->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"置顶成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"置顶失败"));
        }
    }
    //取消置顶
    public function canceltop($id){
        $news=D("News");
        $data["id"]=$id;
        $data["top"]=0;
        $info=$news->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"取消成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"取消失败"));
        }
    }
}