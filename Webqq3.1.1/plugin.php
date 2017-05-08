<?php

/**
 * 插件设置页面
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 加载主体文件
 */
require_once 'web-init.php';

/**
 * 如果用户不是登录成功状态则跳转到登录页面
 */
if (LOGIN_STATUS != UserLoginUtil::SUCCESS) header("location:login.php");

/**
 * 如果用户没有权限则给出提示
 */
RoleUtil::findRole($user_role['jurisdiction'], RoleUtil::GOPLUGIN, "抱歉,您没有权限访问");

/**
 * 设置网页标题
 */
$_set_html_title = "插件中心";

/**
 * 需要加载的CSS文件
 */
$_link_file_array = array();

/**
 * 需要加载的JavaScript文件
 */
$_script_file_array = array();

/**
 * 实例化插件操作类
 */
$pluginPer = new WebpluginPer();

/**
 * 获取所有的插件
 */
$pluginArray = $pluginPer->getMeAll();

/**
 * 保存安装过的插件类名
 */
$classNames = array();

if ($pluginArray) {
	foreach ($pluginArray as $plugin) {
		array_push($classNames, $plugin['class_name']);
	}
}

/**
 * 从ITPK获取最新插件列表
 */
$do = DataUtil::param_mysql_filter("do", "self");
if ($do == "all") {				//从ITPK获取最新插件列表
	$pageno = DataUtil::param_mysql_filter("pageno", 1);
	$result = file_get_contents("http://addon.itpk.cn/iqplugin.php?do=iq&type=plugin&pageno=" . $pageno);
	$pluginJson = json_decode($result, true);
	$itpkPluginArray = $pluginJson['result'];
} elseif ($do == "detail") {	//从ITPK获取插件的最新信息
	$id = DataUtil::param_mysql_filter("id");
	$name = DataUtil::param_mysql_filter("name");
	if (DataUtil::is_empty($id)) {
		$result = file_get_contents("http://addon.itpk.cn/iqfind.php?do=iq&type=plugin&name=" . $name);
		$itpkPlugin = json_decode($result, true);
	} else {
		$itpkPlugin = $pluginPer->getMeById($id);
	}
} elseif ($do == "install") {	//从ITPK获取远程插件并安装（插件存放目录：程序安装根目录下的plugin目录）
	$installResult = "";
	$name = DataUtil::param_mysql_filter("name");
	$plugin_zip = PLUGIN_FOLDER . "{$name}.zip";		//插件地址
	$plugin_folder = PLUGIN_FOLDER . DataUtil::getPluginFolder($name);
	if (is_writable(PLUGIN_FOLDER)) {
		if (file_exists($plugin_zip)) {
			$installResult = "安装失败，本地已存在此插件安装包";
		} elseif (is_dir($plugin_folder)) {
			$installResult = "安装失败，本地已存在此插件文件夹";
		} else {
			//下载插件压缩包
			$file = new HttpDownload();
			$openResult = @$file->OpenUrl("http://plugin.itpk.cn/iq/{$name}.zip");
			$downloadResult = $openResult ? @$file->SaveToBin( $plugin_zip ) : false;
			$file->Close();
			if (!$openResult || DataUtil::is_empty($openResult)) {
				$installResult = "插件不存在";
			} elseif ($downloadResult && file_exists($plugin_zip)) {
				$zip = new ZipArchive();
				$res = $zip->open( $plugin_zip );
				if ($res === true) {
					if (file_exists($plugin_folder)) DataUtil::delFolder($plugin_folder);
					//解压插件压缩包
					$zip->extractTo( $plugin_folder );
					$zip->close();
					if ($pluginPer->installSql(@file_get_contents($plugin_folder . "/web.sql"))) {
						//删除插件残留文件
						unlink($plugin_zip);
						unlink($plugin_folder . "/web.sql");
						$installResult = "安装成功";
					} else {
						$installResult = "安装失败";
					}
				} else {
					$installResult = "插件下载失败，请检查插件目录是否有写入权限";
				}
			} else {
				$installResult = "插件不存在或下载失败";
			}
		}
	} else {
		$installResult = "请检查插件目录是否有写入权限";
	}
} elseif ($do == "delete") {
	$id = DataUtil::param_mysql_filter("id");
	$plugin = $pluginPer->getMeById($id);
	if ($plugin) {
		$className = $plugin['class_name'];
		if (!DataUtil::is_empty($className)) {
			$plugin_folder = PLUGIN_FOLDER . DataUtil::getPluginFolder($className);
			$folderDelResult = false;
			$dbDelResult = false;
			if (file_exists($plugin_folder)) {
				if ($pluginPer->uninstallSql(@file_get_contents($plugin_folder . "/unweb.sql"))) {
					$folderDelResult = @DataUtil::delFolder($plugin_folder);
				}
			}
			if ($folderDelResult) {
				$dbDelResult = $pluginPer->deleteMe($plugin['id']);
			}
		}
	}
} elseif ($do == "zip") {
	$m = DataUtil::param_mysql_filter("m", "list");
	$name = DataUtil::param_mysql_filter("name");
	if ($m == "list") {
		$files = DataUtil::getFolderFiles(PLUGIN_FOLDER, ".zip");
	} elseif ($m == "delete") {
		$deleteResult = unlink(PLUGIN_FOLDER . $name);
	} elseif ($m == "install") {
		$plugin_zip = PLUGIN_FOLDER . $name;
		$name = explode(".zip", $name);
		$name = $name[0];
		$plugin_folder = PLUGIN_FOLDER . DataUtil::getPluginFolder($name);
		if (file_exists($plugin_zip)) {
			$zip = new ZipArchive();
			$res = $zip->open( $plugin_zip );
			if ($res === TRUE) {
				if (file_exists($plugin_folder)) DataUtil::delFolder($plugin_folder);
				//解压插件压缩包
				$zip->extractTo( $plugin_folder );
				$zip->close();
				if ($pluginPer->installSql(@file_get_contents($plugin_folder . "/web.sql"))) {
					//删除插件残留文件
					unlink($plugin_zip);
					unlink($plugin_folder . "/web.sql");
					$installResult = "安装成功";
				} else {
					$installResult = "安装失败";
				}
			}
		} else {
			$installResult = "压缩包不存在";
		}
	}
} elseif ($do == "folder") {
	$m = DataUtil::param_mysql_filter("m", "list");
	$name = DataUtil::param_mysql_filter("name");
	if ($m == "list") {
		$files = DataUtil::getFolders(PLUGIN_FOLDER, $classNames);
	} elseif ($m == "delete") {
		$deleteResult = @DataUtil::delFolder(PLUGIN_FOLDER . $name);
	} elseif ($m == "install") {
		$plugin_folder = PLUGIN_FOLDER . $name;
		if ($pluginPer->installSql(@file_get_contents($plugin_folder . "/web.sql"))) {
			//删除插件sql文本
			if (@unlink($plugin_folder . "/web.sql")) {
				$installResult = "安装成功";
			} else {
				$installResult = "安装成功,但插件残留文件无权限删除";
			}
		} else {
			$installResult = "安装失败";
		}
	}
} elseif ($do == "set") {
	$id = DataUtil::param_mysql_filter("id");
	if (DataUtil::is_exits("m") &&  DataUtil::param_mysql_filter("m") == "save") {
		$instruction = DataUtil::param_mysql_filter("instruction");
		$is_able = DataUtil::param_mysql_filter("is_able", false, true);
		$updateResult = $pluginPer->updateMe($id, $instruction, $is_able);
	}
	$plugin = $pluginPer->getMeById($id);
}

/**
 * 加载公共头部文件
 */
require_once DOCUMENTS_FOLDER . "header.inc";

/**
 * 导入首页模板文件
 */
require_once MANAGER_FOLDER . "plugin.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>