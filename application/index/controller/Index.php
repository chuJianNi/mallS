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
			trace('pas:'.$res["inputPassword"],'debug');
			trace('pasAfterMd5:'.md5(md5($res["inputPassword"])),'debug');
			$data=["user_id"=>$res["inputAccount"],
				"user_name"=>$res["inputUsername"],
				"password"=>md5(md5($res["inputPassword"])),
				"mark"=>$res["inputMark"],
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

	function checkPas()
	{
		if(request()->isPost())
		{
			trace('oldpasword: '.input('post.pas'),'debug');
			trace('oldpaswordAfterMd5: '.md5(md5(input('post.pas'))),'debug');
			$val=Db::table('goods_user')->where(['id'=>input('post.id'),'password'=>md5(md5(input('post.pas')))])->count();
			if($val === 1){
				return json(["info"=>"right"]);
			}else{
				return json(["info"=>"error"]);
			}
		}
	}

	function getuser()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			$val=Db::table('goods_user')->field('id,user_id,user_name,mark,valid')->select();
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

	function userinfo()
	{
		if(request()->isPost())
		{
			$res=input('post.id');
			$val=Db::table('goods_user')->field('user_id,user_name,valid,mark')->where('id',$res)->find();
			return json($val);
		}
	}

	function edituser()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			$val=Db::table('goods_user')->where('id',$res['id'])->update(['mark'=>$res['inputMarkM'],'valid'=>$res['optionsRadiosM']]);
			error_log('update goods_user ,field: mark valid.');
			trace('update goods_user. field:mark valid.','debug');
			trace($val,'debug');
			if($val === 1){
				return json(["info"=>"done"]);
			}else{
				return json(["info"=>"error"]);
			}
		}
	}
	function editpas()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			$val=Db::table('goods_user')->where('id',$res['id'])->update(['password'=>md5(md5($res['pas']))]);
			if($val === 1){
				return json(["info"=>"done"]);
			}else{
				return json(["info"=>"error"]);
			}
		}
	}
}
?>
