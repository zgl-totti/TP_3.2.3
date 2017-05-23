<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Page;
class AdminController extends BaseController{
    //展示管理员列表
    public function showlist(){
        //实例化管理员表数据模型
        $admin=M("Admin");
        //数据库所有记录总数
//        $keywords=I("post.keywords")?I("post.keywords"):"";
        //查询条件
        $condition["adminname"]=array("like","%".I("get.keywords")."%");
//        $count=$admin->count("id");
        $count=$admin->where($condition)->count("id");
        //实例化分页类 传入总记录数和每页显示的记录数为 2
        $page=new Page($count,3);
        $page->rollPage=3;
        $page->setConfig("first","首页");
        //分页显示输出(分页的页码输出)
        $show=$page->show();
        //进行分页数据查询 注意limit方法的参数要使用Page类的属性
        // $list代表每页显示的数据记录数
        $list = $admin->where($condition)->order('addtime')->limit($page->firstRow.','.$page->listRows)->select();
        //遍历管理员所属组
        foreach($list as $k=>$v){
            $groupInfo=M('AuthGroupAccess')->alias('a')->join('shop_auth_group g ON a.group_id=g.id')->field('g.title')->where("a.uid={$v['id']}")->select();
            $str='';
            foreach($groupInfo as $g){
                $str.=$g['title'].'---';
            }
            $list[$k]['group']=substr($str,0,-3);
        }

        $map["key"]=I("get.keywords");
        foreach($map as $k=>$v){
            $page->parameter[$k]=urlencode($v);
        }
        //赋值数据集
        $this->assign("keywords",I("get.keywords"));
        $this->assign("list",$list);
        $this->assign("firstRow",$page->firstRow);
        $this->assign("page",$show);
        $this->display();
    }
    //添加管理员
    public function addlist(){
        if(IS_POST){
            $admin=M("Admin");
            //获得要添加的账号
            $password=trim(I("post.password"));
            $data["adminname"]=trim(I("post.adminname"));
            $info=$admin->where($data)->find();
            //判断添加账号是否存在
            if($info){
                //该账号存在，不能重复添加
                $this->ajaxReturn(array("status"=>"error","msg"=>"账号已存在,是否重新添加"));
            }else{
                //该账号不存在，可以添加
                //判断账号是否为空
                if(empty($data["adminname"])){
                    //管理员账号为空
                    $this->ajaxReturn(array("status"=>"error","msg"=>"账号不能为空,是否重新添加"));
                }else{
                    //判断密码是否为空
                    if(empty($password)){
                        //密码为空
                        $this->ajaxReturn(array("status"=>"error","msg"=>"密码不能为空,是否重新添加"));
                    }else{
                        //密码不为空
                        //判断密码和确认密码是否一致
                        if(I("post.password")!=I("post.repassword")){
                            //两次密码不一致
                            $this->ajaxReturn(array("status"=>"error","msg"=>"两次密码不一致,是否重新添加?"));
                        }else{
                            //两次密码一致
                            //拼装插入的数据为一个数组
                            $data["adminname"]=trim(I("post.adminname"));
                            $data["password"]=md5(trim(I("post.password")));
                            $data["logintime"]=time();
                            $data["loginip"]=$_SERVER["REMOTE_ADDR"];
                            $data["addtime"]=time();
                            $data["gender"]=I("post.gender");
                            $rst=$admin->data($data)->add();//返回最后一次插入记录的ID号
                            //判断是否添加成功
                            if($rst){
                                if(I('post.group_id')){
                                    $access=M('AuthGroupAccess');
                                    foreach(I('post.group_id') as $v){
                                        $info['uid']=$rst;
                                        $info['group_id']=$v;
                                        $access->add($info);
                                    }
                                }
                                //添加成功
                                $this->ajaxReturn(array("status"=>"ok","msg"=>"添加成功,是否继续添加?"));
                            }else{
                                //添加失败
                                $this->ajaxReturn(array("status"=>"error","msg"=>"添加失败,是否重新添加?"));
                            }
                        }
                    }
                }
            }
        }else{
            //获得组列表
            $groupList=D('AuthGroup')->getGroupList();
            $this->assign('groupList',$groupList);
            $this->display();
        }
    }
    //禁用管理员
    public function disabled($id){
        $admin=D("Admin");
        $data["id"]=$id;
        $data["active"]=0;
        $info=$admin->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"禁用成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"禁用失败"));
        }
    }
    //启用管理员
    public function enabled($id){
        $admin=D("Admin");
        $data["id"]=$id;
        $data["active"]=1;
        $info=$admin->save($data);
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"启用成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"启用失败"));
        }
    }
    //删除管理员
    public function del($id){
        $admin=D("Admin");
        $info=$admin->where("id='{$id}'")->delete();
        if($info){
            $this->ajaxReturn(array("status"=>"ok","msg"=>"删除成功"));
        }else{
            $this->ajaxReturn(array("status"=>"error","msg"=>"删除失败"));
        }
    }
    //编辑管理员
    public function updlist(){
        $admin=D("Admin");
        if(IS_POST){
            $data["id"]=I("post.id");
            $data["gender"]=I("post.gender");
            //根据主键ID查询该密码
            $pwdInfo=$admin->where("id={$data['id']}")->field("password")->find();
            if($pwdInfo["password"]==trim(I("post.password"))){
                $data["password"]=trim(I("post.password"));
            }else{
                $data["password"]=md5(trim(I("post.password")));
            }
            $info=$admin->save($data);
            if($info){
                //修改角色
                if(I('post.group_id')){
                    $access=M('AuthGroupAccess');
                    $access->where(array('uid'=>$data['id']))->delete();
                    //删除后重新写入
                    foreach(I('post.group_id') as $v){
                        $info1['uid']=$data['id'];
                        $info1['group_id']=$v;
                        $access->add($info1);
                    }
                }
                $this->ajaxReturn(array("status"=>"ok","msg"=>"修改成功"));
            }else{
                $this->ajaxReturn(array("status"=>"error","msg"=>"修改失败"));
            }
        }else{
            $data["id"]=I("get.id");
            //查找管理员
            $info=$admin->where($data)->find();
            //查找组（角色）
            $gid=M('AuthGroupAccess')->field('group_id')->where(array('uid'=>$data["id"]))->select();
            foreach($gid as $v){
                $arr[]=$v['group_id'];
            }
            $info["gid"]=$arr;
            //获得角色列表
            $groupList=D('AuthGroup')->getGroupList();
            $this->assign('groupList',$groupList);
            $this->assign("info",$info);
            $this->display();
        }
    }


}