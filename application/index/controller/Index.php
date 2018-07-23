<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
class Index
{
	function index()
	{
	//		return view('bas');
        	return view('index');
	//	return view('bootstrap');
//		dump(config());
	}

	function goods()
	{
		return view('goods');
	}

	function adduser()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			//dump($res);
			$data=["user_id"=>$res["inputAccount"],
				"user_name"=>$res["inputUsername"],
				"password"=>$res["inputPassword"],
				"valid"=>$res["optionsRadios"]];
			$val=Db::table('goods_user')->insert($data);	
			if($val === 1)
			{
				return json(["info"=>"保存成功"]);
			}
			return json(["info"=>"保存失败"]);
		}
	}

	function checkUsername()
	{
		if(request()->isPost())
		{
			$val=Db::table('goods_user')->where(['user_name'=>input('post.user_name')])->count();
			if($val === 1){
				return json(["info"=>"have"]);
			}else{
				return json(["info"=>"no"]);
			}
		}
	}
	function checkUserid()
	{
		if(request()->isPost())
		{
			$val=Db::table('goods_user')->where(['user_id'=>input('post.user_id')])->count();
			if($val === 1){
				return json(["info"=>"have"]);
			}else{
				return json(["info"=>"no"]);
			}
		}
	}

	function getuser()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			$val=Db::table('goods_user')->field('id,user_id,user_name,valid')->select();
			$res['data']=$val;
			return json($res);
		}
	}

	function deluser()
	{
		if(request()->isPost())
		{
			$res=input('post.user_id');
			$val=Db::table('goods_user')->delete($res);
			if($val === 1){
				return json(["info"=>"done"]);
			}else{
				return json(["info"=>"error"]);
			}
		}
	}
}
?>
