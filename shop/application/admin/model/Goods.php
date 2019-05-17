<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Goods extends Model{
	public function saveGoods($data){
		$lists =  $this->save($data);
		return $lists;
	}

	public function goodsLists(){
		$lists = $this->select();
		$result = [];
		foreach ($lists as  $value) {
			$result[] = $value->toArray();
		}
		return $result;
		
	}
	public function formatGoods($id){
		$lists = $this->where('id',$id)->find();
		//$lists = $lists->toArray();
		$lists =  empty($lists) ? array():$lists->toArray();
		return $lists;
	}

	public function keyWords($search)
    {
        //return $this->where($goodsname,'like',"%{$key}%")->select();
        $result = $this->where('name','like',"%".$search."%")->select();  
        return $result;
    }

}
