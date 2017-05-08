<?php
class qq_login{
	public function __construct(){
		$this->loginapi=''; //可填写登录API
	}
	public function dovc($uin,$sig,$ans,$cap_cd,$sess,$websig){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'QQ不能为空');
		if(empty($sig))return array('saveOK'=>-1,'msg'=>'sig不能为空');
		if(empty($ans))return array('saveOK'=>-1,'msg'=>'验证码不能为空');
		if(empty($cap_cd))return array('saveOK'=>-1,'msg'=>'cap_cd不能为空');
		if(empty($sess))return array('saveOK'=>-1,'msg'=>'sess不能为空');
		if(strpos($ans,',')){
			$subcapclass=9;
		}else{
			$subcapclass=0;
		}
		$url='http://captcha.qq.com/cap_union_new_verify';
		$post='aid=549000912&captype=&protocol=http&clientype=1&disturblevel=&apptype=2&noheader=0&uid='.$uin.'&color=&showtype=&fb=1&lang=2052&cap_cd='.$cap_cd.'&rnd='.rand(100000,999999).'&rand=0.744936'.time().'&sess='.$sess.'&subcapclass='.$subcapclass.'&vsig='.$sig.'&ans='.$ans.'&collect='.$this->get_curl('http://getcollect.duapp.com/').'&websig='.$websig;
		$data=$this->get_curl($url,$post);
		$arr=json_decode($data,true);
		if(array_key_exists('errorCode',$arr) && $arr['errorCode']==0){
			return array('rcode'=>0,'randstr'=>$arr['randstr'],'sig'=>$arr['ticket']);
		}elseif($arr['errorCode']==50){
			return array('rcode'=>50,'errmsg'=>'验证码输入错误！');
		}elseif($arr['errorCode']==12 && $subcapclass==9){
			return array('rcode'=>12,'errmsg'=>$arr['errMessage']);
		}else{
			return array('rcode'=>9,'errmsg'=>$arr['errMessage']);
		}
	}
	public function getvcpic($uin,$sig,$cap_cd,$sess){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'QQ不能为空');
		if(empty($sig))return array('saveOK'=>-1,'msg'=>'sig不能为空');
		$url='http://captcha.qq.com/cap_union_new_getcapbysig?aid=549000912&captype=&protocol=http&clientype=1&disturblevel=&apptype=2&noheader=0&uid='.$uin.'&color=&showtype=&lang=2052&cap_cd='.$cap_cd.'&rnd='.rand(100000,999999).'&rand=0.02398118'.time().'&sess='.$sess.'&vsig='.$sig.'&ischartype=1';
		return $this->get_curl($url);
	}
	public function getvcpic2($uin,$sig,$cap_cd,$sess,$img_index=0){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'QQ不能为空');
		if(empty($sig))return array('saveOK'=>-1,'msg'=>'sig不能为空');
		$url='http://captcha.qq.com/cap_union_new_getcapbysig?aid=549000912&captype=&protocol=http&clientype=1&disturblevel=&apptype=2&noheader=0&uid='.$uin.'&color=&showtype=&lang=2052&cap_cd='.$cap_cd.'&rnd='.rand(100000,999999).'&rand=0.02398118'.time().'&sess='.$sess.'&vsig='.$sig.'&img_index='.$img_index;
		return $url;
	}
	public function qqlogin($uin,$pwd,$p,$vcode,$pt_verifysession){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'QQ不能为空');
		if(empty($pwd))return array('saveOK'=>-1,'msg'=>'pwd不能为空');
		if(empty($p))return array('saveOK'=>-1,'msg'=>'密码不能为空');
		if(empty($vcode))return array('saveOK'=>-1,'msg'=>'验证码不能为空');
		if(empty($pt_verifysession))return array('saveOK'=>-1,'msg'=>'pt_verifysession不能为空');
		if(strpos('s'.$vcode,'!')){
			$v1=0;
		}else{
			$v1=1;
		}
		$url='http://ptlogin2.qq.com/login?u='.$uin.'&verifycode='.strtoupper($vcode).'&pt_vcode_v1='.$v1.'&pt_verifysession_v1='.$pt_verifysession.'&p='.$p.'&pt_randsalt=0&u1=http%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=2-10-'.time().'7584&js_ver=10178&js_type=1&pt_uistyle=40&aid=549000912&daid=5&pt_ttype=1';
		$ret = $this->get_curl($url,0,0,0,1);
		if(preg_match("/ptuiCB\('(.*?)'\);/", $ret, $arr)){
			$r=explode("','",str_replace("', '","','",$arr[1]));
			if($r[0]==0){
				if(strpos($r[2],'mibao_vry'))
					return array('saveOK'=>-3,'msg'=>'请先到QQ安全中心关闭网页登录保护！');
				preg_match('/skey=@(.{9});/',$ret,$skey);
				preg_match('/superkey=(.*?);/',$ret,$superkey);
				$data=$this->get_curl($r[2],0,0,0,1);
				if($data) {
					preg_match("/p_skey=(.*?);/", $data, $matchs);
					$pskey = $matchs[1];
					preg_match("/Location: (.*?)\r\n/iU", $data, $matchs);
					$sid=explode('sid=',$matchs[1]);
					$sid=$sid[1];
				}
				if($skey[1] && $pskey){
					return array('saveOK'=>0,'uin'=>$uin,'sid'=>$sid,'skey'=>'@'.$skey[1],'pskey'=>$pskey,'superkey'=>$superkey[1],'nick'=>urlencode($r[5]));
				}else{
					if(!$pskey)
						return array('saveOK'=>-3,'msg'=>'登录成功，获取P_skey失败！'.$r[2]);
					elseif(!$sid)
						return array('saveOK'=>-3,'msg'=>'登录成功，获取SID失败！');
				}
			}elseif($r[0]==4){
				return array('saveOK'=>4,'msg'=>'验证码错误');
			}elseif($r[0]==3){
				return array('saveOK'=>3,'msg'=>'密码错误');
			}elseif($r[0]==19){
				return array('saveOK'=>19,'uin'=>$uin,'msg'=>'您的帐号暂时无法登录，请到 http://aq.qq.com/007 恢复正常使用');
			}else{
				return array('saveOK'=>-6,'msg'=>str_replace('"','\'',$r[4]));
			}
		}else{
			return array('saveOK'=>-2,'msg'=>$ret);
		}
	}
	public function getvc($uin,$sig,$sess){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'请先输入QQ号码');
		if(empty($sig))return array('saveOK'=>-1,'msg'=>'SIG不能为空');
		if(!preg_match("/^[1-9][0-9]{4,11}$/",$uin)) exit('{"saveOK":-2,"msg":"QQ号码不正确"}');
		if($sess=='0'){
			$url='http://captcha.qq.com/cap_union_new_show?aid=549000912&captype=&protocol=http&clientype=1&disturblevel=&apptype=2&noheader=0&uid='.$uin.'&color=&showtype=&lang=2052&cap_cd='.$sig.'&rnd='.rand(100000,999999);
			$data=$this->get_curl($url);
			if(strpos($data,'img_index=')){
				if(preg_match("/=\"([0-9a-zA-Z\*\_\-]{187})\"/", $data, $match1) && preg_match("/=\"([0-9a-zA-Z\*\_\-]{184})\"/", $data, $match2)){
					preg_match('/\$=Number\(\"(\d+)\"\)/', $data, $Number);
					preg_match('/websig=([0-9a-f]{32})/', $data, $websig);
					$height = $Number[1];
					$imgA = $this->getvcpic2($uin,$match1[1],$sig,$match2[1],1);
					$imgB = $this->getvcpic2($uin,$match1[1],$sig,$match2[1],0);
					$width = $this->captcha($imgA, $imgB);
					$ans = $width.','.$height.';';
					return array('saveOK'=>2,'vc'=>$match1[1],'sess'=>$match2[1],'websig'=>$websig[1],'ans'=>$ans);
				}else{
					return array('saveOK'=>-3,'msg'=>'获取验证码失败');
				}
			}else{
				if(preg_match("/=\"([0-9a-zA-Z\*\_\-]{129})\"/", $data, $match1) && preg_match("/=\"([0-9a-zA-Z\*\_\-]{184})\"/", $data, $match2)){
					preg_match('/websig=([0-9a-f]{32})/', $data, $websig);
					return array('saveOK'=>0,'vc'=>$match1[1],'sess'=>$match2[1],'websig'=>$websig[1]);
				}else{
					return array('saveOK'=>-3,'msg'=>'获取验证码失败');
				}
			}
		}else{
			$url='http://captcha.qq.com/cap_union_new_getsig';
			$post='aid=549000912&captype=&protocol=http&clientype=1&disturblevel=&apptype=2&noheader=0&uid='.$uin.'&color=&showtype=&cap_cd='.$sig.'&rnd=634076&rand=0.37335288'.time().'&sess='.$sess;
			$data=$this->get_curl($url,$post);
			$arr=json_decode($data,true);
			if($arr['initx'] && $arr['inity']){
				$height = $arr['inity'];
				$imgA = $this->getvcpic2($uin,$arr['vsig'],$sig,$sess,1);
				$imgB = $this->getvcpic2($uin,$arr['vsig'],$sig,$sess,0);
				$width = $this->captcha($imgA, $imgB);
				$ans = $width.','.$height.';';
				return array('saveOK'=>2,'vc'=>$arr['vsig'],'sess'=>$sess,'ans'=>$ans);
			}elseif($arr['vsig']){
				return array('saveOK'=>0,'vc'=>$arr['vsig'],'sess'=>$sess);
			}else{
				return array('saveOK'=>-3,'msg'=>'获取验证码失败');
			}
		}
	}
	public function checkvc($uin){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'请先输入QQ号码');
		if(!preg_match("/^[1-9][0-9]{4,13}$/",$uin)) exit('{"saveOK":-2,"msg":"QQ号码不正确"}');
		$url='http://check.ptlogin2.qq.com/check?pt_tea=2&uin='.$uin.'&appid=549000912&ptlang=2052&regmaster=&pt_uistyle=9&r=0.397176'.time();
		$data=$this->get_curl($url);
		if(preg_match("/ptui_checkVC\('(.*?)'\);/", $data, $arr)){
			$r=explode("','",$arr[1]);
			if($r[0]==0){
				return array('saveOK'=>0,'uin'=>$uin,'vcode'=>$r[1],'pt_verifysession'=>$r[3]);
			}else{
				return array('saveOK'=>1,'uin'=>$uin,'sig'=>$r[1]);
			}
		}else{
			return array('saveOK'=>-3,'msg'=>'获取验证码失败');
		}
	}
	private function captcha($imgAurl,$imgBurl){
		$imgA = imagecreatefromjpeg($imgAurl);
		$imgB = imagecreatefromjpeg($imgBurl);
		$imgWidth = imagesx($imgA);
		$imgHeight = imagesy($imgA);
		
		$t=0;$r=0;
		for ($y=20; $y<$imgHeight-20; $y++){
		   for ($x=20; $x<$imgWidth-20; $x++){
			   $rgbA = imagecolorat($imgA,$x,$y);
			   $rgbB = imagecolorat($imgB,$x,$y);
			   if(abs($rgbA-$rgbB)>1800000){
				   $t++;
				   $r+=$x;
			   }
		   }
		}
		return round($r/$t)-55;
	}
	private function get_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0){
		if($this->loginapi)return $this->get_curl_proxy($url,$post,$referer,$cookie,$header,$ua,$nobaody);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$httpheader[] = "Accept:application/json";
		$httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
		$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
		$httpheader[] = "Connection:close";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		if($post){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		if($header){
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
		}
		if($cookie){
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		}
		if($referer){
			curl_setopt($ch, CURLOPT_REFERER, "http://ptlogin2.qq.com/");
		}
		if($ua){
			curl_setopt($ch, CURLOPT_USERAGENT,$ua);
		}else{
			curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36');
		}
		if($nobaody){
			curl_setopt($ch, CURLOPT_NOBODY,1);

		}
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
	private function get_curl_proxy($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0){
		$data = array('url'=>$url, 'post'=>$post, 'referer'=>$referer, 'cookie'=>$cookie, 'header'=>$header, 'ua'=>$ua, 'nobaody'=>$nobaody);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->loginapi);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
}