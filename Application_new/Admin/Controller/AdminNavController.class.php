<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
class AdminNavController extends BaseController{
    //菜单列表
    public function showlist(){
        $nav=D('AdminNav');
        $pid=I("get.id")?I("get.id"):0;
        $navList=$nav->getNavList($pid);
        $this->assign('navList',$navList);
        $this->display();
    }
    //添加菜单
    public function addlist(){
        if(IS_AJAX){
            $nav=D('AdminNav');
            $data=$nav->create();
            if($data){
                $nid=$nav->addNav($data);
                if($nid){
                    $this->success('菜单添加成功',U('showlist'));
                }else{
                    $this->error('菜单添加失败');
                }
            }else{
                $this->error($nav->getError());
            };
        }else{
            if(I('get.pid')){
                $this->assign('pid',I('get.pid'));
                $this->assign('pname',I('get.pname'));
            }
            $this->display();
        }
    }
    //编辑菜单
    public function edit(){
        $adminNav=D("Admin_nav");
        if(IS_POST){
            //执行编辑
            $data=I("post.");
            $data["edittime"]=time();
            $info=$adminNav->save($data);
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"编辑成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"编辑失败"));
            }
        }else{
            //展示编辑页面
            //获得要修改的菜单ID
            $id=I("get.id");
            //根据菜单ID，查找该菜单的一些基本信息
            $adminNavInfo=$adminNav->editNav($id);
            $this->assign("navInfo",$adminNavInfo);
            $this->display();
        }
    }
    //菜单删除
    public function delete($id){
        //根据菜单项主键ID进行菜单项删除
        $adminNav=D("Admin_nav");
        $info=$adminNav->where("id={$id}")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
}