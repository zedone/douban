<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Banner extends Model{
	public function getLists(){
		$lists = $this->select();
		return $lists;
	}

	public function formatBanner($banners){
		$result = [];
		foreach ($banners as $value) {
			$item = [
				"img" => "http://www.shop.com/".$value['img'],
                "url" => "http://www.shop.com/".$value['url']
			];
			$result[] = $item;
		}
		return $result;
	}
}