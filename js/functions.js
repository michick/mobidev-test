function alertSucceed(obj, delay_time){
	delay_time = delay_time || 1000;
	if(delay_time < 1000) delay_time = 1000;
	var diff_top = 3;
	
	var top = obj.offsetTop + diff_top;
	var left = obj.offsetLeft + 72;
	var div = '<div id="alertSucc" style="z-index: 30000; position: absolute; left:'+left+'px; top:'+top+'px">';	
	div+= '<span style="color:green;"><strong>+</strong></span>';
	div+='</div>';
	$('body').append(div);
	setTimeout(function(){$('#alertSucc').remove()}, delay_time);
}

function modifyLikes(obj, obj_type, owner, reponame){
	obj.disabled = true;
	reponame = reponame || '';
	var button_text = obj.innerHTML;
	obj.innerHTML = '<img src="img/loader.gif" />'
	var task = 'add_like';
	if(button_text == 'UnLike') task = 'del_like';
	var input_data = {'action':'modify_like', 'task':task, 'obj_type':obj_type, 'owner':owner, 'reponame':reponame};
	$.post('action.ajax.php', input_data, function(data){
		if(data.result){
			if(data.message == 'like_added') obj.innerHTML = 'UnLike';
			else if (data.message == 'like_deleted') obj.innerHTML = 'Like';
			
		} else {
				alert('Извините, произошла ошибка. Изменения не были применены.');
				obj.innerHTML = button_text;
		}
		obj.disabled = false;
		//if(data.result) alertSucceed(obj, 2000);
	}, 'json');
	
}

function getMoreResults(obj, query, start_page){
	obj.disabled = true;
	var button_text = obj.innerHTML;
	obj.innerHTML = '<img src="img/loader.gif" />';
	var input_data = {'action':'get_more_search_results', 'query':query, 'start_page':start_page};
	$.post('action.ajax.php', input_data, function(data){
		if(data.result){
			$("#search_results").append(data.html);
			if(data.next_page == 'none'){
				//delete obj;
				obj.parentNode.removeChild(obj);
				$("#search_results").append("<p>Это все результаты</p>");
			}else{
				obj.innerHTML = button_text;
				obj.onclick = function(){ getMoreResults(obj, query, data.next_page);};
				obj.disabled = false;
			}
			
			
		} else {
			obj.innerHTML = button_text;
			alert('Извините, произошла ошибка.');
			obj.disabled = false;
		}
	}, 'json');
}
