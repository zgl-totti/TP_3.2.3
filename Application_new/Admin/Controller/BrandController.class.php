<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use \Think\Page;
class BrandController extends BaseController{
    //删除
    public function del(){
        if(IS_AJAX){
            $Brand=M('Brand');// 实例化User对象
            $where['id']=I('get.id');
            $info=$Brand->where($where)->delete();
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
            }

        }
    }
    //编辑
    public function edictlist(){
        if(IS_GET){
            $Brand=M('Brand');// 实例化User对象
            $where['id']=I('get.id');
            $list = $Brand->where($where)->select();
            //print_r($list);
            $this->assign('list',$list);// 赋值数据集
        }
        if(IS_AJAX){
            $Brand=M('Brand');// 实例化User对象
            $where['id']=I('post.id');
            $Data=$Brand->create();
            $Data['bname']=I('post.bname');
            $Data['description']=I('post.description');
            $Data['addtime']=time();
            $v=$Brand->where($where)->save($Data);
            //print_r($Data);
            if($v){
                $this->ajaxReturn(1);
            }else{
                $this->ajaxReturn(0);
            }
        }else{
            $this->display(); // 输出模板
        }

    }


    //展示列表
    public function showlist(){
        $Brand=M('Brand');// 实例化User对象

// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        //查询
        if(IS_POST){
            $bname=I('post.bname');
            $where['bname']=array('like',"%$bname%");
            //$where['addtime']=I('post.addtime');
            ///print_r($where);
            $this->assign('scwords',$bname);
            $count= $Brand->where($where)->count();// 查询满足要求的总记录数
            $page = new Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $page->show();// 分页显示输出
            $list = $Brand->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $count      = $Brand->count();// 查询满足要求的总记录数
            $page       = new Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $page->show();// 分页显示输出
            $list = $Brand->limit($page->firstRow.','.$page->listRows)->select();
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('firstRow',$page->firstRow);// 赋值分页输出
        $this->display(); // 输出模板
    }
    //禁用品牌
    public function disabled($id){
        $brand=D("Brand");
        $data["id"]=$id;
        $data["active"]=0;
        //执行修改
        $info=$brand->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"禁用成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"禁用失败"));
        }
    }
    //启用品牌
    public function enabled($id){
        $brand=D("Brand");
        $data["id"]=$id;
        $data["active"]=1;
        //执行修改
        $info=$brand->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"启用成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"启用失败"));
        }
    }
    //添加列表
    public function addlist(){
        $Brand=M('Brand');
        if(IS_AJAX) {
            $bname=I("post.bname");
            //判断品牌名是否为空
            if(empty($bname)){
                $this->ajaxReturn(array("status"=>"error","msg"=>"品牌名不能为空"));
            }else{
                //判断品牌名称是否重复
                if($Brand->where("bname='{$bname}'")->find()){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"品牌已存在"));
                }else{
                        $upload = new \Think\Upload();// 实例化上传类
                        $upload->maxSize = 3145728;// 设置附件上传大小
                        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                        $upload->rootPath = './Public/Admin/Uploads/'; // 设置附件上传根目录
                        $upload->autoSub = true;
                        $upload->subName = 'brand';
                        // 上传文件
                        $info = $upload->upload();
                        $Data['bname']=I('post.bname');
                        $Data['description']=I('post.description');
                        $Data['addtime']=time();
                        $flag=$Brand->add($Data);
                        if($flag){
                            //判断是否有图片上传
                            if ($info) {
                                $where['id']=$flag;
                                $brandpic['logo']=$info['pic']['savename'];
                                $num=$Brand->where($where)->save($brandpic);
                                if($num){
                                    $this->ajaxReturn(array('status'=>'ok','msg'=>'添加完成,是否继续添加?'));
                                }else{
                                    $this->ajaxReturn(array('status'=>'error','msg'=>'图片上传失败'));
                                }
                            } else {// 上传成功
                                $this->ajaxReturn(array('status'=>'ok','msg'=>'添加完成,是否继续添加?'));
                            }
                        }else{
                            $this->ajaxReturn(array('status'=>'error','msg'=>'添加失败'));
                        }
                }

            }

        }else{
            $this->display();
        }
    }

}