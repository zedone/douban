<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Token extends Model{

	 public function getUserInfo($token) {
       // $res = [];
        $lists = $this->where('token',$token)->find();
        if($lists){
            $lists->toArray();
        }
        return $lists;
    }

    public function setUserInfo($userInfo) {
        $token = $this->getToken($userInfo);
        $value[] = $userInfo;
        $value = serialize($value);
        $data = [
        	'token' => $token,
            //'value' => $value,
        	'value' => $value,
        	'expire' => time()+86400*30,
        ];
        //dump($data);die;
        $this->insert($data);
        return $token;
    }

    public function getToken($user=array()) {
        $time =  md5(time().rand(1,100000));
        $user = md5(serialize($user));
        return 'user_token_'.md5($time. $user);
    }
}