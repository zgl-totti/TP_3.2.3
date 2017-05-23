<?php
namespace Mobile\Controller;
use Think\Controller;
class AddressController extends  Controller{
    //增加收货地址
    public function addAddress(){

    }
    //修改收货地址
    public function editAddress(){
        $address=D("Address");
        $mid=session("mid");
        if(IS_POST){
            $oid=I("post.oid");
            $data["address"]=I("province")."-".I("city")."-".I("town")."-".I("jiedao");
            $data["name"]=I("post.name");
            $data["mobile"]=I("post.mobile");
            $data["id"]=I("post.id");
            $data["mid"]=$mid;
            //判断默认地址按钮是否被选中
            if(I("post.isdefault")){
                $data["isdefault"]=I("post.isdefault");
                $info["isdefault"]=0;
                $id1=$address->where("mid={$data['mid']}")->save($info);
                $id2=$address->where("mid={$mid} AND id={$data['id']}")->save($data);
                if($id1 && $id2){
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"编辑成功","oid"=>$oid));
                }else{
                    $this->ajaxReturn(array("status"=>"error","msg"=>"编辑失败"));
                }
            }else{
                $id3=$address->save($data);
                if($id3){
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"编辑成功","oid"=>$oid));
                }else{
                    $this->ajaxReturn(array("status"=>"error","msg"=>"编辑失败"));
                }
            }
        }else{
            $id=I("get.id");
            $oid=I("get.oid");
            $addInfo=$address->where("id={$id}")->find();
            $addInfo["oid"]=$oid;
            $this->assign("addInfo",$addInfo);
            $this->display();
        }
    }
    //其他收货地址【地址管理中】
    public function otherAddress(){
        $address=D("Address");
        $mid=session("mid");
        $addressInfo=$address->field("id,name,address,mobile,postcode,isdefault")->where("mid={$mid}")
            ->order("isdefault desc,addtime desc")->limit(0,3)->select();
        $this->assign("addressInfo",$addressInfo);
        $this->assign("oid",I("get.oid"));
        $this->display();
    }
    //订单页设置默认地址
    public function setDefault(){
        $address=D("Address");
        $oid=I("post.oid");
        $id=I("post.radio");
        $mid=session("mid");
        $isDefault["isdefault"]=1;//设置为默认地址
        $info1=$address->where("id={$id}")->save($isDefault);
        $notDefault["isdefault"]=0;//其他的取消取消设置
        $info2=$address->where("mid={$mid} AND id!={$id}")->save($notDefault);
        if($info1&&$info2){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"设置成功","oid"=>$oid));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"设置失败"));
        }

    }
}