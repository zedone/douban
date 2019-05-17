<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Tag extends Model{
	public function getTagList($tagid){
		$result = [];
		$lists = $this->where('id',$tagid)->select();
		foreach ($lists as  $value) {
			$result[] = $value->toArray();
		}
		return $result;
		//return $this->where('id',$tagid)->select();
	}
}
