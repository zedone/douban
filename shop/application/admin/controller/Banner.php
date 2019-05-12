<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Banner as BannerModel;

class Banner extends Controller
{
	public function add(){
		return $this->fetch();
	}

	public function doAdd(){
		$data = input('post.');
		//dump($data);die;
		$BannerModel = new BannerModel;
		$item = [
			'url'  => $data['url'],
			'status'   => $data['status'],
		];
		$fileName = \util\File::upload('img');
		$item['img'] = $fileName;
		$BannerModel = $BannerModel -> saveBanner($item);
		if($BannerModel){
			$this->success('success');
		}else{
			$this->error('fail');
		}
	}
}