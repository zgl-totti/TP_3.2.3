<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
class AuthGroupController extends BaseController {
    //列表
    public function showlist(){
        $group=D('AuthGroup');
        $groupList=$group->getGroupList();
        foreach($groupList as $k=>$v){
            $adminInfo=M('AuthGroupAccess')->alias('g')->join('shop_admin a ON g.uid=a.id')->field('a.adminname')->where("g.group_id={$v['id']}")->select();
            $str='';
            foreach($adminInfo as $a){
                $str.=$a['adminname'].',';
            }
            $groupList[$k]['member']=substr($str,0,-1);
        }
        //echo "<pre>";
        //print_r($groupList);
        $this->assign('groupList',$groupList);
        $this->display();
    }
    //添加
    public function addlist(){
        if(IS_AJAX){
            $group=D('AuthGroup');
            $data=$group->create();
            if($data){
                $gid=$group->addGroup($data);
                if($gid){
                    $this->success('角色添加成功',U('showlist'));
                }else{
                    $this->error('角色添加失败');
                }
            }else{
                $this->error($group->getError());
            };
        }else{
            $this->display();
        }
    }

    //向组中添加成员
    public function addMember(){
        $groupAccess=D("Auth_group_access");
        $admin=D("Admin");
        if(IS_POST){
            $data=I("post.");
            $id=$groupAccess->add($data);
            if($id){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"添加成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"添加失败"));
            }
        }else{
            $group_id=I("get.group_id");//角色组ID号
            //根据角色组ID号，查出该组的所有成员
            $group_member=$groupAccess->field("uid")->where("group_id={$group_id}")->select();
            //把查出来的ID变成一维数组
            $groupID=array();
            foreach($group_member as $key=>$value){
                $groupID[]=$value["uid"];
            }
            //查找出所有的管理员
            $memberInfo=$admin->field("id,adminname")->select();
            //查出所有不在指定角色中的其他成员
            $info=array();
            foreach($memberInfo as $k=>$v){
                    if(!in_array($v["id"],$groupID)){
                        //把不在该组的成员查找出来
                        $data["uid"]=$v["id"];
                        $data["adminname"]=$v["adminname"];
                        $info[]=$data;
                    }
            }
            $this->assign("info",$info);
            $this->assign("group_id",$group_id);
            $this->display();
        }
    }

    //给组分配权限
    public function allocateRule(){
        $group=D('AuthGroup');
        if(IS_AJAX){
            $data['id']=I('post.id');
            $data['rules']=implode(',',I('post.rules'));
            $row=$group->editRule($data);
            if($row){
                $this->success('分配成功',U('showlist'));
            }else{
                $this->success('分配失败');
            }
        }else{
            //获取所有权限规则
            $rule=D('AuthRule');
            $ruleList=$rule->getRuleTree();
            /* echo "<pre>";
             print_r($ruleList);*/
            //获取组信息
            $id=I('get.gid');
            $groupInfo=$group->find($id);
            $groupInfo['rules']=explode(',',$groupInfo['rules']);

            $this->assign('ruleList',$ruleList);
            $this->assign('groupInfo',$groupInfo);
            $this->display();
        }

    }
    //删除
    public function delete($id){
        //根据角色表主键ID进行角色删除
        $role=D("Auth_group");
        $memberRole=D("Auth_group_access");
        //1，删除角色表中的角色信息
        $info1=$role->where("id={$id}")->delete();
        //2，删除用户角色表中的对应信息（根据角色表主键ID）
        $info2=$memberRole->where("group_id={$id}")->delete();
        if($info1 || $info2){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
    //编辑
    public function edit(){
        $role=D("Auth_group");//角色表
        $memberRole=D("Auth_group_access");//用户角色表
        //判断是否有表单提交
        if(IS_POST){
            //有表单提交
            $group_id=I("post.group_id");
            $title=I("post.title");
            $uids=I("post.uid");
            //先删除,在修改
            $memberRole->where("group_id={$group_id}")->delete();
            //执行角色表的修改
            $data1["id"]=$group_id;
            $data1["title"]=$title;
            $info1=$role->save($data1);
            //执行用户角色表的修改
            $data2["group_id"]=$group_id;
            foreach($uids as $k=>$v){
                $data2["uid"]=$v;
                $info2=$memberRole->add($data2);
            }
            if($info1 || $info2){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"编辑成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"编辑失败"));
            }
        }else{
            $id=I("get.id");
            //没有表单提交
            //1,获取角色的名称
            $data=$role->field("id,title")->where("id={$id}")->find();
            //2,获得该角色下的所有管理员ID
            $Uids=$memberRole->alias("aga")->field("uid,adminname")->where("group_id={$id}")->join('shop_admin a on a.id=aga.uid')->select();
            $this->assign("data",$data);
            $this->assign("Uids",$Uids);
            $this->display();
        }
    }
}