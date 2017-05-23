<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
class GoodsCommentController extends BaseController{
    //评论列表
    public function comment(){
        $comment = M('Goods_comment');
        $where['goodsname']=array('like',"%".I('get.keywords')."%");
        $data=$comment->table("shop_goods")->field("id")->where($where)->select();  //找到goods表中的id
        $ids=array();
        foreach($data as $key=>$val){
            $ids[]=$val['id'];
        }
        $search["gid"]=array("in",$ids);
        $count = $comment->where($search)->count("id");   //算出总条数
        $page = new \Think\Page($count,3);
        $show = $page->show();
        $list = $comment->alias('c')->join('shop_goods g on g.id=c.gid')->join('shop_member m on m.id=c.mid')
            ->field('username,goodsname,c.addtime,commentcontent,replycontent,c.id,isreply,isshow')->where($search)->limit($page->firstRow.','.$page->listRows)->select();
       $map["key"]=I('get.keywords');
        foreach($map as $key=>$v){
            $page->parameter["$key"]=urlencode($v);
        }
        $this->assign('p',I('get.p'));
        $this->assign('firstRow',$page->firstRow);
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->assign('count',$count);
        $this->display();
    }


    public function del($id){
        $del = M('Goods_comment');
        $where['id']=$id;
        $result = $del->where($where)->delete();
        if($result){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除失败"));
        }
    }
    public function enabled($id){
        $enable = M('Goods_comment');
        $where['id']=$id;
        $data['isshow']=1;
        $result=$enable->where($where)->save($data);
        if($result){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"更新成功"));
        }else{
            $this->ajaxReturn(array("status"=>"ok","msg"=>"更新失败"));
        }
    }
    public function disabled($id){
        $enable = M('Goods_comment');
        $where['id']=$id;
        $data['isshow']=0;
        $result=$enable->where($where)->save($data);
        if($result){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"更新成功"));
        }else{
            $this->ajaxReturn(array("status"=>"ok","msg"=>"更新失败"));
        }
    }
    public function detail(){
        $where['c.id']=I('get.id');
        $comment = M('Goods_comment');
        $list = $comment->alias('c')->join('shop_goods g on g.id=c.gid')->join('shop_member m on m.id=c.mid')
            ->field('username,goodsname,c.addtime,commentcontent,replycontent')
            ->where($where)->select();
        $this->assign('list',$list);
        $this->display();
    }

    public function answer(){
        $comment= M('Goods_comment');
        if(IS_POST) {
            $where['id'] = I('post.id');
            $data['replycontent'] = I('post.replycontent');
            $data['isreply']=1;
            $result = $comment->where($where)->save($data);
            if ($result) {
                $this->ajaxReturn(array("status" => "ok", "msg" => "恭喜你,提交成功"));
            } else {
                $this->ajaxReturn(array("status" => "error", "msg" => "提交失败"));
            }
        }
        else{
            $id=I('get.id');
            $this->assign('id',$id);
            $this->display();
        }

    }
}