<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Page;
class ArticleController extends BaseController{
    //添加文档
    public function addlist(){
        $Article=D('Article');
        if(IS_POST){
            $art['title']=I("post.title");
            $art['author']=I("post.author");
            $art['cate']=I("post.cate");
            $art['content']=I("post.content");
            $art['addtime']=time();
            $info=$Article->add($art);
            if($info){
                $this->ajaxReturn(array('status'=>1,'msg'=>'文档发布成功'));
            }else{
                $this->ajaxReturn(array('status'=>2,'msg'=>'文档发布失败'));
            }
        }
        $this->display();
    }
//文档列表
        public function showlist(){
        $Article=D('Article');
        $condition=I("get.keywords")?I("get.keywords"):"";
        $where["title"]=array("like","%".$condition."%");
        if($condition){
            $count = $Article->where($where)->count();// 查询满足要求的总记录数
        }else{
            $count = $Article->count();// 查询满足要求的总记录数
        }
        $Page = new Page($count,3);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->rollPage=3;
        $Page->setConfig("first","首页");
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        if($condition){
            $list = $Article->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{
            $list = $Article->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        $this->assign('info',$list);
        $this->assign('keywords',I("get.keywords"));
        $this->assign('page',$show);
        $this->assign('firstRow',$Page->firstRow);
        $this->display();
    }
/*    public function showlist(){
        $Article=D('Article');
        $keywords=I("get.keywords");
        $where['title']=array("like","%$keywords%");
        if($keywords){
            $count = $Article->where($where)->count();// 查询满足要求的总记录数
        }else{
            $count = $Article->count();// 查询满足要求的总记录数
        }
        $Page = new Page($count,3);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        if($keywords){
            $list = $Article->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{
            $list = $Article->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        $this->assign('info',$list);// 赋值数据集
        $this->assign('keywords',$keywords);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('firstRow',$Page->firstRow);// 赋值分页输出
        $this->display(); // 输出模板
    }*/
//展示隐藏文档
    public function active(){
        $id['id']=I('get.id');
        $Article=D('Article');
        $info=$Article->where($id)->field('active')->find();
        if($info['active']==1){
           $active['active']=0;
           $info1=$Article->where($id)->save($active);
               if($info1){
                   $this->ajaxReturn(array('status'=>1,'msg'=>'文档隐藏成功'));
               }else{
                   $this->ajaxReturn(array('status'=>2,'msg'=>'文档隐藏失败'));
               }
        }else{
            $active['active']=1;
            $info1=$Article->where($id)->save($active);
            if($info1) {
                $this->ajaxReturn(array('status'=>3,'msg'=>'文档显示成功'));
            }else{
                $this->ajaxReturn(array('status'=>4,'msg'=>'文档显示失败'));
            }
        }

    }
//删除文档
    public function del(){
        $id['id']=I('get.id');
        $Article=D('Article');
       $info= $Article->where($id)->delete();
        if($info){
            $this->ajaxReturn(array('status'=>1,'msg'=>'删除成功'));
        }else{
            $this->ajaxReturn(array('status'=>2,'msg'=>'删除失败'));
        }
    } 
    //查看详情
    public function detail(){
       $id['id']=I("get.id");
        $Article=D('Article');
        $info=$Article->where($id)->find();
        $this->assign('info',$info);
        $this->display();
    }


}