<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Token extends Model{

	public function getUserInfo($token) {
        $lists = $this->where('token',$token)->find();
        if($lists){
            $lists->toArray();
        }
        if(!$lists){
            return false;die;
        }
        $lists['value'] = unserialize($lists['value']);
        return $lists;
    }

    public function setUserInfo($userInfo) {
        $token = $this->getToken($userInfo);
        $value = serialize($userInfo);
        $data = [
        	'token' => $token,
        	'value' => $value,
        	'expire' => time()+86400*30,
        ];
        $this->insert($data);
        return $token;
    }

    public function getToken($user=array()) {
        $time =  md5(time().rand(1,100000));
        $user = md5(serialize($user));
        return 'user_token_'.md5($time. $user);
    }
}
