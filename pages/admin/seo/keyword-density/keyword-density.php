<?
	$p->title = 'Keyword Density Check'; 
	$p->template('crave','top');
?>
<div><input type="text" id="word-search" style="width:200px;" /> <input type="button" id="search" value="Search"></div>
<div style="margin-bottom:10px;"><textarea id="density-area" style="width:600px; height:300px"></textarea></div>
<div>Total Words (<span id="total-words">0</span>)</div>
<div id="density"></div>
<?
	$p->template('crave','bottom')
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
					word = value.toLowerCase().replace("!","").replace("?","").replace(".","").replace(";","").replace(":","").replace(";","").replace(",","")
					newtext = newtext+' '+word
				})
				var count = newtext.split(word).length - 1;
				density = (count / numWords) * 100
				percent = density.toFixed(2)
				
				$('#density').html("<strong>"+word+"</strong> was found "+count+" times.<br> It has a density of "+percent+"%")
			}
			else $('#density').html('')
		})
	})
</script>