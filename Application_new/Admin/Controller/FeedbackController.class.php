<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Page;
class FeedbackController extends BaseController{

    public function showlist(){

        if(IS_GET){
            $keywords = I('get.keywords');
            $where['username'] = array('like',"%{$keywords}%");
            $member = D('member')->where($where)->select();
            $str = '';
            foreach ($member as $k=>$v){
                $str.=$v['id'].',';
            }
            $map['mid']=array('in',trim($str,','));
        }else{$map='';}
        $count = D('Feedback')->where($map)->count();
        $page = new \Think\Page($count,8);
        $show = $page->show();
         $list = D('Feedback')->join('shop_member on shop_member.id = shop_feedback.mid')
             ->where($map)->limit($page->firstRow.','.$page->listRows)->field('feedback_id,username,content,reply,shop_feedback.addtime,feedback_admin')->select();
        $this->assign('list',$list);
        $this->assign('empty',"<h1 style='color:red'>没有相关数据</h1>");
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->display();
    }

    public function detail(){
        if(IS_POST){
            $search = D('Feedback')->where(array("feedback_id"=>I('post.id')))->find();
            if($search['reply']==I('reply')){
                $this->ajaxReturn(array('status'=>'1','msg'=>'暂无操作'));
            }else{
                $data['reply']=I('post.reply');
                $result = D('Feedback')->where(array("feedback_id"=>I('post.id')))->save($data);
                if($result){
                    $this->ajaxReturn(array('status'=>0,'msg'=>'操作成功'));
                }else{
                    $this->ajaxReturn(array('status'=>2,'msg'=>'操作失败'));
                }
            }
        }
        else {
            $feed = D('Feedback')->join('shop_member on shop_member.id = shop_feedback.mid')
                ->field('feedback_id,username,content,reply,shop_feedback.addtime,feedback_admin')->where(array("feedback_id"=>I('get.id')))->find();
            $this->assign('list', $feed);
            $this->display();
        }
    }
    public function del($id){
        $del = D('Feedback')->where(array("feedback_id"=>"{$id}"))->delete();
        if($del){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>'error',"msg"=>"删除失败"));
        }

    }

}