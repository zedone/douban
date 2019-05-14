<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Attr extends Model{

	public function getLists($id){
		$lists =  $this->where('goods_id',$id)->select();
		$res = [];
        foreach ($lists as $value) {
        	$res[] = $value->toArray();
        }
        return $res;
	}

	public function formatAttr($attr){
		$res = [];
		foreach ($attr as  $value) {
			$res[] = [
				'goods_id'  => $value['goods_id'],
				'attr_type' => $value['attr_type'],
				'attr_name' => $value['attr_name'],
				'pic'       => $value['pic']
			];
 		}
 		//dump($res);die;
 		return $res;
	}	
}
