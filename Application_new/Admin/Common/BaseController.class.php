<?php
namespace Admin\Common;
use Think\Controller;
class BaseController extends Controller{
    public function __construct(){
        parent::__construct();
        //判断是否登陆
     if(!session("aid")){
            $this->redirect("Login/login");
        }else{
            $auth=new \Think\Auth();
            $rule_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
            $result=$auth->check($rule_name,session('aid'));
            if(!$result){
                $this->error('您没有权限访问');
            }
        }
    }
    //空操作
    public function _empty($method){
        echo $method."---操作不存在";
    }
}