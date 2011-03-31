
function save_tags(theform)
{
	 
	/*if(!check_form_blog())
	{
		
		document.getElementById('tag_progres').innerHTML = '<font color="Red">Please Select a Blog.</font>';
		return false;
	}*/
	  
	 
	 
	 
	 document.getElementById('tag_progres').innerHTML = 'saving...';
	 theform.action = '/admin/blog/article/tag/';
	theform.method = 'post';
	AjaxRequest.submit(theform,{
		'onSuccess' : function(req){
			document.getElementById('tag_progres').innerHTML = '';
			document.getElementById('name').value = '';  
			document.getElementById('name').focus();
			if(req.responseText=='1')
			{
				document.getElementById('tag_progres').innerHTML = '<font color="Red">Please Choose a Different name</font>';
				
			}else if(req.responseText=='0')
			{
				
			}else {
					document.getElementById('alltag').innerHTML = req.responseText;
			}
			
		}
	});	
	
	
}


//this function will save articlefunction save_tags(theform)
function save_article(theform)
{
	tinyMCE.triggerSave();
	document.getElementById('article_progres').innerHTML = 'saving...';
	theform.action = '/admin/blog/article/save/';
	theform.method = 'post';
	AjaxRequest.submit(theform,{
		'onSuccess' : function(req){
			document.getElementById('article_progres').innerHTML = '';
			document.getElementById('article_progres').innerHTML = req.responseText;
		}
	});	
	
	
}

function blog_author(blog_id,person_ide) {
	document.getElementById('blog_authors').innerHTML = '<div align="center"><img src="/images/loading.gif"></div>';
	$.ajax({
                'type':'POST',
		'url' : '/admin/blog/article/authors/?blog_id='+blog_id+'&person_ide='+person_ide,
		'success':function(req){
			document.getElementById('blog_authors').innerHTML = req.responseText;
		}
	});
}

//This function will display show all category
function show_blog_category(theform, id, article_cat_id)
{
	
	getKeyword(id);
	if(document.getElementById('blog_hidden').value !='')
	{
		 
		var hidme =document.getElementById('blog_hidden').value;
		document.getElementById('blog_category_'+hidme).innerHTML = '';
		 
		document.getElementById('blog_hidden').value =id;
		
	}
	
	
	
	
	if(document.getElementById('blog_hidden').value =='')
	{
		document.getElementById('blog_hidden').value =id;
		 
	}
	
	document.getElementById('blog_category_'+id).innerHTML = 'loading...';
	theform.action = '/admin/blog/article/blog_category/';
	url = '/admin/blog/article/blog_category/';
	//theform.method = 'post';
	$.ajax({
        'type':'GET',
	'url':url,
	'parameters':{ 'blog_id':id, 'blog_article_cat_id':article_cat_id },
	'success' : function(req){
		document.getElementById('blog_category_'+id).innerHTML = '';
		document.getElementById('blog_category_'+id).innerHTML = req.responseText;
		
	}
	
	});
	/*AjaxRequest.submit(theform,{
	'onSuccess' : function(req){
		document.getElementById('blog_category_'+ide).innerHTML = '';
		document.getElementById('blog_category_'+ide).innerHTML = req.responseText;
	}
	});	*/
	
}


function prev_article(theform)
{	
	 theform.target = '_blank';	 
	 theform.action = '/admin/blog/article/preview/';
	 theform.submit();
}

function delete_article(theform)
{
	
	if(confirm ("Are you sure want to delete?"))
	{
		  
	 theform.action = '/admin/blog/article/delete/';
	 theform.submit();
	}
	
	
	
}
 

function check_form_blog()
{
	var elemAll	=	document.postarticles.elements.length;
	for(i=0; i<elemAll; i++){
		if(document.postarticles.elements[i].type.toUpperCase()=='RADIO' && eval("document.postarticles.elements[i].name.match(/blog_id/)")){
				
				var radioCheck = 'false';
				var elemName	=	document.postarticles.elements[i].name;
				var elem = eval("document.postarticles."+elemName);
				
				//alert("L = "+elem.length);
				var divId	=	elemName.split('_');
				var divIdData	=	parseInt(divId[1]);
				for(j=0; j<elem.length; j++){//alert(elem[j].checked);
					if(elem[j].checked == true){
						//alert("Is checked : "+document.postarticles.elements[i].name);
						radioCheck = 'true';
					}else{
						//radioCheck='false'
						//alert("Is Not checked : "+document.postarticles.elements[i].name);
					}
				}
				
			
				
				
				//alert(styleClassName);break;
				if(radioCheck=='false'){
					 return false;
				}else{
					
					 return true;
				
				}
			} 
	}
}

 

function getKeyword(value)
{
	
	  url = '/admin/blog/article/keywords/';
	//theform.method = 'post';
	$.ajax({
                'type':'GET',
		'url':url,
		'parameters':{ 'blog_id':value  },
		'success' : function(req){
			
			document.getElementById('keywords1').innerHTML = '';
			document.getElementById('blog_keywords').value = req.responseText;
			document.getElementById('keywords1').innerHTML = req.responseText;
			
		}
		
	}); 
	
	
}

function save_keyword(theform)
{
	  
	document.getElementById('keyword_progres').innerHTML = 'loading...';
	theform.action = '/admin/blog/article/keywords/save/';
	theform.method = 'post';
	AjaxRequest.submit(theform,{
		'onSuccess' : function(req){
			document.getElementById('keyword').value = '';  
			document.getElementById('keyword').focus();
			document.getElementById('keyword_progres').innerHTML = '';
			document.getElementById('keywords1').innerHTML = req.responseText;
		}
	});	
}

function imagePicker(date,offset) {
	document.getElementById('image-picker').innerHTML = '<div align="center"><img src="/images/loading.gif"></div>';
	$.ajax({
                'type':'POST',
		'url' : '/admin/blog/article/images/image-picker-nav?date='+date,
		'success':function(req){
			document.getElementById('image-picker-nav').innerHTML = req.responseText;
		}
	});	
	$.ajax({
                'type':'POST',
		'url' : '/admin/blog/article/images/image-picker/'+offset+'?date='+date,
		'success':function(req){
			document.getElementById('image-picker').innerHTML = req.responseText;
			document.getElementById('divFileProgressContainer_').innerHTML = '';
		}
	});
}

function imageInsert(src,w,h,media_item_ide) {
	if (src=='alert') alert('Image must be at least 250px wide.');
	else {
		tinyMCE.execCommand( 'mceInsertContent', false, '<img class="article_image" src="'+src+'" height="'+h+'" width="'+w+'" alt="">' );
//		alert(src+' w='+w+' h='+h);
//		alert(media_item_ide);
		document.getElementById('media_item_ide').value = media_item_ide;
	}//if
}//function

function deletetag(value, article_id)
{
	document.getElementById('tag_progres').innerHTML = 'deleting...';
	 url = '/admin/blog/article/tag/delete';
	//theform.method = 'post';
	$.ajax({
                'type':'GET',
		'url':url,
		'parameters':{ 'blog_tag_id':value, 'mode':'edit', 'blog_article_id':article_id},
		'success' : function(req){
			document.getElementById('tag_progres').innerHTML = '';
			document.getElementById('alltag').innerHTML = req.responseText;
			
		}
		
	});

}

function swap( F, R ){
	var vD = F.defaultValue;
	if ( F.value == ( R ? '' : vD ) ) F.value = ( R ? vD : '' );
}
