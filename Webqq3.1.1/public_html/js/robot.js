function checkedAll(cbx) {
	var checkeds = document.getElementsByName("checked_id[]");
	if (checkeds.length > 0) {
		for (var i = 0; i < checkeds.length; i++) {
			if(checkeds[i].type == 'checkbox') { 
				checkeds[i].checked = cbx.checked;
			}
		}
	}
}

function checkedOff(cbx) {
	if (!cbx.checked) {
		document.getElementById("checkedAll").checked = false;
	}
}

function robotSetting(type) {
	var checkeds = document.getElementsByName("checked_id[]");
	if (checkeds.length <= 0) {
		alert("您还没有添加机器人");
		return false;
	}
	var count = 0;
	var id_array = "";
	for (var i = 0; i < checkeds.length; i++) {
		if(checkeds[i].type == 'checkbox' && checkeds[i].checked) { 
			count++;
			id_array += (id_array == "" ? checkeds[i].value : ("," + checkeds[i].value));
		}
	}
	if (count <= 0) {
		alert("请选择机器人再进行批操作");
		return false;
	}
	document.getElementById("type").value = type;
	document.getElementById("id_array").value = id_array;
	document.getElementById("robotSettingForm").submit();
}