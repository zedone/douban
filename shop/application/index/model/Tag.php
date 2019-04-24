<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Tag extends Model{
	public function getList(){
		$lists = $this->select();
		$result = [];
		foreach ($lists as  $value) {
			$result[] = $value->toArray();
		}
		return $result;
	}

	public function  arr($tag){
		$res = [];
		foreach ($tag as $key => $value) {
			$res[$value['id']] = $value;
		}
		return $res;
	}

}