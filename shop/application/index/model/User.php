<?php
namespace app\index\model;
use think\Model;
use think\Db;

class User extends Model{
	public function getLists($users){
		$lists =  $this->where('phone',$users['phone'])->find();
        //$lists = $lists -> toArray();
        return $lists;
	}

	public function regUser($data){
		return $this->save($data);
	}
}