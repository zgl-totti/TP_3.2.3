<?php
namespace Home\Controller;
use Think\Controller;
use Think\Page;
class NewsController extends Controller{
    public function getNewsList(){
        $new=M('news');
        $news=$new->where('isshow=1')->order('addtime desc')->limit(6)->select();
        return $news;
    }
    public function newsdetail(){
        $ddd=M('News_comment');
        $con['isshow']=1;
        $con['n_id']=I('get.id');
        $count=$ddd->where($con)->count();
        $page=new Page($count,5);
        $page->setConfig(first,首页);
        $page->setConfig(end,末页);
        $page->setConfig(next,下页 );
        $page->setConfig(prev,上页 );
        $page->rollPage=5;
        $show=$page->show();
        $list=$ddd
            ->join('shop_Member ON shop_news_comment.m_id=shop_member.id' )
            ->where($con)->limit($page->firstRow.",".$page->listRows)->order('shop_news_comment.addtime desc')->select();
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->assign('firstRow',$page->firstRow);



        $wenxin=M('News');
        $hao['id']=I('get.id');
        $abc=$wenxin->where($hao)->select();
        $this->assign('abc',$abc);
        $news=$wenxin->where('isshow=1')->order('addtime desc')->limit(6)->select();
        $this->assign('cenews',$news);
        $this->display();
    }
    public function sendInfo(){
        if (IS_POST){
            if (session('mid')&&session('mid')>0){
                $aaa=M('News_comment');
                $data['n_id']=I('post.nid');
                $data['m_id']=session('mid');
                $data['commentcontent']=I('post.content');
                $data['addtime']=time();
                $ccc=$aaa->add($data);
                if ($ccc) {
                    $this->ajaxReturn(array("status" => "1", "msg" => "发布成功"));
                    //修改
                    //结束
                }else{
                    $this->ajaxReturn(array("status"=>"2","msg"=>"发布失败"));
                }
            }else{
                $this->ajaxReturn(array("status"=>"2","msg"=>"评论,请登录"));
            }
        }else{
            return false;
        }
    }
    //点赞
    public function likeNum($nid){
        $news=M("News");
        $newsInfo=$news->where("id={$nid}")->field("likenum")->find();
        $data["likenum"]=$newsInfo["likenum"]+1;
        $data["id"]=$nid;
        $info=$news->save($data);
        if($info){
            $likenum=$newsInfo["likenum"]+1;
            $this->ajaxReturn(array("status"=>"ok","msg"=>$likenum));
        }else{
            $this->ajaxReturn(array("status"=>"ok","msg"=>"点赞失败"));
        }
    }
}