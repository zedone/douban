<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Token as TokenModel;
class User extends Controller{
	public function log(){
		return $this->fetch();
	}

	public function doLogin(){
			$users = [
				'phone' => input('post.phone'),
				'password' => input('post.password')
			];
			//dump($users);die;
			if(empty($users['phone']) || empty($users['password'])){
				echo '参数错误1';die;
			}
			$userModel = new UserModel;
			$info = $userModel -> getLists($users);
			$tokenModel = new TokenModel;

			//dump($users);die;
			if($users['phone'] == $info['phone']){
				if($users['password'] == $info['password']){
					$token = $tokenModel->setUserInfo($users);
					$result = [
						'error' => 0,
						'msg'   => '登录成功',
						'token' => $token
					];
				}else{
				$result = [
					'error' => 1,
					'msg'   => '密码错误',
					];	
				}
			}else{
				$result = [
				'error' => 2,
				'msg'   => '用户不存在',
				];
			}
			echo json_encode($result);die;
	}

	public function check(){
			return $this->fetch();
	}
	public function doCheck(){
		$newToken = input('token');
		$newTime = time();
		$token = new TokenModel;
		$token = $token -> getUserInfo($newToken);
		// dump($token).'<br>';
		// dump($newToken);die;
		if(request()->isPost()){
			if(empty($newToken)){
				$result = [
					'error' => 1,
					'msg'   => 'token不能为空'
				];
			
			}elseif($newToken == $token['token'] && $newTime<$token['expire']){
				$result = [
					'error' => 0,
					'msg'   => 'ok',
					'token' => $token
				];
			}else{
				$result = [
					'error' => 2,
					'msg'   => 'token不存在',
				];
			}

		}
		echo json_encode($result);
	}

}