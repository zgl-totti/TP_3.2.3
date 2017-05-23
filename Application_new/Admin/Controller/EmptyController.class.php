<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
class EmptyController extends BaseController{
    //空控制器
    public function index(){
        echo CONTROLLER_NAME."控制器不存在";
    }
    //空操作
    public function _empty($method){
        echo $method."操作不存在";
    }
}