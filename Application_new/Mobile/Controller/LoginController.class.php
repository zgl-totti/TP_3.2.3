<?php
namespace Mobile\Controller;
use Think\Controller;
class LoginController extends Controller{
    //登陆
    public function login()
    {
        if(IS_AJAX){
            $data=M('Member')->where(array('username' => trim(I('post.username')), 'password' => md5(trim(I('post.password')))))->find();
            if($data){
                if($data["active"]==1){
                    session('mid',$data['id']);
                    session('username',$data['username']);
                    //把session中的商品转移到数据库中  开始
                    $cart=A("Cart");
                    $cart->removeCart();
                    //把session中的商品转移到数据库中  结束
                    $this->ajaxReturn(array("status"=>1,"info"=>"登录成功"));
                }else{
                    $this->ajaxReturn(array("status"=>0,"info"=>"该账号被禁用"));
                }
            }else{
                $this->ajaxReturn(array("status"=>0,"info"=>"用户名或密码不正确"));
            }
        }
        else{
            $this->display();
        }
    }
    //注册
    public function register(){
        if(IS_AJAX){
            $member=D('Member');
            $data=$member->create();
            if($data){
                $data['username']=trim(I('post.username'));
                $data['password']=md5(trim($data['password']));
                $data['addtime']=time();
                $mid=$member->field('username,password,addtime')->add($data);;
                if($mid){
                    session('mid',$mid);
                    session('username',trim(I('post.username')));
                    $this->success('用户注册成功');
                }else{
                    $this->error('用户注册失败');
                }
            }else{
                $this->error($member->getError());
            }
        }else{
            $this->display();
        }
    }
    //验证是否重复注册
    public function chkUserName(){
        $username=I('post.username');
        if(M('Member')->where(array('username'=>$username))->find()){
            echo  'false';
        }else{
            echo 'true';
        }
    }
}