<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use \Think\Page;
class CategoryController extends BaseController
{
    //展示列表
    public function showlist()
    {
        $category = D("Category");    //实例化对象
        if (I('get.pid')) {           //获取到商品的的id
            $pid = I('get.pid');
        } else {
            $pid = 0;
        }
        $cates = $category->getChildList($pid);
        $count = $category->where("pid=$pid")->count();   //查询出总记录数据
        $page = new Page($count, 2);  //每页显示的数据
        $show = $page->show();      //分页显示输出
        $list = $category->where("pid=$pid")->limit($page->firstRow . ',' . $page->listRows)->select();
        //循环cates二维数组
        foreach ($cates as $k => $v) {
            foreach ($list as $k1 => $v1) {
                if ($v["id"] == $list[$k1]["id"]) {
                    $list[$k1]["pathname"] = $v["pathname"];
                }
            }
        }
        $this->assign('p', I('get.p'));
        //关键词模糊查询
        $this->assign('firstRow',$page->firstRow);
        $this->assign('count', $count);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign("cates", $cates);
        $this->assign('empty',"<h1 style='color:red'>暂无相关数据</h1>");
        $this->display();
    }
    /*ajax异步提交获取*/

    public function getChildCate()
    {
        if (IS_AJAX) {
            $category = M('Category');
            $where['pid'] = I('post.val');
            $arr = $category->where($where)->select();
            $this->ajaxReturn($arr);
        } else {
            return false;
        }
    }
    //添加列表
    public function addlist()
    {
        $category = M('Category');
        if (IS_POST) {
            if (!I('post.catename')) {
                $this->ajaxReturn(array("status" => "notc"));
            } elseif (!I('post.parent')) {
                $this->ajaxReturn(array("status" => "notp"));
            } else {
                if (I('post.parent') != '') {
                    if (!I('post.child')) {
                        $pid = I('post.parent');
                    } else {
                        $pid = I('post.child');
                    }
                    $list1 = $category->where("id=$pid")->field('path')->select();
                    $path = $list1[0];
                    $data['pid'] = $pid;
                    $data['catename'] = I('post.catename');
                    $a = $category->add($data);
                    $str = implode(',', $path);
                    $map['path'] = $str . ',' . $a;
                    $result = $category->where("id=$a")->save($map);
                    if ($result) {
                        $this->ajaxReturn(array("status" => "ok"));
                    } else {
                        $this->ajaxReturn(array("status" => "error"));
                    }
                }
            }
        } else {
            $map['pid'] = 0;
            $cates = $category->where($map)->select();
            $this->assign('cates', $cates);
            $this->display();
        }
    }
    public function keys()
    {
        if (I('get.keywords')) {      //根据模糊查询,查询出关键字
            $keywords = I('get.keywords');
            $where['catename'] = array('like', "%$keywords%");
        } else {$where = "";}
        $db = M('Category');
        $count = $db->where($where)->count();
        $page = new Page($count, 4);
        $show = $page->show();
        $list = $db->where($where)->limit($page->firstRow . ',' . $page->listRows)->select();
        foreach($list as $k=>$v){
            $a = explode(",","{$v['path']}");
            $str='';
            foreach($a as $k1=>$v1){
                $b = $db->where("id={$v1}")->select();
                foreach($b as $k2=>$v2){$str.=$v2['catename'].">";}
            }
            $str=rtrim($str,'>');
            $list[$k]['pathname']=$str;
        }
        $this->assign('firstRow',$page->firstRow);
        $this->assign('p', I('get.p'));
        $this->assign('keywords',$keywords);
        $this->assign('count', $count);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('empty',"<h1 style='color:red;'>暂无相关数据</h1>");
        $this->display('showlist');
    }

    public function edit()
    {
        $edit = D('Category');
        if (IS_POST) {
            $gid = I("post.");
            if(!I('post.catename')){
                $this->ajaxReturn(array("status" => "notc"));
            }
            elseif(!I('post.parent')){
                $this->ajaxReturn(array("status" => "notp"));}
            else {
                $pid = I('post.parent');
                $list1 = $edit->where("id=$pid")->select(); //得到相关东西
                $path = $list1[0]['path'];
                $data['path'] = $path . ',' . $gid['gid'];
                $data['pid'] = $pid;
                $data['catename'] = I('post.catename');
                $result = $edit->where(array('id'=>$gid['gid']))->save($data);
                if ($result) {
                    $this->ajaxReturn(array("status" => "ok"));
                } else {
                    $this->ajaxReturn(array("status" => "error"));
                }
            }
        } else {
            $where['id'] = I('get.id'); //获取商品id
            $list = $edit->where($where)->find();
            /*循环出所有的分类*/
            $a = $edit->order('path')->select();
            foreach($a as $v){
                $space = count(explode(',',$v['path']));
                $v['catename'] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $space) . $v['catename'];
                $result[]=$v;}
           $this->assign('result',$result);
            $this->assign('list', $list);
            $this->display();
        }
    }


    public function del($id){
        $where['id']=$id;
        $result = D('Category')->where($where)->delete();
        if($result){$this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));}
        else{
            $this->ajaxReturn(array("status"=>"false","msg"=>"删除失败"));
        }
    }
    public function disabled($id)
    {
        $act = D("Category");
        $map['id'] = $id;
        $data['active'] = 0;
        $pid = $act->field('path')->where($map)->find();
        $path = $pid['path'];
        $where["path"]=array('like',"{$path}%");
        $info = $act->where($where)->save($data);
        if ($info) {
            $this->ajaxReturn(array("status" => "ok", "msg" => "禁用成功"));
        } else {
            $this->ajaxReturn(array("status" => "ok", "msg" => "禁用失败"));
        }
    }
    public function enabled($id)
    {
        $act = D("Category");
        $map['id'] = $id;
        $data['active'] = 1;
        $pid = $act->field('path')->where($map)->find();
        $path = $pid['path'];
        $where["path"]=array('like',"{$path}%");
        $info = $act->where($where)->save($data);
        if ($info) {
            $this->ajaxReturn(array("status" => "ok", "msg" => "激活成功"));
        } else {
            $this->ajaxReturn(array("status" => "ok", "msg" => "激活失败"));
        }
    }
}