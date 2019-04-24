<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Goods as GoodsModel;
use app\index\model\Banner as BannerModel;
use app\index\model\Tag as TagModel;

class Goods extends Controller{
	public function index(){

		$goodsModel = new GoodsModel;
		$bannerModel = new BannerModel;
		$tagModel = new TagModel;
		$goodsList = $goodsModel -> getLists();
		$tagList = $tagModel->getList();
		$tagList = $tagModel ->arr($tagList);
		//dump($tagList);die;
		$goodsRes = $goodsModel -> formatGoods($goodsList,$tagList);
		$banners = $bannerModel -> getLists();
		$bannerRes = $bannerModel -> formatBanner($banners);
		//dump($bannerRes);die;
		$result = [
			'error' =>  0 ,
			'msg'   => 'ok',
			'data'  =>[
				'goods' => $goodsRes,
				'banner'=> $bannerRes
			]
		];
		echo json_encode($result);
	}

	public function detail(){
		$id = input('id');
		if(empty($id)){
			$result = [
				'error'  =>  1,
				'msg'=> 'param error'
			];
			echo json_encode($result);die;
		}

		$goodsModel = new GoodsModel;
		$tagModel = new TagModel;
		$tagList = $tagModel -> getList();
		$tagList = $tagModel -> arr($tagList);
		$goodsInfo = $goodsModel -> getById($id);
		$goodsInfo = $goodsModel -> getGoodsInfo($goodsInfo,$tagList);
		//dump($goodsInfo);die;
		$result = [
			'error'  =>  0,
			'msg'    => 'ok',
			'data'   => [
				'info' => $goodsInfo,
				//'content'=>$content
			],
		];
		//dump($result);die;
		echo json_encode($result);
	}
}