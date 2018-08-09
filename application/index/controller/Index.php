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

	function addUser()
	{
		if(request()->isPost())
		{
			$res=(input('post.'));
			$where=$res['where'];
			$updates = $res['updates'];
			$val=Db::table('goods_user')->where(['user_name'=>$where['username']])->count();
			if($val === 1){
				return json(["msg"=>"用户名被占用","info"=>"error"]);
			}
			$val=Db::table('goods_user')->where(['user_id'=>$where['self']])->count();
			if($val === 1){
				return json(["msg"=>"账号被占用","info"=>"error"]);
			}
			//dump($res);
			trace('pas:'.$updates["inputPassword"],'debug');
			trace('pasAfterMd5:'.md5(md5($updates["inputPassword"])),'debug');
			$data=["user_id"=>$updates["self"],
				"user_name"=>$updates["inputUsername"],
				"password"=>md5(md5($updates["inputPassword"])),
				"mark"=>$updates["inputMark"],
				"valid"=>$updates["optionsRadios"]];
			$val=Db::table('goods_user')->insert($data);	
			if($val === 1)
			{
				return json(["info"=>"ok","msg"=>"保存用户信息成功"]);
			}
			return json(["info"=>"error","msg"=>"保存用户信息失败"]);
		}
	}

	function checkUsername()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			$where=$res['where'];
			$val=Db::table('goods_user')->where(['user_name'=>$where['self']])->count();
			if($val === 0){
				return json(["info"=>"ok","msg"=>""]);
			}
			if($val === 1){
				return json(["info"=>"error","msg"=>"用户名已被使用"]);
			}
			return json(["info"=>"error","msg"=>"未知错误"]);
		}
	}
	function checkUserid()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			$where=$res['where'];
			$val=Db::table('goods_user')->where(['user_id'=>$where['self']])->count();
			if($val === 0){
				return json(["info"=>"ok","msg"=>""]);
			}
			if($val === 1){
				return json(["info"=>"error","msg"=>"账号已被使用"]);
			}
			return json(["info"=>"error","msg"=>"未知错误"]);
		}
	}


	function checkAndEditPas()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			$where=$res['where'];
			$updates=$res['updates'];
			//记录log sad
			trace('oldpasword: '.$where['pwdOld'],'debug');
			trace('oldpaswordAfterMd5: '.md5(md5($where['pwdOld'])),'debug');
			$val=Db::table('goods_user')->where(['id'=>$where['id'],'password'=>md5(md5($where['pwdOld']))])->count();
			if($val === 1){
				$val=Db::table('goods_user')->where('id',$where['id'])->update(['password'=>md5(md5($updates['self']))]);
				if($val === 1){
					return json(["info"=>"ok","msg"=>"修改密码成功"]);
				}
				if($val === 0){
					return json(["info"=>"error","msg"=>"没有修改操作"]);
				}
				return json(["info"=>"error","msg"=>"未知错误"]);

			}
			if($val === 0){
				return json(["info"=>"error","msg"=>"原密码不正确"]);
			}
			return json(["info"=>"error","msg"=>"未知错误"]);
		}

	}

	function checkPas()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			$where=$res['where'];
			$val=Db::table('goods_user')->where(['id'=>$where['id'],'password'=>md5(md5($where['self']))])->count();
			if($val === 1){
				return json(["info"=>"ok","msg"=>""]);
			}
			if($val === 0){
				return json(["info"=>"error","msg"=>"原密码错误"]);
			}
			return json(["info"=>"error","msg"=>"未知错误"]);
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

	function delUser()
	{
		if(request()->isPost())
		{
			trace('try delete user in  table: goods_user ...','debug');
			$res=input('post.');
			$where=$res['where'];
			$val=Db::table('goods_user')->delete($where['user_id']);
			if($val === 1){
				trace('primary id:'.$where['user_id'].' is deleted in table:goods_user','debug');
				return json(["info"=>"ok","msg"=>"删除用户成功"]);
			}
			if($val === 0){
				trace('primary id:'.$where['user_id'].' not exist in table:goods_user','debug');
				return json(["info"=>"error","msg"=>"没有用户信息"]);
			}
			return json(["info"=>"error","msg"=>"未知错误"]);
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

	function editUser()
	{
		if(request()->isPost())
		{
			$res=input('post.');
			$where=$res['where'];
			$updates=$res['updates'];
			$val=Db::table('goods_user')->where('id',$where['id'])->update(['mark'=>$updates['self'],'valid'=>$updates['optionsRadiosM']]);
			trace('update goods_user. field:mark valid.','debug');
			trace($val,'debug');
			if($val === 1){
				return json(["info"=>"ok","msg"=>"修改用户信息成功"]);
			}
			if($val === 0){
				return json(["info"=>"error","msg"=>"没有修改操作"]);
			}
			return json(["info"=>"error","msg"=>"未知错误"]);
		}
	}
	
	function mPwd()
	{
		$arg=$_GET['arg'];
		$arg=explode(",",$arg);
		return view('mPwd',['args'=>$arg]);
	}

	function mInfo()
	{
		$arg=$_GET['arg'];
		$arg=explode(",",$arg);
		return view('mInfo',['args'=>$arg]);
	}
}
?>
