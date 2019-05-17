<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Cart extends Model{
	public function add($data){
		return $this->save($data);
	}
	//通过userid查询cart表中的数据
	public function getLists($field,$info){
		$lists = $this->where($field,$info)->select();
		$result = [];
		foreach ($lists as  $value) {
			$result[] = $value->toArray();
		}
		//dump($result);die;
		return $result;
	}
	public function repeatNum($field1,$info,$field2,$data){
		return $this->where($field1,$info)->setField($field2, $data);
	}

	//删除购物车里的物品数量
	public function delNum($id,$tmpcount){
		return $this->where('user_id',$id)->setField('count',$tmpcount);
	}

	public function delCart($userid){
		return $this->where('user_id',$userid)->delete();
	}
}