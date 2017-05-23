<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model{
    protected $_validate=array(
        //管理员账号验证
        //array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('adminname','2,15','用户名长度必须在2-15个字符之间',0,'length'),
        array('password','2,15','密码长度必须在2-15个字符之间',0,'length'),
    );
}