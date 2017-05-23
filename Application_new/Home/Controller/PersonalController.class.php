<?php
namespace Home\Controller;
use Think\Controller;
use Home\Controller\AddressController;
use Think\Page;
//use Home\Controller\HistoryController;
class PersonalController extends Controller{
    public function showlist()
    {
        //站内信
        $this->msg();
        //会员首页
        /*购买前三条记录*/
        $where['mid'] = session('mid');
        $list = D('Order')->alias('o')->join('shop_order_goods as og on o.id=og.oid')->join('shop_order_status as os on o.order_status=os.id')
            ->join('shop_goods as g on og.gid=g.id')->field('g.id,goodsname,pic,status_name')->where($where)->limit(3)->select();
        /*订单总数*/
        $sum = D('Order')->field('count(id) as count')->group('mid')->where($where)->find();
        /*会员等级*/
        $level = M('member')->join('shop_level as l on shop_member.level=l.lid')->field('level_name,money,credit')->where(array('id'=>session('mid')))->find();
        /*购物车内容*/
        $cart = M('Cart')->field('count(id) as count')->group('mid')->where($where)->find();
        $this->assign('cart',$cart);
        $this->assign('level',$level);
        $this->assign('sum',$sum);
        $this->assign('list',$list);
        $this->display();
    }
    //信息
    public function msg(){
        $message=M("Message");
        $mid=session("mid");
        //查看未读信息的条数
        $num1=$message->where("mid={$mid} AND msgstatus=0")->count();
        session("num1",$num1);
        //查看已读信息的条数
        $num2=$message->where("mid={$mid} AND msgstatus=1")->count();
        session("num2",$num2);
    }
    public function memberInfo(){
        $id = session('mid');
        $member = D('Member');
        if(IS_POST){
            $data = $member->create();    //拿到表单提交的数据
            if($data){
                $upload = new \Think\Upload();
                $upload->maxSize = 3145728;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
                $upload->rootPath = './Public/Admin/Uploads/';
                $upload->savePath = 'member/';  //头像存储到这里
                $upload->autoSub = false;
                $pic = $upload->upload();
                if ($pic) {
                    //生成缩略图
                    $data['pic'] = $pic['pic']['savename'];
                    $filename = './Public/Admin/Uploads/' . $pic['pic']['savepath'] . $pic['pic']['savename'];
                    $image = new \Think\Image();
                    $thumb100 = './Public/Admin/Uploads/' . $pic['pic']['savepath'] . 'thumb100/100_' . $pic['pic']['savename'];
                    $image->open($filename)->thumb(100, 100)->save($thumb100);
                }
                else{$list = $member->where("id={$id}")->find();
                    $data['pic']=$list['pic'];}
                $info=$member->where("id={$id}")->save($data);
                if($info){$this->ajaxReturn(array("status"=>"ok","msg"=>"修改成功"));}
                else{$this->ajaxReturn(array("status"=>"error","msg"=>"修改失败"));}
            }else{$this->ajaxReturn(array("status"=>"error","msg"=>$member->getError()));}
        }else{
            $memberlist = $member->where("id={$id}")->find();   //列出用户基本信息
            $this->assign('memberlist', $memberlist);
            $this->display();
        }
    }
    public function collection(){
        $id=session('mid');
        $member = M('Collect');

        $count = $member->where("mid={$id}")->count();// 查询满足要求的总记录数
        $page = new Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $member->join('shop_goods on shop_collect.gid = shop_goods.id')
                        ->field('goodsname,pic,price,salenum,shop_collect.id,gid')
                        ->limit($page->firstRow.','.$page->listRows)
                        ->where("mid={$id}")->select();

        //收藏量
        $collectnum =$member->where(array("mid"=>session('mid')))->count('id');
        $this->assign('empty',"<h1 style='color:red'>暂无相关数据</h1>");
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('collectnum',$collectnum);
        $this->assign('list',$list);
        $this->display();
    }

    public function collectdel($cid){
        $result = D('Collect')->where(array("mid"=>session('mid'),"id"=>$cid))->delete();
        if($result){$this->ajaxReturn(array("status"=>1,"msg"=>"成功删除"));}
        else{$this->ajaxReturn(array("status"=>0,"msg"=>"删除失败"));}
    }
    public function comment(){

        $order = D('Order');
        $where['mid']=session('mid');
        $where['order_status']=4;
        /*查找出未评价的商品*/
        $list = $order->alias('o')->join('shop_order_goods as g on o.id=g.oid')
            ->join('shop_goods as s on g.gid=s.id')
            ->field('g.oid,s.id,g.buynum,s.goodsname,s.pic,s.price')->where($where)->select();
        $this->assign('list',$list);
        /*查找出已评价的商品*/
        $map['order_status']=5;
        $map['c.mid'] = session('mid');
        /*distinct去重复字段*/
        $aready = $order->alias('o')->join('shop_order_goods as g on o.id=g.oid')
            ->join('shop_goods as s on g.gid=s.id')->join('shop_goods_comment as c on s.id=c.gid')
            ->distinct(true)->field('c.id,c.gid,g.buynum,s.goodsname,s.pic,s.price,c.commentcontent')->where($map)->select();
        $this->assign('empty',"<h1 style='color:red'>暂无相关数据</h1>");
        $this->assign('aready',$aready);
        $this->display();
    }

    public function commentlist(){
        if(IS_POST){
            $comment = M('Goods_comment');
            $data = $comment->create();
            $data['mid']=session('mid');
            $data['addtime']=time();
            if($data['commentcontent']==''){
                $this->ajaxReturn(array("status" => "cnull", "msg" => "内容不能为空"));
            }else{
                $info = $comment->add($data);
                if ($info) {
                    $where['id']=I('post.oid');
                    $data1['order_status']=5;
                    $result = D('Order')->where($where)->save($data1);
                    if($result) {
                        session('lastgid',I('post.gid'));    //保存gid 留着后面用
                        $this->ajaxReturn(array("status" => 1, "msg" => "恭喜你,提交成功"));}
                    else{$this->ajaxReturn(array("status" => 0, "msg" => "提交失败"));}
                }
                else {$this->ajaxReturn(array("status" => 0, "msg" => "提交失败"));}
            }
        }
        else {
            $this->assign('gid',I('get.gid'));
            $this->assign('oid',I('get.oid'));
            $this->assign('empty',"暂时没有相关信息");
            $this->display();
        }
    }
    public function uploadGoodsPic(){
        $upload=new \Think\Upload();
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Admin/Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     'comments/'; // 设置附件上传（子）目录
        $upload->autoSub  =false;  //关闭自动使用子目录上传文件
        $info=$upload->upload();
        $data['mid']=session('mid');
        $data['gid']=session('lastgid');
        $data['picname']=$info['file']['savename'];
        $data['addtime']=time();
        $result = M('Comment_pic')->add($data);
        if($result){
            $this->ajaxReturn(array("status"=>1,"msg"=>"图片上传成功"));
        }else{$this->ajaxReturn(array("status"=>0,"msg"=>"图片上传失败"));}
    }
    public function del($mid){
        $info=M('Goods_comment')->where(array("id"=>$mid))->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"成功删除"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
    public function point(){
        $id=session('mid');
        $where['mid']=$id;
        $order = M('order');
        $count = $order->where($where)->count();
        $page = new \Think\Page($count,6);
        $show = $page ->show();
        $point = $order->where("mid={$id}")->order('addtime desc')
                                           ->limit($page->firstRow.','.$page->listRows)
                                           ->field('order_syn,order_price,addtime')->select();
        $sum = M('Member')->where("id={$id}")->field('credit')->find();
        $usepoint = M('Credit_goods')->alias('c')->join("shop_goods on shop_goods.id=c.gid")
                                     ->field('goodsname,pic,c.addtime,c.buynum,c.credit')->where($where)->order('c.addtime desc')->limit(5)->select();
        $usesum = M('Credit_goods')->where($where)->sum('credit');
        $this->assign('usesum',$usesum);
        $this->assign('sum',$sum);
        $this->assign('page',$show);
        $this->assign('point',$point);
        $this->assign('usepoint',$usepoint);
        $this->display();
    }


    //收货地址
    public function address(){
        $mid=session("mid");
        //实例化address对象
        $address=new AddressController();
        //对象调用相关方法
        $addressInfo=$address->getAddress($mid);
        //分配给模板
        $this->assign("addressInfo",$addressInfo);
        $this->display();
    }
    //我的订单
    public function order(){
        $mid=session("mid");
        $order=D("Order");
        $where=I("get.order_status");
        if($where){
            $count = $order->where("mid={$mid} AND order_status={$where}")->count();// 查询满足要求的总记录数
        }else{
            $count = $order->where("mid={$mid}")->count();// 查询满足要求的总记录数
        }
        $page = new Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $page->rollPage=5;
        $page->setConfig("first","首页");
        $show = $page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        if($where){
            $list = $order->where("mid={$mid} AND order_status={$where}")->order('addtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $list = $order->where("mid={$mid}")->order('addtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        }
//        $list = $order->where("mid={$mid} AND order_status={$where}")->order('addtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        //得到每个订单下的所有商品，是一个三维数组
        foreach($list as $k=>$v){
            $list[$k]['goods']=$this->orderGoods($v["id"]);
            $list[$k]["status"]=$this->orderStatus($v["order_status"]);
        }
       /* dump($list);
        exit;*/
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);//
        $this->display();
    }
    //当前订单所购买的所有商品信息
    public function orderGoods($oid){
        $order_goods=D("Order_goods");
        $goodsInfo=$order_goods->alias("og")->where("o.id={$oid}")
                            ->field("g.id,g.pic,g.introduction,g.goodsname,g.price,og.buynum,o.order_price,os.status_name,os.member_opt,o.order_status")
                            ->join("shop_order o on og.oid=o.id")
                            ->join("shop_order_status os on o.order_status=os.id")
                            ->join("shop_goods g on og.gid=g.id")->select();
        return $goodsInfo;
    }
    //根据当前订单的状态，查找出用户对应的操作
    public function orderStatus($status){
        $order_status=D("Order_status");
        $statusInfo=$order_status->alias("os")->field("os.id,os.member_opt")->where("o.order_status={$status}")
                                 ->join("shop_order o on o.order_status=os.id")->find();
        return $statusInfo;
    }
    //会员中心，我的购物车
    public function myCart(){
        //判断用户有没有登陆
        $goods=D("Goods");
        $mid=session("mid");
        if(!empty($mid)){
            //用户已经登陆从数据库中读取信息
            $cartList=$goods->getCartList($mid);
        }else{
            //用户没有登陆情况下，从session中取数据
            foreach(array_reverse($_SESSION["mycart"]) as $k=>$v){
                $cartList[$k]=$goods->getSessionGoods($v["gid"]);
                $cartList[$k]["gid"]=$v["gid"];
                $cartList[$k]["buynum"]=$v["buynum"];
            }
        }
        //分配商品信息
        $this->assign("cartList",$cartList);
        $this->display();
    }
    //会员中心我的足迹
    public function foot(){

        $foot = M('Foot'); // 实例化User对象
        $mid=session("mid");
        $count = $foot->where("mid={$mid}")->count();// 查询满足要求的总记录数
        $page = new Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $foot->alias("f")->where("mid={$mid}")->join("shop_goods g on f.gid=g.id")
                     ->order('f.addtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        //浏览总记录数
        $num=$foot->where("mid={$mid}")->count("id");
        $this->assign('list',$list);// 赋值数据集
        $this->assign('num',$num);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板

    }
    //删除我的足迹
    public function delFoot($id){
        $foot=M("Foot");
        $data['mid']=session("mid");
        $data['gid']=$id;
        $info=$foot->where($data)->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }

    }
    //会员中心，会员等级
    public function memberLevel(){
        $mid=session("mid");//当前会员ID
        $member=D("member");//实例化会员对象
        $menberLevel=$member->alias("m")->field("m.username,m.gender,m.costs,l.level_name")->where("m.id={$mid}")
                           ->join("shop_level l on l.lid=m.level")->find();
        $this->assign("memberLevel",$menberLevel);
        $this->display();
    }
    //账户中心
    public function safety(){
        //根据session中保留的会员ID查询出用户信息
        $member=D("Member");
        $mid=session("mid");
        $memberInfo=$member->where("id={$mid}")->field("username,money")->find();
        $this->assign("memberInfo",$memberInfo);
        $this->display();
    }
    //修改登陆密码
    public function editLoginPwd(){
        //根据session中保留的会员ID进行登录密码修改
        $mid=session("mid");
        $member=D("Member");
        $password=I("post.password");
        $newPwd=I("post.newPwd");
        $reNewPwd=I("post.reNewPwd");
        $memberInfo=$member->where("id={$mid}")->field("password")->find();
        if(md5($password)!=$memberInfo["password"]){
            $this->ajaxReturn(array("status"=>"error","msg"=>"原密码输入错误"));
        }else{
            if(md5($newPwd)==$memberInfo["password"]){
                $this->ajaxReturn(array("status"=>"error","msg"=>"新密码不能与原密码一样"));
            }else{
                if($newPwd!==$reNewPwd){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"确认密码与新密码不一致"));
                }else{
                    $data["password"]=md5($newPwd);
                    $info=$member->where("id={$mid}")->save($data);
                    if($info){
                        $this->ajaxReturn(array("status"=>"ok","msg"=>"登录密码修改成功"));
                    }else{
                        $this->ajaxReturn(array("status"=>"error","msg"=>"登录密码修改失败"));
                    }
                }
            }
        }
    }
    //修改支付密码
    public function editPayPwd(){
        //根据session中保留的会员ID进行登录密码修改
        $mid=session("mid");
        $member=D("Member");
        $paypwd=I("post.paypwd");
        $newpaypwd=I("post.newpaypwd");
        $renewpaypwd=I("post.renewpaypwd");
        $memberInfo=$member->where("id={$mid}")->field("password,paypwd")->find();
        if($paypwd!=$memberInfo["paypwd"]){
            $this->ajaxReturn(array("status"=>"error","msg"=>"原密码输入错误"));
        }else{
            if(md5($newpaypwd)==$memberInfo["password"]){
                $this->ajaxReturn(array("status"=>"error","msg"=>"支付密码与登陆密码不能一样"));
            }else{
                if($newpaypwd==$memberInfo["paypwd"]){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"新密码不能与原密码一样"));
                }else{
                    if($newpaypwd!=$renewpaypwd){
                        $this->ajaxReturn(array("status"=>"error","msg"=>"确认密码与新密码不一致"));
                    }else{
                        $data["paypwd"]=$newpaypwd;
                        $info=$member->where("id={$mid}")->save($data);
                        if($info){
                            $this->ajaxReturn(array("status"=>"ok","msg"=>"支付密码修改成功"));
                        }else{
                            $this->ajaxReturn(array("status"=>"error","msg"=>"支付密码修改失败"));
                        }
                    }
                }
            }

        }
    }
    //账号余额充值
    public function accountAdd(){
        $member=D("Member");
        $mid=session("mid");
        $pay=I("post.pay");
        $money=I("post.money");
        if($pay){
            switch($pay){
                case 1:
                    $msg="支付宝充值成功";
                    break;
                case 2:
                    $msg="银行卡充值成功";
                    break;
                case 3:
                    $msg="微信充值成功";
                    break;
                case 4:
                    $msg="QQ红包充值成功";
                    break;
                default:
                    $msg="其他方式充值成功";
                    break;
            }
            $memberInfo=$member->where("id={$mid}")->field("money")->find();
            $data["money"]=$memberInfo["money"]+$money;
            $info=$member->where("id={$mid}")->save($data);
            if($info){
                $this->ajaxReturn(array("status"=>"ok","msg"=>$msg));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"充值失败"));
            }
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"请选择充值方式"));
        }
    }
    //我的拍卖
    public function auction(){
        $auction=D("Auction");
        $auctionSuccess=M("Auction_success");
        $auctionGoods=M("Auction_goods");
        $mid=session("mid");
        //我的拍卖纪录
        $myAuctionRecord=$auction->alias("a")->where("a.mid={$mid}")->field("g.pic,g.goodsname,a.auctionprice,a.addtime")
                                 ->join("shop_auction_goods ag on a.ag_id=ag.id")
                                 ->order("a.ag_id")->join("shop_goods g on g.id=ag.gid")->select();
        foreach($myAuctionRecord as $k=>$v){
            $myAuctionRecord[$k]["buynum"]=1;
        }
        $this->assign("myAuctionRecord",$myAuctionRecord);
        //已拍商品
        $getAuctionGoods=$auctionSuccess->alias("s")->where("s.mid={$mid}")->field("s.isshow,ag.id as ag_id,g.id as gid,g.pic,g.goodsname,s.price")
                                        ->join("shop_auction_goods ag on ag.id=s.ag_id")
                                        ->join("shop_goods g on g.id=ag.gid")->select();
        foreach($getAuctionGoods as $kk=>$vv){
            //拍卖的数量默认是一件
            $getAuctionGoods[$kk]["buynum"]=1;
            //查看用户交的保证金
            $deposit=M("Auction_deposit")->where("mid={$mid} AND ag_id={$vv['ag_id']}")->field("deposit")->find();
            //把保证金存入拍卖完成信息中
            $getAuctionGoods[$kk]["deposit"]=$deposit["deposit"];
            //需支付
            $getAuctionGoods[$kk]["pay"]=$vv["price"]-$deposit["deposit"];
        }
        $this->assign("getAuctionGoods",$getAuctionGoods);
        $this->display();
    }

    public function feedback(){
        if(IS_POST){
            $data['mid']=session('mid');
            $data['addtime']=time();
            $data['content']=I('post.content');
            $result=M('Feedback')->add($data);
            if($result){$this->ajaxReturn(array("status"=>"ok","msg"=>"提交成功"));}
            else{$this->ajaxReturn(array("status"=>"error","msg"=>"提交失败,请稍后再试"));}
        }
        else{
            $feedback = M('Feedback')->where(array("mid" => session('mid')))->select();
            $this->assign('feedback', $feedback);
            $this->assign('empty', "<h1 style='color:red'>没有相关反馈</h1>");
            $this->assign('empty1', "<h1 style='color:red'>没有相关回馈</h1>");
            $this->display();
        }
    }
    public function feeddel($fid){
        $info=M('Feedback')->where(array("feedback_id"=>$fid))->delete();
        if($info){$this->ajaxReturn(array("status"=>"ok","msg"=>"成功删除"));}
        else{$this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));}
    }

    public function draw(){
        $draw = M('Integral_draw');
        $member = M('Member');
        if(IS_POST){
            //先判断是否能找到用户
            $find1 = $draw->where(array('mid'=>session('mid')))->find();
            if(!$find1){
                $data['addtime']=date('Y-m-d',time());
                $data['mid']=session('mid');
                $data['text']=trim(I('post.str'));
                $info1 = $draw->add($data);
                if($info1){
                    $find2 = $member->where(array('id'=>session('mid')))->getField('credit');
                    $save['credit']=$find2+substr(trim(I('post.str')),0,2);
                    $result1 = $member->where(array('id'=>session('mid')))->save($save);
                    if($result1){
                        $this->ajaxReturn(array('status'=>'ok','msg'=>I('post.str')));
                    }else{
                        $this->ajaxReturn(array('status'=>'error','msg'=>'抽奖失败,请稍后再试'));
                    }
                }else{
                    $this->ajaxReturn(array('status'=>'error','msg'=>'抽奖失败,请稍后再试'));
                }
            }
            elseif($find1 && $find1['addtime']!=date('Y-m-d',time())){
                $map['addtime']=date('Y-m-d',time());
                $map['text']=trim(I('post.str'));
                $info2 =$draw->where(array('mid'=>session('mid')))->save($map);
                if($info2){
                    $find3 = $member->where(array('id'=>session('mid')))->field('credit')->find();
                    $temp['credit']=$find3['credit'] + substr(trim(I('post.str')),0,2);
                    $result2 = $member->where(array('id'=>session('mid')))->save($temp);

                    if($result2){$this->ajaxReturn(array('status'=>'ok','msg'=>I('post.str')));}
                    else{$this->ajaxReturn(array('status'=>'error','msg'=>'抽奖失败,请稍后再试'));}
                }else{
                    $this->ajaxReturn(array('status'=>'error','msg'=>'抽奖失败,请稍后再试'));
                }
            }else{
                $this->ajaxReturn(array('status'=>'not','msg'=>'亲,您今日已抽过,明天再来吧!'));
            }
        }
        else
        {$this->display();}
    }


}























