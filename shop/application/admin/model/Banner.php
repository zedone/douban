<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Banner extends Model{
	public function saveBanner($data){
		$lists =  $this->save($data);
		return $lists;
	}
}
