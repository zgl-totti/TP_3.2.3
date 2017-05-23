<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Page;
class NewsCommentController extends BaseController{
    //评论列表
    public function comment(){
        $comment=D("News_comment");
        //数据库所有记录总数
        $count=$comment->count("id");
        //实例化分页类 传入总记录数和每页显示的记录数为 2
        $page=new Page($count,2);
        //分页显示输出(分页的页码输出)
        $show=$page->show();
        //进行分页数据查询 注意limit方法的参数要使用Page类的属性
        // $list代表每页显示的数据记录数
        $list = $comment->order('addtime')->limit($page->firstRow.','.$page->listRows)->select();
        //遍历$list数组
        foreach($list as $k=>$v){
            $mid=$v["m_id"];
            $member=D("Member");
            $member_info=$member->where("id='{$mid}'")->find();
            $list[$k]["member_name"]=$member_info["username"];
            $nid=$v["n_id"];
            $news=D("News");
            $news_info=$news->where("id='{$nid}'")->find();
            $list[$k]["title"]=$news_info["title"];
        }
        //赋值数据集
        $this->assign("list",$list);
        $this->assign("firstRow",$page->firstRow);
        $this->assign("page",$show);
        $this->display();
    }
    //评论删除
    public function commentDel($id){
        $comment=D("News_comment");
        $info=$comment->where("id='{$id}'")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
    //评论隐藏
    public function commentHide($id){
        $comment=D("News_comment");
        $data["id"]=$id;
        $data["isshow"]=0;
        $info=$comment->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"隐藏成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"隐藏失败"));
        }
    }
    //评论显示
    public function commentShow($id){
        $comment=D("News_comment");
        $data["id"]=$id;
        $data["isshow"]=1;
        $info=$comment->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"显示成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"显示失败"));
        }
    }
    //评论详情
    public function commentDetail(){
        $comment=D("News_comment");
        $id=I("get.id");
            //根据评论id查出用户名，新闻标题，评论内容，回复内容
            $sql="select m.username,n.title,c.commentcontent,c.replycontent from shop_member as m,shop_news as n,shop_news_comment as c where c.m_id=m.id and c.n_id=n.id and c.id={$id}";
            $info=$comment->query($sql);
            if($info){
                $this->assign("info",$info);
                $this->display();
            }
    }
    //评论回复
    public function replyNews(){
        $comment=D("News_comment");
        if(IS_POST){
            $data["id"]=I("post.id");
            $data["replycontent"]=I("post.replycontent");
            $data["isreply"]=1;
            $info=$comment->save($data);
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>"回复成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"回复失败"));
            }
        }else{
            $id=I("get.id");
            $this->assign("id",$id);//把id传送给回复页面,为下次使用做准备
            $this->display();
        }
    }
}