<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use \Think\Page;
class AdsController extends BaseController{
    //展示列表
    public function showlist(){
        $ads=M('Ads');// 实例化Ads对象
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        //查询
        if(IS_POST){
            $adname=I('post.adname');
            $where['adname']=array('like',"%$adname%");
            $where['adposition']=I('post.adposition');
            $this->assign('scwords',$adname);
            $count= $ads->where($where)->count();// 查询满足要求的总记录数
            $page = new Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $page->show();// 分页显示输出
            $list = $ads->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $count      = $ads->count();// 查询满足要求的总记录数
            $page       = new Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $page->show();// 分页显示输出
            $list = $ads->order('adposition,top desc')->limit($page->firstRow.','.$page->listRows)->select();
        }
        $this->assign('empty','<span style="color: #f00;font-size: 32px;margin-left: 100px;">没有数据Q_Q</span>');
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('firstRow',$page->firstRow);// 赋值分页输出
        $this->display(); // 输出模板
    }


    //添加列表
    public function addlist(){
        $ads=M('Ads');
        $adname=I('post.adname');
        if(IS_AJAX){
            if(!$adname){
                $this->ajaxReturn(array('status'=>'error','msg'=>'广告标题未填写'));
            }elseif($ads->where("adname='{$adname}'")->find()){
                $this->ajaxReturn(array('status'=>'error','msg'=>'广告已存在'));
            }else{
                $upload=new \Think\Upload();
                $upload->maxSize=3145728;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath = './Public/Admin/Uploads/'; // 设置附件上传根目录
                $upload->autoSub = true;
                $upload->subName = 'ads';
                // 上传文件
                $info = $upload->upload();
                if ($info) {// 上传错误提示错误信息
                    $Data=$ads->create();
                    $Data['adname']=I('post.adname');
                    $Data['adlogo']=$info['pic']['savename'];
                    $Data['adposition']=I('post.adposition');
                    /*$Data['top']=time();*/
                    $Data['top']=0;
                    //print_r($Data);
                    if($ads->add($Data)){
                        $this->ajaxReturn(array('status'=>'ok','msg'=>'添加完成,是否继续添加?'));
                    }else{
                        $this->ajaxReturn(array('status'=>'error','msg'=>'添加失败,是否重新添加?'));
                    }
                } else {// 上传成功
                    $this->ajaxReturn(array('status'=>'error','msg'=>'上传图片失败'));
                }
            }
        }else{
            $this->display();
        }

    }
    //禁用
    public function disabled($id){
        $ads=D("Ads");
        $data["id"]=$id;
        $data["top"]=0;
        //执行修改
        $info=$ads->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"隐藏成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"隐藏失败"));
        }
    }
    //启用
    public function enabled($id){
        $ads=D("Ads");
        $data["id"]=$id;
        $data["top"]=time();

        //执行修改
        //$where['top']=array('neq',0);
        $where["id"]=$id;
        $position=$ads->field('adposition')->where($where)->find();
        $map['adposition']=$position['adposition'];
        $map['top']=array('neq',0);
        $num=$ads->where($map)->count();
        if($num<3){
            $info=$ads->save($data);
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"展示成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"展示失败"));
            }
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"展示失败,已有3张图片展示"));
        }


    }
    public function zhiding($id){
        $ads=M("Ads");
        /*$data["id"]=$id;*/
        $data["top"]=time();
        //执行修改
        $data['id']=$id;
        $info=$ads->save($data);
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"置顶成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"置顶失败"));
            }
    }
    public function del(){
        if(IS_AJAX){
            $Ads=M('Ads');// 实例化User对象
            $where['id']=I('get.id');
            $info=$Ads->where($where)->delete();

            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
            }

        }
    }
    public function edictlist(){
        $ads=M('Ads');// 实例化User对象
        if(IS_GET){
            $where['id']=I('get.id');
            $list = $ads->where($where)->select();
            $this->assign('list',$list);// 赋值数据集
        }elseif(IS_AJAX){
            $where['adname']=I('post.adname');
            $adname=I('post.adname');
            $where['id']=array('neq',I('post.id'));
            $sum=$ads->where($where)->find();
            if($adname && $sum){
                $this->ajaxReturn(array('status'=>'error','msg'=>'广告已存在'));
            }elseif(!$adname){
                $this->ajaxReturn(array('status'=>'error','msg'=>'广告标题未填写'));
            } else{
                $upload=new \Think\Upload();
                $upload->maxSize=3145728;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath = './Public/Admin/Uploads/'; // 设置附件上传根目录
                $upload->autoSub = true;
                $upload->subName = 'ads';
                // 上传文件
                $info = $upload->upload();
                $ads=M('Ads');// 实例化User对象
                $wheres['id']=I('post.id');
                $Data['adname']=I('post.adname');
                $Data['adposition']=I('post.adposition');
                $Data['top']=I('post.top')?time():0;
                if($info){
                    $Data['adlogo']=$info['pic']['savename'];
                }
                $v=$ads->where($wheres)->save($Data);
                if($v){
                    $this->ajaxReturn(array('status'=>'ok','msg'=>'修改完成'));
                }else{
                    $this->ajaxReturn(array('status'=>'error','msg'=>'修改失败'));
                }

            }
        }
        $this->display(); // 输出模板
    }


}