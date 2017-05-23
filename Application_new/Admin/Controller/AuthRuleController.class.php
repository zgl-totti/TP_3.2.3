<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
class AuthRuleController extends BaseController {
    //菜单列表
    public function showlist(){
        $rule=D('AuthRule');
        $pid=I("get.pid")?I("get.pid"):0;
        $ruleList=$rule->getRuleList($pid);

        $this->assign('ruleList',$ruleList);

        $this->display();
    }
    //添加菜单
    public function addlist(){
        if(IS_AJAX){
            $rule=D('AuthRule');
            $data=$rule->create();
            if($data){
                $nid=$rule->addRule($data);
                if($nid){
                    $this->success('权限添加成功',U('showlist'));
                }else{
                    $this->error('权限添加失败');
                }
            }else{
                $this->error($rule->getError());
            };
        }else{
            if(I('get.pid')){
                $this->assign('pid',I('get.pid'));
                $this->assign('pname',I('get.pname'));
            }
            $this->display();
        }
    }
    //删除菜单
    public function delete($id){
        //获取要删除的权限的主键ID
        $authRule=D("Auth_rule");
        $info=$authRule->where("id={$id}")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"权限删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"权限删除失败"));
        }
    }
    //编辑菜单
    public function edit(){
        $authRule=D("Auth_rule");
        //判断是否有表单提交
        if(IS_POST){
            //获取要修改的表单信息
            $id=I("post.id");
            $title=I("post.title");
            $name=I("post.name");
            //根据权限ID，查找出该权限的基本信息
            $data["id"]=$id;
            $data["title"]=$title;
            $data["name"]=$name;
            $data["edittime"]=time();
            $res=$authRule->save($data);
            if($res){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"权限编辑成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"权限编辑失败"));
            }
        }else{
            //根据权限ID查找出该权限对应的信息
            $id=I("get.id");
            $info=$authRule->edit($id);
            $this->assign("info",$info);
            $this->display();
        }
    }
}