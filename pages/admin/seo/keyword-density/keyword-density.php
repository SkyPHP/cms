<?
	$p->title = 'Keyword Density Check'; 
	$p->template('skybox','top');
?>
<div style="margin-bottom:5px"><input type="text" id="word-search" style="width:200px;" /> <input type="button" id="search" value="Search"></div>
<div style="margin-bottom:5px"><textarea id="density-area" style="width:500px; height:150px"></textarea></div>
<div style="margin-bottom:5px; font-size:16px;">Total Words (<span id="total-words">0</span>)</div>
<div style="font-size:14px;" id="density"></div>
<?
	$p->template('skybox','bottom')
?>
<script language="javascript">
	$(function() {
		$('#density-area').live('keyup click focusout focusin change', function() {
			text = jQuery.trim($(this).val()).replace(/\s+/g," ")
			if($(this).val() == '') {
				numWords = 0
			}
			else numWords = text.split(' ').length;
			$('#total-words').html(numWords)
		})
		
		$('#search').live('click',function() {
			word = $('#word-search').val().toLowerCase()
			text = jQuery.trim($('#density-area').val()).replace(/\s+/g," ")
			if($('#density-area').val() == '') {
				numWords = 0
			}
			else numWords = text.split(' ').length;
			if (numWords > 0) {
				words = text.split(' ')
				// remove the unwanted characters
				newtext=''				
				$.each(words,function(index,value) {
					rep_word = value.toLowerCase().replace("!","").replace("?","").replace(".","").replace(";","").replace(":","").replace(";","").replace(",","")
					newtext = newtext+' '+rep_word
				})
				var count = newtext.split(word).length - 1;
				density = (count / numWords) * 100
				percent = density.toFixed(2)
				
				$('#density').html("<strong style='padding:3px 2px; border:1px #666 solid'>"+word+"</strong> was found "+count+" times.<br> It has a density of "+percent+"%")
			}
			else $('#density').html('')
		})
	})
</script>