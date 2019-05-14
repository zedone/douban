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
			\util\Response::returnData(3,'数据不能为空',[]);
		}
		$userModel = new UserModel;
		$info = $userModel -> getLists($users);
		$tokenModel = new TokenModel;
		$tmp = [
			'id'    => $info['id'],
			'name'  => $info['name'],
			'phone' => $info['phone'],
			'password' => $info['password']
		];
		//dump($data);die;
		if(empty($info['phone'])){
			\util\Response::returnData(2,'用户不存在',[]);
		}
		if($users['password'] == $info['password']){
			$token = $tokenModel->setUserInfo($tmp);
			$result = array('token'=>$token,'info'=>$tmp);
			\util\Response::returnData(0,'登录成功',$result);
		}else{
			\util\Response::returnData(1,'密码错误',[]);
		}
		echo json_encode($result);die;
	}

	public function check(){
		return $this->fetch();
	}
	public function info(){
		$newToken = input('post.token');
		$newTime = time();
		if(empty($newToken)){
			\util\Response::returnData(1,'token为空',[]);
		}
		$token = new TokenModel;
		$token = $token -> getUserInfo($newToken);
		if(empty($token['token'])){
			\util\Response::returnData('2','token不存在',[]);
		}
		if($newToken == $token['token'] && $newTime<$token['expire']){
			$result = array('info'=>$token['value']);
			\util\Response::returnData('0','ok',$result);
		}
		echo json_encode($result);die;
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