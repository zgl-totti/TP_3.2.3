<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
class IndexController extends BaseController {
        public function index()
        {
            $this->display();
        }
        public function top(){
            $this->display();
        }
        public function left(){
            //获取侧栏菜单
            $nav=D('AdminNav');
            $navList=$nav->getNavTree();
            /* echo "<pre>";
             print_r($navList);*/
            $this->assign('navList',$navList);
            $this->display();
        }
        public function main(){
//            $admin=M("Admin");
//            session("aid",1);
//            $admin_info=$admin->where(array("aid"=>session("aid")))->find();//本次登陆的管理员信息
//            $this->assign("lastlogintime",date("Y-m-d H:i:s",$admin_info["lastlogintime"]));//上次登录时间
//            $this->assign("lastloginip",$admin_info["lastloginip"]);//上次登陆IP

            $this->assign("softWare",$_SERVER["SERVER_SOFTWARE"]);//服务器软件
            $this->assign("developLang","PHP");//开发语言
            $this->assign("developSql","MYSQL");//数据库类型
            $this->assign("domainName",$_SERVER["SERVER_NAME"]);//域名
            
            
            //统计图开始
            $paihang=M('goods');
            $rank=$paihang->order('salenum desc')->limit('10')->select();
            $this->assign('rank',$rank);
            //统计图结束
            $bbv=A('member');
            $ccc=$bbv->level();
            $this->assign('count',$count);
            $this->assign('count1',$count1);
            $this->assign('count2',$count2);
            $this->assign('count3',$count3);
            $this->assign('count4',$count4);
        }
        public function footer(){
            $this->display();
        }
}