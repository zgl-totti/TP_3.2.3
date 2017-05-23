<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Crypt\Driver\Think;
use \Think\Upload;
class GoodsController extends BaseController{
    //展示列表
    public function showlist(){
        $goods = M('Goods');
        $keywords =I('get.keywords');
        if($keywords){
            $where['goodsname']=array("like","%$keywords%");
        }else{$where='';}
        $where['delete']=0;
        $count = $goods->where($where)->count();
        $page = new \Think\Page($count,8);
        $show = $page->show();
        $list = $goods->where($where)->limit($page->firstRow.','.$page->listRows)->select();    //先拼出一个二维数组 所有商品信息
        foreach ($list as $k=>$v) {     //遍历已经变成了一维数组
            $sql = "select catename,bname from shop_goods as g,shop_category as c,shop_brand as b where g.cid=c.id and g.bid=b.id and g.id={$v['id']}";
            $info = $goods->query($sql);    //查找出catename,bname
            $list[$k]['catename']=$info[0]['catename'];     //将获得的两个字段都放到$list中 在模型中遍历出来
            $list[$k]['bname']=$info[0]['bname'];
        }
        $this->assign('p',I('get.p'));
        $this->assign('firstRow',$page->firstRow);
        $this->assign('keywords',$keywords);
        $this->assign('list',$list);
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','暂无相关数据');
        $this->display();
    }
    //添加列表
    public function addlist(){
        if(IS_POST){
            $goods = M('Goods');
            $pic = M('Goods_pic');
            $data['goodsname']=trim(I('post.goodsname'));
            $data['cid']=I('post.cid');
            $data['bid']=I('post.bid');
            $data['marketprice']=I('post.marketprice');
            $data['price']=I('post.price');
            $data['num']=I('post.num');
            $data['introduction']=I('post.introduction');
            $data['parameter']=I('post.parameter');
            $data['description']=I('post.description');
            $data['addtime']=time();
            if(!$data['goodsname']){
                $this->ajaxReturn(array('status'=>'gnull'));
            }elseif(!$data['cid']){
                $this->ajaxReturn(array('status'=>'cnull'));
            }elseif(!$data['bid']){
                $this->ajaxReturn(array('status'=>'bnull'));
            }
            else {
                //处理图片
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     './Public/Admin/Uploads/'; // 设置附件上传根目录
                $upload->savePath  =     'goods/'; // 设置附件上传（子）目录
                $upload->autoSub  =false;  //关闭自动使用子目录上传文件
//                //先将上面得到的商品插入到表中
                $gid = $goods->add($data);
                $info   =   $upload->upload();
                if(!$info){
                    $this->ajaxReturn(array('status'=> 'picerror'));    //上传错误时报的错
                }
                else{
                    //如果上传成功,处理缩略图的问题
                    foreach($info as $file){
                        //先拼出插入图片的图片路径
                        $filename = './Public/Admin/Uploads/'.$file['savepath'].$file['savename'];
                        $arr[] = $file['savename']; //将每个遍历出来的图片名称插入到表中
                        $picarr['gid']=$gid;
                        $picarr['picname']=$file['savename'];   //将获取到的gid和picname字段中的内容插入到数据表中
                        $pic->add($picarr); //添加返回添加后的id值
                        //1.先设置路径
                        $thumb_100 = './Public/Admin/Uploads/'.$file['savepath'].'/thumb100/100_'.$file['savename'];
                        $thumb_350 ='./Public/Admin/Uploads/'.$file['savepath'].'/thumb350/350_'.$file['savename'];
                        $thumb_800 = './Public/Admin/Uploads/'.$file['savepath'].'/thumb800/800_'.$file['savename'];
                        $image = new \Think\Image();
                        $image->open($filename)->thumb(100,100)->save($thumb_100);
                        $image->open($filename)->thumb(500,500)->save($thumb_350);
                        $image->open($filename)->thumb(800,800)->save($thumb_800);
                    }
                    //将arr中存放的四个图片中的第一个图片更新的到goods表中
                    $arr['id'] = $gid;
                    $arr['pic'] = $arr[0];
                    $result = $goods->save($arr);
                    if(!$result){
                        $this->ajaxReturn(array("status"=>"falsepic"));
                    }
                }
                $this->ajaxReturn(array("status" =>'ok'));
            }
        }
        else{
            $brand =M('Brand')->select();
            $category = M('Category')->order('path')->select();
            foreach($category as $v){
                $space = count(explode(',',$v['path']));
                $v['catename'] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $space) . $v['catename'];
                $result[]=$v;
            }
            $this->assign('brand',$brand);  //商品循环
            $this->assign('category',$result);    //分类循环
            $this->display();
        }
    }
    /*
 * 回收页面
 * */
    public function recycle(){
        $goods = M('Goods');
        $keywords =I('get.keywords');
        if($keywords){
            $where['goodsname']=array("like","%$keywords%");
        }else{$where='';}
        $where['delete']=1;
        $count = $goods->where($where)->count();
        $page = new \Think\Page($count,3);
        $show = $page->show();
        $list = $goods->where($where)->limit($page->firstRow.','.$page->listRows)->select();    //先拼出一个二维数组 所有商品信息
        foreach ($list as $k=>$v) {     //遍历已经变成了一维数组
            $sql = "select catename,bname from shop_goods as g,shop_category as c,shop_brand as b where g.cid=c.id and g.bid=b.id and g.id={$v['id']}";
            $info = $goods->query($sql);    //查找出catename,bname
            $list[$k]['catename']=$info[0]['catename'];     //将获得的两个字段都放到$list中 在模型中遍历出来
            $list[$k]['bname']=$info[0]['bname'];
        }
        $this->assign('p',I('get.p'));
        $this->assign('firstRow',$page->firstRow);
        $this->assign('keywords',$keywords);
        $this->assign('list',$list);
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<h1 style="color:red;font-size: 20px;">暂无相关数据</h1>');
        $this->display();
    }
    /*
     * 编辑页面
     * */
    public function edit()
    {
        if (IS_POST) {
            $goods = D('goods');
            //与拍卖相关的价格获取
            $auctionData["startprice"]=I("post.startprice");
            $auctionData["commonprice"]=I("post.commonprice");
            $auctionData["maxprice"]=I("post.maxprice");
            $auctionData["range"]=I("post.range");
            $auctionData["gid"]=I("post.id");
            //收集表单信息
            $data = $goods->create();
            if ($data) {
                $data['addtime'] = time();  //获取当前时间更新
                //这块跟李立新的活动相连接和拍卖专场有关
                if($data['activity']!=0){
                    $activity = M('Activity');
                    $auctionGoods=M("Auction_goods");
                    $data1['gid'] = I('post.id');
                    $result = $activity->field('gid')->select();
                    foreach($result as $k=>$v){
                        //限时抢购
                        if(($v['gid']==I('post.id')) && (I('post.activity')!=1)){
                            $activity->where(array('gid'=>I('post.id')))->delete();
                        }elseif(($v['gid']==I('post.id')) && (I('post.activity')==1)){
                            $activity->where(array('gid'=>I('post.id')))->delete();
                        }
                        //拍卖专场
                        if(($v['gid']==I('post.id')) && (I('post.activity')!=4)){
                            $activity->where(array('gid'=>I('post.id')))->delete();
                        }elseif(($v['gid']==I('post.id')) && (I('post.activity')==4)){
                            $activity->where(array('gid'=>I('post.id')))->delete();
                        }
                    }
                    //加入限时抢购
                    if($data['activity']==1) {$activity->where($data1)->add($data1);}
                    //加入拍卖专场
                    if($data['activity']==4) {$auctionGoods->add($auctionData);}
                }
                //更新商品信息
                if ($goods->save($data)){
                    //更新图片信息
                    if ($_FILES) {
                        $goodsInfo = $goods->field('pic')->find(I('post.id'));
                        $upload = new \Think\Upload();
                        $upload->maxSize = 3145728;// 设置附件上传大小
                        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型D
                        $upload->rootPath = './Public/Admin/Uploads/'; // 设置附件上传根目录
                        $upload->savePath = "goods/";
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
                                    unlink('./Public/Admin/Uploads/goods/' . $goodsInfo['savename']);
                                    unlink('./Public/Admin/Uploads/goods/thumb100/100_' . $goodsInfo['savename']);
                                    unlink('./Public/Admin/Uploads/goods/thumb350/350_' . $goodsInfo['savename']);
                                    unlink('./Public/Admin/Uploads/goods/thumb800/800_' . $goodsInfo['savename']);
                                } else {$this->error('主图更新失败');};
                            } else if ($key > 0) { //修改辅图
                                $pid = $key;
                                $data['id'] = $pid;
                                $data['picname'] = $val['savename'];
                                if (M('goods_pic')->save($data)) {
                                    $picInfo = M('goods_pic')->field('picname')->find($pid);
                                    //删除原图
                                    unlink('./Public/Admin/Uploads/goods/' . $picInfo['savename']);
                                    unlink('./Public/Admin/Uploads/goods/thumb100/100_' . $picInfo['savename']);
                                    unlink('./Public/Admin/Uploads/goods/thumb350/350_' . $picInfo['savename']);
                                    unlink('./Public/Admin/Uploads/goods/thumb800/800_' . $picInfo['savename']);
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
            $goods = M('goods')->alias('g')->join('shop_category c ON g.cid=c.id')
                ->join('shop_brand b ON g.bid=b.id')
                ->where(array('g.id' => $gid))->field('g.*,catename,path,bname')->order(array('g.id' => 'desc'))->find();
            $category = M('Category')->order('path')->select();
            foreach($category as $v){
                $space = count(explode(',',$v['path']));
                $v['catename']=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;",$space).$v['catename'];
                $result[] =$v;
            }
            $brand = M('Brand')->select();
            $pics = M('goods_pic')->where(array('gid' => $gid))->select();
            $this->assign('goods', $goods);
            $this->assign('cate', $result);
            $this->assign('brand',$brand);
            $this->assign('pics', $pics);
            $this->display();
        }
    }
    /*以下为页面的小操作*/
    public function enabled($id){
        $dis = M('Goods');
        $map['id']=$id;
        $data['display']=1;
        $info=$dis->where($map)->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"成功上架"));
        }else{
            $this->ajaxReturn(array("status"=>"ok","msg"=>"上架失败"));
        }
    }
    public function disabled($id)
    {
        $act = M("Goods");
        $map['id'] = $id;
        $data['display'] = 0;
        $info = $act->where($map)->save($data);
        if ($info) {
            $this->ajaxReturn(array("status" => "ok", "msg" => "成功下架"));
        } else {
            $this->ajaxReturn(array("status" => "ok", "msg" => "下架失败"));
        }
    }
    public function del($id){
        $del = M('Goods');
        $map['id']=$id;
        $info=$del->where($map)->delete();
        //将activity中的相关数据也给删除
        $activity = M('Activity');
        $data['gid'] = $id;
        $activity->where($data)->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"false","msg"=>"删除失败"));
        }
    }
    public function addrecycle(){
        $recycle = M('Goods');
        $map['id']=I('get.id');
        $data['delete']=1;
        $info=$recycle->where($map)->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"成功加入到回收站中"));
        }else{
            $this->ajaxReturn(array("status"=>"ok","msg"=>"加入回收站失败"));
        }
    }
    public function regain(){
        $recycle = M('Goods');
        $map['id']=I('get.id');
        $data['delete']=0;
        $info=$recycle->where($map)->save($data);
        if($info){$this->ajaxReturn(array("status"=>"ok","msg"=>"恢复成功"));}
        else{$this->ajaxReturn(array("status"=>"ok","msg"=>"恢复失败"));}
    }
}