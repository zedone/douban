<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Goods extends Model{
	public function saveGoods($data){
		$lists =  $this->save($data);
		return $lists;
	}

	public function goodsLists(){
		return $this->select();
	}
}
