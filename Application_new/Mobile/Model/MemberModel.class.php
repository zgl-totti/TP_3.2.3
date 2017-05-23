<?php

namespace Mobile\Model;
use Think\Model;
class MemberModel extends Model
{
    protected $_validate = array(
        //array('验证字段','验证规则','错误提示',['验证条件','附加规则','验证时间']);
        array('username', 'require', '用户名不能为空'),
        array('username', '2,15', '用户名长度在2-15个字符之间', 0, 'length'),
        array('password', '6,20', '密码长度在5-20字符之间', 0, 'length'),
        array('repwd', 'password', '两次密码输入不匹配', 0, 'confirm'),
        /*杨晶修改*/
        /*     array('username','','用户名不唯一',0,'unique'),*/
        array('email','email','格式必须为邮箱'),
        /*        array('email','','邮箱已被注册!',1,'unique'),     //unique可以验证非主键字段*/
        array('mobile','/^1[34578]\d{9}/','手机号格式不正确'),
        array('mobile','11','手机号长度是11位',0,'length'),
        array('qq','/^[1-9]\d{4,11}/','qq号码格式不正确')
    );
    //检验账户密码是否和数据库中的匹配
    /*    protected $_map = array(
            'username' => 'username',
        );*/
    //检验账户密码是否和数据库中的匹配
    public function logined($data)
    {
        return $this->where($data)->find();
    }
}
