<?
	$p->title = 'Keyword Density Check'; 
	$p->template('intranet','top');

	for ($x=1; $x<=5; $x++) {
?>
	<div style="margin:20px 0;">
		<div style="margin-bottom:5px"><input type="text" id="word-search<?=$x?>" style="width:200px;" /> <input type="button" class="search" x="<?=$x?>" value="Search"></div>
		<div style="margin-bottom:5px"><textarea x="<?=$x?>" class="area" id="density-area<?=$x?>" style="width:800px; height:400px"></textarea></div>
		<div style="margin-bottom:5px; font-size:16px;">Total Words (<span id="total-words<?=$x?>">0</span>)</div>
		<div style="font-size:14px;" id="density<?=$x?>"></div>
	</div>
<?	
	if ($x != 5) echo "<hr/>";
	}
	$p->template('intranet','bottom')
?>
<script language="javascript">
	$(function() {
		$('.area').live('keyup click focusout focusin change', function() {
			x = $(this).attr('x')
			text = jQuery.trim($(this).val()).replace(/\s+/g," ")
			if($(this).val() == '') {
				numWords = 0
			}
			else numWords = text.split(' ').length;
			$('#total-words'+x).html(numWords)
		})
		
		$('.search').live('click',function() {
			x = $(this).attr('x')
			word = $('#word-search'+x).val().toLowerCase()
			text = jQuery.trim($('#density-area'+x).val()).replace(/\s+/g," ")
			if($('#density-area'+x).val() == '') {
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
				
				$('#density'+x).html("<strong style='padding:3px 2px; border:1px #666 solid'>"+word+"</strong> was found "+count+" times.<br><br> It has a density of "+percent+"%")
			}
			else $('#density'+x).html('')
		})
	})
</script>