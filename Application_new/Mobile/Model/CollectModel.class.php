<?php
namespace Mobile\Model;
use Think\Model;
class CollectModel extends Model{

    public function addCollect($gid,$mid){
            $data['gid'] = $gid;
            $data['mid'] = $mid;
            $data['addtime'] = time();
            $addcollect = $this->add($data);
            return $addcollect;

    }

}