<?php
namespace Admin\Model;
use \Think\Model;
class IntegralModel extends Model{

    protected $_validate=array(
        //array('验证字段','验证规则','错误提示',['验证条件','附加规则','验证时间']);
        //验证规则：require 字段值必须、email 邮箱、url URL地址、currency 货币、number 数字、double浮点、integer整数、zip邮政编码、english英文
        //验证条件：0代表字段存在时必须验证、1代表字段必须验证、2代表字段存在而且不为空时验证
        //附加规则:默认为regex,附加规则的值决定了验证规则的值
        //验证时间:1代表添加时验证，2代表更新时验证，3代表任何情况下都验证
        array('goodsname','require','商品名称不能为空',1,'regex',3),
        array('goodsname','','商品名称已经存在！',0,'unique',3),
        array('integral','require','商品积分不能为空',1,'regex',3),
        array('num','require','  库存数量不能为空',1,'regex',3)
    );

    public function addGoods($data){
        $gid= $this->add($data);
        return $gid;
    }



}