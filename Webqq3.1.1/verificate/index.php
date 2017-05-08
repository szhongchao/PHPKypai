<?php 
 
/**
 * 生成验证码
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

session_start();

$x		= 60;			//验证码的图片宽度
$y		= 38;			//验证码的图片高度
$n		= 4;			//验证码的长度
$d		= 0;			//验证码的角度
$px		= 12;			//字体横坐标
$py		= 12;			//字体纵坐标
$de		= 50;			//干扰程度
$t		= 1;			//验证码类型： 0为随机字母加数字， 1为随机字母， 2为随机数字
$c		= 0;			//字体的大小（为了控制程序的大小，使用内置字体，所以此设置无效）
$tc		= "#000";		//验证码字体的十六进制颜色，不含字符"#"
$bc		= "#fafafa";	//验证码背景的十六进制颜色，不含字符"#"

$tc_arr = color_zh($tc);
$bc_arr = color_zh($bc);

/**
 * 创建一张图片
 */
$img = imagecreate($x, $y);

/**
 * 定义图片背景颜色和字体颜色
 */
$img_col = imagecolorallocate($img, $bc_arr["r"], $bc_arr["g"], $bc_arr["b"]);
$txt_col = imagecolorallocate($img, $tc_arr["r"], $tc_arr["g"], $tc_arr["b"]);

$case_q		= range('a', 'k');
$case_z		= range('m', 'n');
$case_h		= range('p', 'z');
$capital_q	= range('A', 'N');
$capital_h	= range('P', 'Z');
$num		= range(2, 9);

if($t == 0) {
	$chars		= array_merge($case_q, $case_z, $case_h, $capital_q, $capital_h, $num);
} elseif($t == 1) {
	$chars		= array_merge($case_q, $case_z, $case_h, $capital_q, $capital_h);
} elseif($t == 2) {
	$chars		= array_merge(range(0, 9));
}

shuffle($chars);
$char_keys	= array_rand($chars, $n);
shuffle($char_keys);

$yzm = '';
foreach($char_keys as $key) {
	$yzm.=$chars[$key];
}

/**
 * 把验证码字符经过加密后存入session
 */
$_SESSION['yzm'] = md5(strtolower($yzm) . "itpkverificate");

/**
 * 把验证码写入图片中
 */
imagestring($img, 5, $px, $py, $yzm, $txt_col);

/**
 * 给验证码加干扰阅读的小点
 */
for($k = 0; $k < $de; $k++) {
	imagesetpixel($img, rand(0, $x) , rand(0, $y) , imagecolorallocate($img, mt_rand(100,255), mt_rand(0,250), mt_rand(0,200)));
}

header("Content-Type: image/png");
imagepng ($img);
imagedestroy ($img);

exit;

/**
 * 把十六进制的颜色转换成RGB格式的颜色代码
 * @param string $hexColor
 * @return multitype:number
 */
function color_zh($hexColor) {
	$color = str_replace('#', '', $hexColor);
	if (strlen($color) > 3) {
		$rgb = array(
		'r' => hexdec(substr($color, 0, 2)),
		'g' => hexdec(substr($color, 2, 2)),
		'b' => hexdec(substr($color, 4, 2))
		);
	} else {
		$color = str_replace('#', '', $hexColor);
		$r = substr($color, 0, 1) . substr($color, 0, 1);
		$g = substr($color, 1, 1) . substr($color, 1, 1);
		$b = substr($color, 2, 1) . substr($color, 2, 1);
		$rgb = array(
		'r' => hexdec($r),
		'g' => hexdec($g),
		'b' => hexdec($b)
		);
	}
	return $rgb;	
}

?>