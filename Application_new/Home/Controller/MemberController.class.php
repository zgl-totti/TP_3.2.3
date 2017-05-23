<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
use Home\Controller\CartController;
class MemberController extends Controller {
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
                        $cart=new CartController();
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
    //退出
    public function logout(){
        if(IS_GET){
            session("mid",null);
            session("username",null);
            $this->ajaxReturn(array("status"=>"ok","msg"=>"退出成功"));
        }

    }
   /* //注册页面
    public function reg(){
        $this->display('Index/index');
    }*/
    //注册
    public function register(){
        $member=D('Member');
        if(IS_AJAX){
            $data=$member->create();
            if($data){
                $data['username']=trim(I('post.username'));
                $data['password']=md5(trim($data['password']));
                $data['addtime']=time();
//                $mid=$member->register1($data);
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

    //验证用户名是否被占用
    public function chkUserName(){
        $username=I('post.username');
        if(M('Member')->where(array('username'=>$username))->find()){
            echo  'false';
        }else{
            echo 'true';
        }
    }

    //产生验证码
    public function verify(){
        $config =    array(
            'fontSize'    =>    18,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
            'useImgBg'   => false,
            'useCurve'  => false,
        );
        $Verify = new Verify($config);
        $Verify->entry();
    }

    //检查验证码是否匹配
    public function chkVerify(){
        $verify = new Verify();
        $code=I('post.verify');
        if($verify->check($code, '')){
            echo 'true';
        }else{
            echo 'false';
        };
    }
    //检测是否注册过
    public function chkUserName1(){
        $username=I('post.username');
        if(M('Member')->where(array('username'=>$username))->find()){
            echo  'true';
        }else{
            echo 'false';
        }
    }
    //找回密码
    public function findPwd(){
        

        $this->display();
    }

}

