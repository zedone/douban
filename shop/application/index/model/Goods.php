<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Goods extends Model{
	public function getLists(){
		$lists = $this->select();
		return $lists;
	}

	public function formatGoods($goods,$tag=array()){
		$result = [

		];
		foreach ($goods as $value) {
			$item = [
				'id' => $value['id'],
				'title' => $value['name'],
				'desc' => $value['pro'],
				'img' => 'http://www.shop.com/'.$value['img'],
				'price' => "￥".$value['price'] / 100,
				'tag' => []
			];
			$tagArr = explode(',',$value['tag_id']);
			//dump($tagArr);die;
			//dump($tag);die();
			foreach ($tagArr as $key => $tagId) {
				$item['tag'][] = $tag[$tagId];
			}
			//dump($item);die;	
			//dump($tag[$tagId]);die;
			$result[] = $item;
		}
		return $result;
	}

	public function getById($id){
		return $this->where('id',$id)->find();
	}

	public function getGoodsInfo($item,$tag=array()){
		$value = [
			'id' => $item['id'],
			'img'   => 'http://www.shop.com/'.$item['img'],
            'title' => $item['name'],
            'desc'  => $item['pro'],
            'tag_id'=> $item['tag_id'],
            'price' => "￥".$item['price'] / 100,
            'tag'   => [],
            'content'=>htmlspecialchars_decode($item['content']),
		];
		//dump($value['tag']);die;
		$tagArr = explode(',',$value['tag_id']);
		//dump($tagArr);die;
		foreach ($tagArr as $key => $tagId) {
			$value['tag'][] = $tag[$tagId];
		}
		//dump($value);die;
		//$result = $value;
		return $value;
	}
}






