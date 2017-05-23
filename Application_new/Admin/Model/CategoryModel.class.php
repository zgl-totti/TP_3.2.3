<?php
namespace Admin\Model;
use \Think\Model;
class CategoryModel extends Model{
    //返回出所有的列表内容
    public function getCateList(){
        return $this->where(array('pid'=>0))->select();
    }
    //查询子类
    public function getChildList($pid=0,$list=array()){
        $arr = $this->where("pid=$pid")->select();  //目前为止得到的是四个父类
        if($arr){   //当它不为空的时候做循环
            foreach($arr as $k=>$value) {
                $res['id'] = array('in', "{$value['path']}");   //查找id值在获得商品的path中(例如手机id=1 在子类的path中 满足条件)
                $data = $this->order('path')->where($res)->getField('catename', true);
                foreach ($data as $k1 => $v1) {
                    $value['pathname'] .= '>' . $v1;
                    $value['pathname'] = ltrim($value['pathname'], ">"); //去除最左面的>
                }
                $list[] = $value;
            }
            return $list;
        }else{
            return false;
        }
    }
}