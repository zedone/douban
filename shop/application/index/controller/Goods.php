<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Goods as GoodsModel;
use app\index\model\Banner as BannerModel;
use app\index\model\Tag as TagModel;
use app\index\model\Cart;
use app\index\model\Token;
use app\index\model\Attr;

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
			\util\Response::returnData(1,'参数错误',[]);
		}
		$goodsModel = new GoodsModel;
		$tagModel = new TagModel;
		$tagList = $tagModel -> getList();
		$tagList = $tagModel -> arr($tagList);
		$goodsInfo = $goodsModel -> getById($id);
		$goodsInfo = $goodsModel -> getGoodsInfo($goodsInfo,$tagList);
		$attrModel  = new Attr;
		$attr = $attrModel -> getLists($id);
		$attrFormat = $attrModel -> formatAttr($attr);
		$goodsInfo['attr'] = $attrFormat;

		$res = array('info'=>$goodsInfo);
		\util\Response::returnData(0,'ok',$res);
		//echo json_encode($result);
	}

	public function cart(){
		$data = input('post.');
		$id = input('post.id');
		$newToken = input('post.token');
		$newTime = time();
		if(empty($newToken)){
			\util\Response::returnData(1,'token不能为空',[]);die;
		}
		$tokenModel = new Token;
		$token = $tokenModel->getUserInfo($newToken);
		//$lists = $tokenModel -> getUserInfo($newToken);
		if(!$token){
			\util\Response::returnData(2,'token不存在',[]);die;
		}
		if($newTime > $token['expire']){
			\util\Response::returnData(3,'token已过期',[]);die;
		}
		$goodsModel = new GoodsModel;
		$goodsInfo = $goodsModel -> getById($id);
		
		$data = [
			'goods_id'  => $goodsInfo['id'],
			'count'     => $data['count'],
			'color'     => $data['color'],
			'price'     => $goodsInfo['price'],
			'user_id'   => $token ['value']['id'],
			'size'      => $data['size']
		];
		$cartModel = new Cart;
		$count = $data['count'];
  		$cartLists = $cartModel -> getLists('user_id',$token['value']['id']);
  		foreach ($cartLists as $value) {
  			if($value['goods_id'] == $goodsInfo['id'] && $value['size'] == $data['size']){
	  			$count = $value['count'] +$count;
	  			$result = $cartModel->repeatNum('id',$value['id'],'count',$count);
	  			\util\Response::returnData(0,'已加入购物车',[]);
  			if(!$result){
                \util\Response::returnData(4,'增加失败',[]);die;
           	}
  		}	
  	}
  		$addCart = $cartModel -> add($data);
  		\util\Response::returnData(0,'已加入购物车',[]);
	}

	public function cartInfo(){
		$newToken = input('post.token');
		$newTime = time();
		$cart = [];
		if(empty($newToken)){
			\util\Response::returnData(1,'token不能为空',[]);die;
		}
		$tokenModel = new Token;
		$token = $tokenModel->getUserInfo($newToken);
		if(!$token){
			\util\Response::returnData(2,'token不存在',[]);die;
		}
		// dump($token);die;
		if($newTime > $token['expire']){
			\util\Response::returnData(3,'token已过期',[]);die;
		}
		$userid = $token['value']['id'];
		$cartModel = new Cart;
		$cartLists = $cartModel -> getLists('user_id',$userid);
		//dump($cartLists);die;
		foreach ($cartLists as $value) {
			$goodsModel = new GoodsModel;
			$goodsLists = $goodsModel->getInfo('id',$value['goods_id']);
			$cart[] = [
			'name'  => $goodsLists['name'],
            'color' => $value['color'],
            'price' => $goodsLists['price'],
            'count' => $value['count'] ,
			];
		}
		
		$result = array('cart'=>$cart);
		\util\Response::returnData(0,'购物车列表',$result);
	}

	public function cartDec(){
		$newToken = input('post.token');
		$newTime = time();
		if(empty($newToken)){
			\util\Response::returnData(1,'token不能为空',[]);die;
		}
		$tokenModel = new Token;
		$token = $tokenModel->getUserInfo($newToken);
		if(!$token){
			\util\Response::returnData(2,'token不存在',[]);die;
		}
		// dump($token);die;
		if($newTime > $token['expire']){
			\util\Response::returnData(3,'token已过期',[]);die;
		}

		$data = input('post.');
		//商品id
		$goods_id = input('post.goods_id');
		//通过token拿用户id
		$id = $token['value']['id'];
		$cartModel = new Cart;
		$res = $cartModel -> getLists('user_id',$id);
		foreach ($res as  $value) {
			if($value['goods_id'] == $goods_id){
			$count   = !empty($data['count']) ?$data['count']:1;
			$tmpcount = 0;
			$tmpcount = $value['count'] - $count;
			if($tmpcount>0){
				$result = $cartModel -> delNum($id,$tmpcount);
				\util\Response::returnData(0,'删除成功',[]);die;
				}else{
					\util\Response::returnData(2,'删除数量大于原有数量',[]);die;
				}
			}else{
				\util\Response::returnData(1,'商品不存在',[]);die;
			}
		}	
	}
	//清空购物车
	public function delCart(){
		$newToken = input('post.token');
		$newTime = time();
		if(empty($newToken)){
			\util\Response::returnData(1,'token不能为空',[]);die;
		}
		$tokenModel = new Token;
		$token = $tokenModel->getUserInfo($newToken);
		if(!$token){
			\util\Response::returnData(2,'token不存在',[]);die;
		}
		// dump($token);die;
		if($newTime > $token['expire']){
			\util\Response::returnData(3,'token已过期',[]);die;
		}
		$userid = $token['value']['id'];
		
		$cartModel = new Cart;
		$delCartlists = $cartModel -> delCart($userid);
		//dump($delCartlists);die;
		if($delCartlists){
			\util\Response::returnData(0,'清空购物车成功',[]);die;
		}else{
			\util\Response::returnData(0,'清空失败',[]);die;
		}
	}

}
	

	