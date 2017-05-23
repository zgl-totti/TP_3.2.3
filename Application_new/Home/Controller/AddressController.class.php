<?php
namespace Home\Controller;
use Think\Controller;
class AddressController extends Controller{
    //订单页和会员中心编辑收货地址
    public function editAddress(){
        $address=D("Address");
        if(IS_POST){
            $data["mid"]=session("mid");//当前登陆账号的ID
            $data["name"]=I("post.name");
            $data["mobile"]=I("post.mobile");
            $data["postcode"]=I("post.postcode");
            $data["address"]=I("post.province").'-'.I("post.city").'-'.I("post.town").'-'.I("post.jiedao");
            $data["isdefault"]=1;
            $data["addtime"]=time();
            if(!$data["name"]){
                $this->ajaxReturn(array("status"=>"error","msg"=>"收货人姓名不能为空"));
            }else{
                if(!$data["mobile"]){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"手机号不能为空"));
                }else{
                    if(!$data["address"]){
                        $this->ajaxReturn(array("status"=>"error","msg"=>"收货地址不能为空"));
                    }else{
                        $mid=session("mid");
                        $info["isdefault"]=0;
                        $id=$address->add($data);
                        if($id){
                            $address->where("mid={$mid} AND id!=$id")->save($info);
                            $this->ajaxReturn(array("status"=>"ok","msg"=>"地址添加成功"));
                        }else{
                            $this->ajaxReturn(array("status"=>"error","msg"=>"地址添加失败"));
                        }
                    }
                }
            }

        }else{
            $this->display();
        }
    }
    //订单页设置默认地址
    public function setDefault($id){
        $address=D("Address");
        $mid=session("mid");
        $isDefault["isdefault"]=1;//设置为默认地址
        $info1=$address->where("id={$id}")->save($isDefault);
        $notDefault["isdefault"]=0;//其他的取消取消设置
        $info2=$address->where("mid={$mid} AND id!={$id}")->save($notDefault);
        if($info1&&$info2){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"设置成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"设置失败"));
        }

    }
    //获得最新地址
    public function getAddress($mid){
        $address=D("Address");
        $addressInfo=$address->field("id,name,address,mobile,postcode,isdefault")->where("mid={$mid}")
                             ->order("isdefault desc,addtime desc")->limit(0,4)->select();
        return $addressInfo;
    }
    //会员中心删除地址
    public function del($id){
        $address=M("Address");
        $info=$address->where("id={$id}")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
    //会员中心修改收货地址
    public function updateAddress(){
        $aid=I("get.id");//要修改的地址主键ID
        $address=D("Address");
        //判断是否有表单提交
        if(IS_POST){
            //有表单提交，执行修改
            $data["id"]=I("post.id");
            $data["name"]=I("post.name");
            $data["mobile"]=I("post.mobile");
            $data["postcode"]=I("post.postcode");
            $data["address"]=I("post.province").'-'.I("post.city").'-'.I("post.town").'-'.I("post.jiedao");
            $data["addtime"]=time();
            if(!$data["name"]){
                $this->ajaxReturn(array("status"=>"error","msg"=>"收货人姓名不能为空"));
            }else{
                if(!$data["mobile"]){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"手机号不能为空"));
                }else{
                    if(!I('post.province')&&!I('post.city')&&!I('post.town')&&!I('post.jiedao')){
                        $this->ajaxReturn(array("status"=>"error","msg"=>"收货地址信息不能为空"));
                    }else{
                        $id=$address->save($data);
                        if($id){
                            $this->ajaxReturn(array("status"=>"ok","msg"=>"修改成功"));
                        }else{
                            $this->ajaxReturn(array("status"=>"error","msg"=>"修改失败"));
                        }
                    }
                }
            }
        }else{
            //没有表单提交，弹出修改框
            //根据地址表主键ID，查出用户地址信息
            $addressInfo=$address->where("id={$aid}")->find();
            $this->assign("addressInfo",$addressInfo);
            $this->display();
        }
    }
}