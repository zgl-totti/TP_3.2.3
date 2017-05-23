<?php
namespace Admin\Model;
use \Think\Model;
class GoodsCommentModel extends Model{
    public function getComment(){
        return $this->select();
    }
}