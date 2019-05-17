<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Attr as AttrModel;
use app\admin\model\Goods as GoodsModel;
use app\admin\model\Tag as TagModel;

class Goods extends Controller
{
	public function add(){
		return $this->fetch();
	}
	public function doAdd(){
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

	public function lists(){
		$search= input('post.keywords');
		$goodsModel = new GoodsModel;
		$goodsRes = $goodsModel -> goodsLists();
		$tagModel = new TagModel;
		foreach ($goodsRes as $key => $value) {
			//$tagId[$value['id']] = $value['tag_id'];
			$tagId[] = $value['tag_id'];
		}
		$tagRes = [];
		foreach ($tagId as $value) {
			$tagRes[] = $tagModel -> getTagList($value);
		}
		$key = $goodsModel -> keyWords($search);
		$this->assign('key',$key);
		//dump($key);die;
		$this->assign('goodsRes',$goodsRes);
		$this->assign('tagRes',$tagRes);
		return $this->fetch();
	}

	public function search(){
		$search= input('get.keywords');
		$goodsModel = new GoodsModel;
		$key = $goodsModel -> keyWords($search);
		//dump($key);die;
		$this->assign('key',$key);
		return $this->fetch();
	}
	public function uptype(){
		$id = input('id');
		$attrModel = new AttrModel;
		$attrLists = $attrModel -> typeLists($id);
		$this->assign('attrLists',$attrLists);
		return $this->fetch();
	}
	public function addtype(){
		$id = input('id');
		$res= [];
		$goodModel = new GoodsModel;
		$goodsLists = $goodModel -> formatGoods($id);
		//dump($goodsLists);die;
		$this->assign('goodsLists',$goodsLists);
		if(request()->isPost()){
			$data = [
				'goods_id' => input('post.goods_id'),
                'attr_type' => input('post.attr_type'),
                'attr_name'   => input('post.attr_name'), 
            ]; 
            $fileName = \util\File::upload('pic');
			$data['pic'] = $fileName;
			//dump($data);die;
			$attrModel = new AttrModel;
			$addType = $attrModel -> addType($data);
			if($addType){
				$this->success('success','lists');
			}else{
				$this->error('fail');
			}
		}
		
		return $this->fetch();
	}
}