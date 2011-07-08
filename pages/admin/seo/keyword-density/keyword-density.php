<?
	$p->title = 'Keyword Density Check'; 
	$p->template('crave','top');
?>
<div><input type="text" id="word-search" style="width:200px;" /> <input type="button" id="search" value="Search"></div>
<div><textarea id="density-area" style="width:800px; height:600px"></textarea></div>
<div>Total Words (<span id="total-words">0</span>)</div>
<div id="density"></div>
<?
	$p->template('crave','bottom')
?>
<script language="javascript">
	$(function() {
		$('#density-area').live('keyup click focusout focusin change', function() {
			word = $('#word-search').val()
			search_count = 0;
			text = jQuery.trim($(this).val()).replace(/\s+/g," ")
			if($(this).val() == '') {
				numWords = 0
			}
			else numWords = text.split(' ').length;
			$('#total-words').html(numWords)
			if (numWords > 0) {
				words = text.split(' ')
				$.each(words,function(index,value) {
					if (toLowerCase(value) == toLowerCase(word)) search_count++
				})
				density = (search_count / numWords) * 100
				percent = density.toFixed(2)
				
				$('#density').html("'"+word+"' was found "+search_count+" times. It has a density of "+percent+"%")
			}
			else $('#density').html('')
		})
	})
</script>