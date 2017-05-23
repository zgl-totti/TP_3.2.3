<?php
namespace Admin\Model;
use Think\Model;
class AuthRuleModel extends Model{
    protected $_validate=array(
        //array('验证字段','验证规则','错误提示',['验证条件','附加规则','验证时间']);
        //验证规则：require 字段值必须、email 邮箱、url URL地址、currency 货币、number 数字、double浮点、integer整数、zip邮政编码、english英文
        //验证条件：0代表字段存在时必须验证、1代表字段必须验证、2代表字段存在而且不为空时验证
        //附加规则:默认为regex,附加规则的值决定了验证规则的值
        //验证时间:1代表添加时验证，2代表更新时验证，3代表任何情况下都验证
        array('title','require','权限名称不能为空',0,'regex',1),
        array('name','require','权限规则不能为空',0,'regex',1)
    );
    public function addRule($data){
        $nid=$this->field("pid,title,name")->add($data);
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
    //权限列表
    public function getRuleList($pid){
//        $navList=$this->order('path desc')->where("pid={$pid}")->select();
        $navList=$this->where("pid={$pid}")->select();
        foreach($navList as $k=>$v){
            $count=count(explode(',',$v['path']));
            $navList[$k]['level']=$count;
        }
        return $navList;
    }

    public function getRuleTree(){
        $rule=$this->where(array('pid'=>0))->select();
        if($rule){
            foreach($rule as $k=>$v){
                $child=$this->where(array('pid'=>$v['id']))->select();
                foreach($child as $k1=>$v1){
                    $child1=$this->where(array('pid'=>$v1['id']))->select();
                    $child[$k1]['child']=$child1;
                }
                $rule[$k]['child']=$child;
            }
           return $rule;
        }else{
            return false;
        }
    }

    public function editRule($data){
        $row=$this->save($data);
        return $row;
    }

    //获取权限编辑的信息
    public function edit($id){
        //查找出该权限的基本信息
        $info=$this->where("id={$id}")->field("title,name,id,pid")->find();
        //查找出该权限的父级
        $pName=$this->where("id={$info['pid']}")->field("title")->find();
        $info["pName"]=$pName["title"];
        return $info;
    }

}