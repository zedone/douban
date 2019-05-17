<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Attr extends Model{
    public function addType($data){
    	return $this->save($data);
    }

    public function typeLists($id){
    	$result = [];
    	$lists =  $this->where('goods_id',$id)->select();
    	if(!$lists){
    		return false;die;
    	}
    	foreach ($lists as  $value) {
			$result[] = $value->toArray();
		}
    	return $result;
    }
}
