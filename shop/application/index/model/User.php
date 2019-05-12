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
	// public function checkLogin($data){
	// 	$token = input('token');
	// 	$time =  md5(time().rand(1,100000));
	// 	$logToken = 'user_token_'.md5($time. $user);
	// }
}