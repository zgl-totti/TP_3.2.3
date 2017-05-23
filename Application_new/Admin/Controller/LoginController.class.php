<?php
namespace Admin\Controller;
use Think\Controller;

use Think\Verify;

class LoginController extends Controller{
    //展示列表
//    public function index(){
//        $this->display('login');
//    }
    public function login(){
        if (IS_POST) {
              $admin=D('Admin');
              $data=$admin->create();
//              $data['adminname']=I("post.adminname");
              $data['password']=md5(trim(I("post.password")));
            //此时，用户名已经通过验证，如果下面的$info为空，证明管理员密码错误
              $info=$admin->where($data)->find();
              if($info){
                  //判断管理员是否禁用
                  if($info["active"]==0){
                      $this->ajaxReturn(array('status'=>'error','msg'=>'该账号已被禁用'));
                   }else{
                      session('aid',$info['id']);
                      session('admin_name',$info['adminname']);
                      $this->ajaxReturn(array('status'=>'ok','msg'=>'登陆成功'));
                  }

              }else{
                  $this->ajaxReturn(array('status'=>'error','msg'=>'登陆失败'));
              }
        }else{
            $this->display();
       }
    }

    public function verify(){
        $verify=new Verify();
        $verify->fontSize=30;
        $verify->length=2;
        $verify->codeSet="12346578";
        $verify->useNoise=false;
        $verify->useCurve=false;
        $verify->entry();
    }

    public function checkVerify(){
        $verify = new verify();
        $code=I('post.verify');
        if($verify->check($code, '')){
            echo  'true';
        }else{
            echo 'false';
        }
    }
    public function checkName(){
        $admin=D('Admin');
        $adminname=trim(I("post.adminname"));
        $info=$admin->where("adminname='{$adminname}'")->find();
        if($info){
            echo 'true';
        }else{
            echo 'false';
        }

    }
    public function logout(){
            session('admin_id',null);
            session('admin_name',null);
            if(session('admin_id')=='' && session('admin_name')==''){
                $this->ajaxReturn('ok');
            }else{
                $this->ajaxReturn('error');
            }



    }

}