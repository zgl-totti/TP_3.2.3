<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
class RoleController extends BaseController{
    //展示列表
    public function showlist(){
        $this->display();
    }
    //添加列表
    public function addlist(){
        $this->display();
    }
}