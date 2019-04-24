<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Goods as GoodsModel;

class Goods extends Controller
{
	public function add(){
		return $this->fetch();
	}
	public function doAdd(){
		//var_dump($_POST,$_REQUEST);die();
		//var_dump(input('post.'));die;
		$data = input('post.');
		$GoodsModel = new GoodsModel;
		$item = [
			'name'  => $data['name'],
			'pro'   => $data['pro'],
			'price' => $data['price']*100,
			'tag_id'=> $data['tag_id'],
			'content'=>$data['content']
		];
		
		//dump($item);die;
		$fileName = \util\File::upload('img');
		$item['img'] = $fileName;
		//dump($item);die;
		$GoodsModel = $GoodsModel->saveGoods($item);
		if($GoodsModel){
			$this->success('success');
		}else{
			$this->error('fail');
		}
	}
}