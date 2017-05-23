<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use \Think\Upload;
class IntegralController extends BaseController
{

    public function showlist(){
        if(IS_GET){
            $keywords = I('get.keywords');
            $where['goodsname'] = array('like',"%$keywords%");
        }else{
            $where ='';
        }
        $count = D('Integral')->where($where)->count();
        $page = new \Think\Page($count,8);
        $show = $page->show();
        $list = D('Integral')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('count',$count);
        $this->assign('empty',"<h1 style='color:red'>不好意思,没有相关数据</h1>");
        $this->assign('list',$list);
        $this->display();
    }


    public function addlist(){

        if(IS_POST){
            $goods=D('Integral');
            $data=$goods->create();
            if($data){
                $common=A('Common');
                $info=$common->uploadPic();
                if(is_array($info)){
                    $image=new \Think\Image();
                    //获取图片文件路径
                    $filename='./Public/Admin/Uploads/'.$info[0]['savepath'].$info[0]['savename'];
                    //缩略
                    $thumb_100 = './Public/Admin/Uploads/'.$info[0]['savepath'].'thumb100/100_'.$info[0]['savename'];
                    $thumb_350 ='./Public/Admin/Uploads/'.$info[0]['savepath'].'thumb350/350_'.$info[0]['savename'];
                    $thumb_800 = './Public/Admin/Uploads/'.$info[0]['savepath'].'thumb800/800_'.$info[0]['savename'];
                    $image->open($filename)->thumb(100,100)->save($thumb_100);
                    $image->open($filename)->thumb(500,500)->save($thumb_350);
                    $image->open($filename)->thumb(800,800)->save($thumb_800);
                }else{
                    $this->error($info);
                }
                $data['addtime']=time();
                // $data['detail']=htmlspecialchars($data['detail']);

                $data['pic']=$info[0]['savename'];
                $gid=$goods->addGoods($data);

                if($gid){
                    session('lastGid',$gid);
                    $this->success('商品添加成功');
                }else{
                    $this->error('商品添加失败');
                };
            }else{
                $this->error($goods->getError());
            }
        }else{
            $this->display();
        }
    }

    public function uploadGoodsPic(){
        $common=A('Common');
        $info=$common->uploadPic();

        if(is_array($info)){
            $image=new \Think\Image();
            //获取图片文件路径
            $filename='./Public/Admin/Uploads/'.$info['file']['savepath'].$info['file']['savename'];
            //缩略
            $thumb800='./Public/Admin/Uploads/'.$info['file']['savepath'].'thumb800/800_'.$info['file']['savename'];
            $thumb350='./Public/Admin/Uploads/'.$info['file']['savepath'].'thumb350/350_'.$info['file']['savename'];
            $thumb100='./Public/Admin/Uploads/'.$info['file']['savepath'].'thumb100/100_'.$info['file']['savename'];
            $image->open($filename)->thumb('800','800')->save($thumb800);
            $image->open($filename)->thumb('350','350')->save($thumb350);
            $image->open($filename)->thumb('100','100')->save($thumb100);

            $data['iid']=session('lastGid');
            $data['picname']=$info['file']['savename'];
           M('Integral_pic')->add($data);

        }else{
            $this->error($info);
        }
    }

    public function del($id){
        $result = D('Integral')->where(array("id"=>$id))->delete();
        if($result){
            $this->ajaxReturn(array("status"=>1,"msg"=>"删除成功"));
        }
        $this->ajaxReturn(array("status"=>0,"msg"=>"删除失败"));
    }
    public function disabled($id){
        $result = D('Integral')->where(array("id"=>$id))->save(array("display"=>0));
        if($result){$this->ajaxReturn(array("status"=>1,"msg"=>"成功下架"));}
        else{$this->ajaxReturn(array("status"=>0,"msg"=>"下架失败"));}
    }
    public function enabled($id){
        $result = D('Integral')->where(array("id"=>$id))->save(array("display"=>1));
        if($result){$this->ajaxReturn(array("status"=>1,"msg"=>"成功上架"));}
        else{$this->ajaxReturn(array("status"=>0,"msg"=>"上架失败"));}
    }
    public function edit(){

        if (IS_POST) {
            $goods = D('integral');
            //收集表单信息
            $data = $goods->create();
            if ($data) {
                $data['addtime'] = time();  //获取当前时间更新
                //更新商品信息
                if ($goods->save($data)){
                    //更新图片信息
                    if ($_FILES) {
                        $goodsInfo = $goods->field('pic')->find(I('post.id'));
                        $upload = new \Think\Upload();
                        $upload->maxSize = 3145728;// 设置附件上传大小
                        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型D
                        $upload->rootPath = './Public/Admin/Uploads/'; // 设置附件上传根目录
                        $upload->savePath = "integral/";
                        $upload->autoSub = false;
                        $info = $upload->upload();
                        foreach ($info as $key => $val) {
                            $image = new \Think\Image();
                            //获取图片文件路径
                            $filename = './Public/Admin/Uploads/' . $val['savepath'] . $val['savename'];
                            //1.先设置路径
                            $thumb_100 = './Public/Admin/Uploads/'.$val['savepath'].'/thumb100/100_'.$val['savename'];
                            $thumb_350 ='./Public/Admin/Uploads/'.$val['savepath'].'/thumb350/350_'.$val['savename'];
                            $thumb_800 = './Public/Admin/Uploads/'.$val['savepath'].'/thumb800/800_'.$val['savename'];
                            $image->open($filename)->thumb(100,100)->save($thumb_100);
                            $image->open($filename)->thumb(500,500)->save($thumb_350);
                            $image->open($filename)->thumb(800,800)->save($thumb_800);
                            //修改主图
                            if ($key == 0) {
                                $data['id'] = I('post.id');
                                $data['pic'] = $val['savename'];
                                if ($goods->save($data)) {
                                    //删除原主图(这会出问题)
                                    unlink('./Public/Admin/Uploads/integral/' . $goodsInfo['savename']);
                                    unlink('./Public/Admin/Uploads/integral/thumb100/100_' . $goodsInfo['savename']);
                                    unlink('./Public/Admin/Uploads/integral/thumb350/350_' . $goodsInfo['savename']);
                                    unlink('./Public/Admin/Uploads/integral/thumb800/800_' . $goodsInfo['savename']);
                                } else {$this->error('主图更新失败');};
                            } else if ($key > 0) { //修改辅图
                                $pid = $key;
                                $data['id'] = $pid;
                                $data['picname'] = $val['savename'];
                                if (M('integral_pic')->save($data)) {
                                    $picInfo = M('integral_pic')->field('picname')->find($pid);
                                    //删除原图
                                    unlink('./Public/Admin/Uploads/integral/' . $picInfo['savename']);
                                    unlink('./Public/Admin/Uploads/integral/thumb100/100_' . $picInfo['savename']);
                                    unlink('./Public/Admin/Uploads/integral/thumb350/350_' . $picInfo['savename']);
                                    unlink('./Public/Admin/Uploads/integral/thumb800/800_' . $picInfo['savename']);
                                }
                            }
                        }
                    }
                    $this->success('商品更新成功');
                } else {
                    $this->error('商品更新失败');
                }
            }
            else {$this->error($goods->getError());}
        } else {
            $gid = trim(I('get.id'));
            $goods = D('Integral')->where(array('id'=>$gid))->find();
            $pics = M('integral_pic')->where(array('iid' => $gid))->select();
            $this->assign('goods', $goods);
            $this->assign('pics', $pics);
            $this->display();
        }
    }

}