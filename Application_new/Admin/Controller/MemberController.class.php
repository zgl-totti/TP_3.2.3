<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Page;
class MemberController extends BaseController{
    //展示列表
    public function showlist(){
        $member=M('Member');
//        $abc=$member->join('shop_level ON shop_member.level=shop_level.id')->select();
        $condition['username']=array('like',"%".I('get.keywords')."%");
        $count=$member->where($condition)->count();
        $page=new Page($count,5);
        $page->setConfig(first,首页);
        $page->setConfig(end,末页);
        $page->setConfig(next,下页 );
        $page->setConfig(prev,上页 );
        $page->rollPage=5;
        $show=$page->show();
        $list=$member->order("addtime")->join('shop_level ON shop_member.level=shop_level.lid')->where($condition)->limit($page->firstRow.",".$page->listRows)->select();
        $map['key']=I('get.keywords');
        foreach ($map as $key=>$v){
            $page->parameter[$key]=urlencode($v);
        }
        $this->assign('keywords',I('get.keywords'));
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->assign('firstRow',$page->firstRow);// 赋值分页输出
        $this->display();

    }
    //添加列表
    public function addlist(){
        $this->display();
    }

    /**
     * 删除成员
     */
    public function del($id){
        $member=D("Member");
        $info=$member->where("id='{$id}'")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
    //禁用
    public function disabled($id){
        $member=D("Member");
        $where["id"]=$id;
        $data["active"]=0;
        $info=$member->where($where)->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"禁用成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"禁用失败"));
        }
    }
    //启用
    public function enabled($id){
        $member=D("Member");
        $where["id"]=$id;
        $data["active"]=1;
        $info=$member->where($where)->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"启用成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"启用失败"));
        }
    }
    //详情
    public function detail(){
        $id=I('get.id');
        $member=D('Member');
        $where['id']=$id;
        $abc=$member->where($where)->join('shop_level ON shop_member.level=shop_level.lid')->select();
        $this->assign('id',I('get.id'));
        $this->assign('abc',$abc);
        $this->display();
    }
    public function level(){
        $member=M('Member');
//        $abc=$member->join('shop_level ON shop_member.level=shop_level.id')->select();
        $count=$member->where('level=1')->count();
        $page=new Page($count,20);
        $page->setConfig(first,首页);
        $page->setConfig(end,末页);
        $page->setConfig(next,下页 );
        $page->setConfig(prev,上页 );
        $page->rollPage=5;
        $show=$page->show();
        $list=$member->order("addtime")->join('shop_level ON shop_member.level=shop_level.lid')->where('level=1')->limit($page->firstRow.",".$page->listRows)->select();
        $count1=$member->where('level=2')->count();
        $list1=$member->order("addtime")->join('shop_level ON shop_member.level=shop_level.lid')->where('level=2')->limit($page->firstRow.",".$page->listRows)->select();
        $count2=$member->where('level=3')->count();
        $list2=$member->order("addtime")->join('shop_level ON shop_member.level=shop_level.lid')->where('level=3')->limit($page->firstRow.",".$page->listRows)->select();
        $count3=$member->where('level=4')->count();
        $list3=$member->order("addtime")->join('shop_level ON shop_member.level=shop_level.lid')->where('level=4')->limit($page->firstRow.",".$page->listRows)->select();
        $count4=$member->where('level=5')->count();
        $list4=$member->order("addtime")->join('shop_level ON shop_member.level=shop_level.lid')->where('level=5')->limit($page->firstRow.",".$page->listRows)->select();
        $map['key']=I('get.keywords');
        foreach ($map as $key=>$v){
            $page->parameter[$key]=urlencode($v);
        }
        $this->assign('keywords',I('get.keywords'));
        $this->assign('count',$count);
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('count3',$count3);
        $this->assign('count4',$count4);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->assign('list1',$list1);
        $this->assign('list2',$list2);
        $this->assign('list3',$list3);
        $this->assign('list4',$list4);
        $this->assign('firstRow',$page->firstRow);// 赋值分页输出
        $this->display();

    }
}