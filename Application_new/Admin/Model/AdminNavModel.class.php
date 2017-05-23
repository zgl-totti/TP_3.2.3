<?php
namespace Admin\Model;
use Think\Model;
class AdminNavModel extends Model{
    protected $_validate=array(
        //array('验证字段','验证规则','错误提示',['验证条件','附加规则','验证时间']);
        //验证规则：require 字段值必须、email 邮箱、url URL地址、currency 货币、number 数字、double浮点、integer整数、zip邮政编码、english英文
        //验证条件：0代表字段存在时必须验证、1代表字段必须验证、2代表字段存在而且不为空时验证
        //附加规则:默认为regex,附加规则的值决定了验证规则的值
        //验证时间:1代表添加时验证，2代表更新时验证，3代表任何情况下都验证
        array('navname','require','菜单名称不能为空',0,'regex',1),
        array('navurl','require','菜单链接不能为空',0,'regex',1)
    );
    public function addNav($data){
        $nid=$this->field("pid,navname,navurl,priority")->add($data);
        if($nid){
            if($data['pid']==0){
                $path=$nid;
            }else{
                $path=$this->where(array('id'=>$data['pid']))->getField('path');
                $path.=','.$nid;
            }
            $save['path']=$path;
            $save['id']=$nid;
            $row=$this->save($save);
            return $row;
        }else{
            return $nid;
        }
    }

    public function getNavList($pid){
        $navList=$this->order('priority asc')->where("pid={$pid}")->select();
        foreach($navList as $k=>$v){
            $count=count(explode(',',$v['path']));
            $navList[$k]['level']=$count;
        }

        return $navList;
    }

    // 显示有权限的菜单
    public function getNavTree(){
        $nav=$this->where(array('pid'=>0))->order('priority')->select();
        if($nav){
            $auth=new \Think\Auth();
            foreach($nav as $k1=>$v1){
                if ($auth->check($v1['navurl'],session('aid'))) {

                    $child=$this->where(array('pid'=>$v1['id']))->order('priority')->select();
                    foreach($child as $k2=>$v2){
                        if (!$auth->check($v2['navurl'],session('aid'))) {
                            // 删除无权限的菜单
                            unset($child[$k2]);
                        }
                    }
                    $nav[$k1]['child']=$child;
                }else{
                    // 删除无权限的菜单
                    unset($nav[$k1]);
                }
            }

            return $nav;
        }else{
            return false;
        }
    }
    //设置优先级
    public function setPriority($data){
        $row=$this->save($data);
        return $row;
    }
    //获得要修改菜单项的基本信息
    public function editNav($id){
        //1,查找该菜单项的基本信息
        $navInfo=$this->field("priority,navname,navurl,pid,id")->where("id={$id}")->find();
        //2,该菜单项的父ID,查找该菜单项的父级菜单名称
        $pName=$this->field("navname")->where("id={$navInfo['pid']}")->find();
        $navInfo["pName"]=$pName["navname"];
        return $navInfo;
    }
}