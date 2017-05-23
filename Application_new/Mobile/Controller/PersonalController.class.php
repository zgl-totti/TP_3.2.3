<?php
namespace Mobile\Controller;
use Think\Controller;
class PersonalController extends Controller {
    //用户中心首页展示
    public function personalList(){
        //会员基本信息
        $mem=M('member');
        $where['id']=session('mid');
        $data=$mem->where($where)->select();
        $this->assign('data',$data);
        //获取订单信息
        $orderNum["suoyou"]=$this->orderNum(0);
        $orderNum["dfk"]=$this->orderNum(1);
        $orderNum["dfh"]=$this->orderNum(2);
        $orderNum["dsh"]=$this->orderNum(3);
        $orderNum["dpj"]=$this->orderNum(4);
        $orderNum["ywc"]=$this->orderNum(5);
        $this->assign("orderNum",$orderNum);
        $this->display();
    }
    //个人数据显示
    public function myData(){
        $mem=M('member');
        $where['id']=session('mid');
        $data=$mem->field('username,gender,pic')->where($where)->select();
        $this->assign('data',$data);
        $this->display();
    }
    //个人图片页面
    public function myPhoto(){
        $mem=M('member');
        $where['id']=session('mid');
        $date=$mem->field('pic')->where($where)->select();
        $this->assign('date',$date);

        $id = session('mid');
        $member = D('Member');
        if(IS_AJAX){
        $data['pic']=I("post.pic");
          //拿到表单提交的数据
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
                 else {
                           $list = $member->where("id={$id}")->select();
                           $data['pic'] = $list['pic'];
                       }
                $info=$member->where("id={$id}")->save($data);
                
                if($info){
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"修改成功"));
                } else{
                    $this->ajaxReturn(array("status"=>"error","msg"=>"失败"));
                }
            }else{
                $this->ajaxReturn(array("status"=>"not","msg"=>"修改失败"));
            }
        }else{
            $this->display();
        }
}
    //姓名修改
    public function nameChange(){
        
              /*修改*/

        if (IS_AJAX){
            $name['username']=I("post.username");
            $where['id']=session('mid');
            $mem=M('member');
            $abc=$mem->where($where)->save($name);
            if($abc) {
                $this->ajaxReturn(array("status" => "ok", "msg" => "修改成功"));
            }
        }else{
            $mem=M('member');
            $where['id']=session('mid');
            $data=$mem->field('username')->where($where)->select();
            $this->assign('data',$data);
            $this->display();
        }
    }
    //性别修改
    public function myGender()
    {
        if (IS_AJAX) {
            $name['gender'] = I("post.gender");
            $where['id'] = session('mid');
            $mem = M('member');
            $abc = $mem->where($where)->save($name);
            if ($abc) {
                $this->ajaxReturn(array("status" => "ok", "msg" =>"已修改"));
            } else {
                $this->ajaxReturn(array("status" => "error", "msg" => "修改失败"));
            }
        } else {
            $mem = M('member');
            $where['id'] = session('mid');
            $data = $mem->field('gender')->where($where)->select();
            $this->assign('data', $data);
            $this->display();
        }
    }
    /*收藏足迹操作*/
    public function mycollect(){
        $count = M('Collect')->where(array("mid"=>session('mid')))->count(); //计算记录数
        $limitRows = 4; // 设置每页记录数
        $p = new \Org\Shop\AjaxPage($count, $limitRows,"page"); //第三个参数是你需要调用换页的ajax函数名
        $limit_value = $p->firstRow . "," . $p->listRows;
        $collect = M('Collect')->alias('c')->join('shop_goods as g on c.gid = g.id')
                                           ->field('pic,goodsname,price,marketprice,gid,c.id')
                                           ->where(array("mid"=>session('mid')))->limit($limit_value)->select();
        $page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
        $this->assign('total',$count);
        $this->assign('page',$page);
        $this->assign('collect',$collect);
        if(IS_AJAX){$this->display('collectresult');}
        else{$this->display();}
    }
    public function foot(){
        $count = M('Foot')->where(array("mid"=>session('mid')))->count(); //计算记录数
        $limitRows = 4; // 设置每页记录数
        $p = new \Org\Shop\AjaxPage($count, $limitRows,"page"); //第三个参数是你需要调用换页的ajax函数名
        $limit_value = $p->firstRow . "," . $p->listRows;
        $foot = M('Foot')->alias('c')->join('shop_goods as g on c.gid = g.id')
            ->field('pic,goodsname,price,marketprice,gid,c.id')
            ->where(array("mid"=>session('mid')))->limit($limit_value)->select();
        $page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
        $this->assign('total',$count);
        $this->assign('page',$page);
        $this->assign('foot',$foot);
        $this->assign('empty',"<h1 style='color: #ff2832'>小宝贝,您暂无收藏哦!</h1>");
        if(IS_AJAX){$this->display('footresult');}
        else{$this->display();}
    }

    public function collectdel($cid){
        $where['mid'] = session('mid');
        $where['id'] = array("in",$cid);
        $del = M('Collect')->where($where)->delete();
        if($del){
            $this->ajaxReturn(array("status"=>1,"msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>0,"msg"=>"删除失败"));
        }
    }

    public function footdel($cid){
        $where['mid'] = session('mid');
        $where['id'] = array("in",$cid);
        $del = M('Foot')->where($where)->delete();
        if($del){
            $this->ajaxReturn(array("status"=>1,"msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>0,"msg"=>"删除失败"));
        }
    }
/*收藏足迹操作结束*/

    //获取订单各个状态的数量
    public function orderNum($status){
        $order=D("Order");
        $mid=session("mid");
        if($status){
            $num=$order->where("order_status={$status} AND mid={$mid}")->count();
        }else{
            $num=$order->where("mid={$mid}")->count();
        }
        return $num;
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
        $page = new \Org\Shop\AjaxPage($count, 5,"page");// 实例化分页类 传入总记录数和每页显示的记录数(25)
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
        //页面跳转时，要带的参数
        $map["key"]=I("get.order_status");
        foreach($map as $k=>$v){
            $page->parameter[$k]=urlencode($v);
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);//
        $this->assign('where',$where);
        if(IS_AJAX){
            $this->display('result');
        }else{
            $this->display();
        }
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
        $statusInfo=$order_status->alias("os")->field("os.id,os.member_opt,os.status_name")->where("o.order_status={$status}")
            ->join("shop_order o on o.order_status=os.id")->find();
        return $statusInfo;
    }
    //去评论
    public function comment(){
        if(IS_AJAX){
            $data['mid']=session('mid');
            $data['gid']=I('post.gid');
            $data['oid']=I('post.oid');
            $data['commentcontent']=I('post.commentcontent');
            $data['start']=I('post.star');
            $data['addtime']=time();
            //修改order表
            $where['id']=I('post.oid');
            $where['mid']=session('mid');
            $shuju['order_status']=5;
            $order=M('order')->where($where)->save($shuju);
            //存入order_goods表
            $info=M('Goods_comment')->add($data);
            if($info){
                $this->ajaxReturn(array('status'=>'ok','msg'=>'评价成功'));
            }else{
                $this->ajaxReturn(array('status'=>'error','msg'=>'评价失败'));
            }
        }else{
            //评价页面goods图片
            $oid=I('get.oid');
            $goods=M('Goods');
            $where['oid']=$oid;
            $goodslist=$goods->alias('g')->join('shop_order_goods as og ON g.id=og.gid')->where($where)->find();
            $this->assign('goodspic',$goodslist['pic']);
            $this->assign('goodsid',$goodslist['gid']);
            $this->assign('oid',$oid);
            $this->display();
        }
    }
    //修改密码
    public function changePwd(){
        //根据session中保留的会员ID进行登录密码修改
        if(IS_POST){
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
        }else{
            $this->display();
        }
    }
    //修改支付密码
    public function editPayPwd(){
        //根据session中保留的会员ID进行登录密码修改
        if(IS_POST){
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
        }else{
            $this->display("payPwd");
        }
    }
    //地址管理
    public function address(){
        $address=D("Address");
        $mid=session("mid");
        $addressInfo=$address->field("id,name,address,mobile,postcode,isdefault")->where("mid={$mid}")
            ->order("isdefault desc,addtime desc")->limit(0,4)->select();
        $this->assign("addressInfo",$addressInfo);
        $this->display();
    }
    //订单页设置默认地址
    public function setDefault(){
        $address=D("Address");
        $id=I("post.radio");
        $mid=session("mid");
        $isDefault["isdefault"]=1;//设置为默认地址
        $info1=$address->where("id={$id}")->save($isDefault);
        $notDefault["isdefault"]=0;//其他的取消取消设置
        $info2=$address->where("mid={$mid} AND id!={$id}")->save($notDefault);
        if($info1&&$info2){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"设置成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"设置失败"));
        }

    }
    //删除地址
    public function del($id){
        $address=M("Address");
        $info=$address->where("id={$id}")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
    //修改收货地址
    public function editAddress(){
        $address=D("Address");
        $mid=session("mid");
        if(IS_POST){
            $data["address"]=I("province")."-".I("city")."-".I("town")."-".I("jiedao");
            $data["name"]=I("post.name");
            $data["mobile"]=I("post.mobile");
            $data["id"]=I("post.id");
            $data["mid"]=$mid;
            //判断默认地址按钮是否被选中
            if(I("post.isdefault")){
                $data["isdefault"]=I("post.isdefault");
                $info["isdefault"]=0;
                $id1=$address->where("mid={$data['mid']}")->save($info);
                $id2=$address->where("mid={$mid} AND id={$data['id']}")->save($data);
                if($id1 && $id2){
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"编辑成功"));
                }else{
                    $this->ajaxReturn(array("status"=>"error","msg"=>"编辑失败"));
                }
            }else{
                $id3=$address->save($data);
                if($id3){
                    $this->ajaxReturn(array("status"=>"ok","msg"=>"编辑成功"));
                }else{
                    $this->ajaxReturn(array("status"=>"error","msg"=>"编辑失败"));
                }
            }
        }else{
            $id=I("get.id");
            $addInfo=$address->where("id={$id}")->find();
            $this->assign("addInfo",$addInfo);
            $this->display();
        }
    }
    //添加收货地址
    public function addAddress(){
        $address=D("Address");
        if(IS_POST){
            $data["mid"]=session("mid");//当前登陆账号的ID
            $data["name"]=I("post.name");
            $data["mobile"]=I("post.mobile");
            $data["address"]=I("post.province").'-'.I("post.city").'-'.I("post.town").'-'.I("post.jiedao");
            $data["isdefault"]=1;
            $data["addtime"]=time();
            if(!$data["name"]){
                $this->ajaxReturn(array("status"=>"error","msg"=>"收货人姓名不能为空"));
            }else{
                if(!$data["mobile"]){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"手机号不能为空"));
                }else{
                    if(!$data["address"]){
                        $this->ajaxReturn(array("status"=>"error","msg"=>"收货地址不能为空"));
                    }else{
                        $mid=session("mid");
                        $info["isdefault"]=0;
                        $id=$address->add($data);
                        if($id){
                            $address->where("mid={$mid} AND id!=$id")->save($info);
                            $this->ajaxReturn(array("status"=>"ok","msg"=>"地址添加成功"));
                        }else{
                            $this->ajaxReturn(array("status"=>"error","msg"=>"地址添加失败"));
                        }
                    }
                }
            }

        }else{
            $this->display();
        }
    }
    //账号余额充值
    public function accountAdd(){
        if(IS_POST){
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
                if(!$money){
                    $this->ajaxReturn(array("status"=>"error","msg"=>"充值金额不能为空"));
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
        }else{
            //根据session中保留的会员ID查询出用户信息
            $member=D("Member");
            $mid=session("mid");
            $memberInfo=$member->where("id={$mid}")->field("username,money")->find();
            $this->assign("memberInfo",$memberInfo);
            $this->display();
        }
    }
    //消费记录
    public function balance(){
        //根据session中保留的会员ID查询出用户信息
        $member=D("Member");
        $mid=session("mid");
        $memberInfo=$member->where("id={$mid}")->field("username,money")->find();
        $this->assign("memberInfo",$memberInfo);
        $this->display();
    }
    //会员退出
    public function logout(){
        session("mid",null);
        session("username",null);
        $this->ajaxReturn(array("status"=>"ok","msg"=>"退出成功！"));
    }



}