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
		$tmp = [
			'id'    => $info['id'],
			'name'  => $info['name'],
			'phone' => $info['phone']
		];
		//dump($data);die;
		if($users['phone'] == $info['phone']){
			if($users['password'] == $info['password']){
				$token = $tokenModel->setUserInfo($tmp);
				$result = [
					'error' => 0,
					'msg'   => '登录成功',
					'data' =>[
						'token' => $token,
						'info' => $tmp
					]	
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
	public function info(){
		$newToken = input('token');
		$newTime = time();
		$token = new TokenModel;
		$token = $token -> getUserInfo($newToken);
		
		if(request()->isPost()){
			if(empty($newToken)){
				$result = [
					'error' => 1,
					'msg'   => 'token不能为空'
				];
			
			}elseif($newToken == $token['token'] && $newTime<$token['expire']){
				$value = $token->getTokenInfo($newToken);
				//$info[] = $value;
				//dump($value);die;
				$result = [
					'error' => 0,
					'msg'   => 'ok',
					'data' => [
						'info' => $value
					]
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

	public function regs(){
		return $this->fetch();
	}
	public function reg(){	
		if(request()->isPost()){
			$data = input('post.');
			//dump($data);die;
			$result = [];
			$user = new UserModel;
			if(empty($data['name']) || empty($data['phone']) || empty($data['password'])){
				$result = [
				'error' => 1,
				'msg'   => '数据不能为空',
				];
				return json_encode($result);die;
			}
			//dump($data);die;
			$phone = $user->getLists($data);
			
			if($data['phone'] == $phone['phone']){

				$result = [
					'error' => 2,
					'msg'   => '电话号码已被注册',
				];
				return json_encode($result);die;
			}
			$userReg = $user->regUser($data);
			if($userReg){
				
				$result = [
					'error' => 0,
					'msg'   => '注册成功',
					'data' => $data
				];	
			}	
			return json_encode($result);
		}
		
	}
}