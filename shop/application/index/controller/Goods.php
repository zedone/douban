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
		
		$result = array('goods'=>$goodsRes,'banner'=>$bannerRes);
		\util\Response::returnData(0,'ok',$result);
	}

	public function detail(){
		$id = input('id');
		if(empty($id)){
			\util\Response::returnData(1,'param error',[]);
			
		}

		$goodsModel = new GoodsModel;
		$tagModel = new TagModel;
		$tagList = $tagModel -> getList();
		$tagList = $tagModel -> arr($tagList);
		$goodsInfo = $goodsModel -> getById($id);
		$goodsInfo = $goodsModel -> getGoodsInfo($goodsInfo,$tagList);
		$result = array('info'=>$goodsInfo);
		\util\Response::returnData(0,'ok',$result);
		//echo json_encode($result);
	}
}