<?
	template::inc('global','top');
?>
	<h2>Checkout</h2>
	
	<table>
		<tr>
			<th>Repository</th><th>Local Path</th>
		</tr>
		<tr>
			<td><input name="repo" id="repo" type="text"></td>
			<td><input name="path" id="path" type="text"></td>
		</tr>
		<tr>
			<td colspan="2">
				<input name="submit" onclick="checkout()" value="Checkout" type="button">
			</td>
		</tr>
	</table>
	<div id = "output"></div>
<?
	template::inc('global','bottom');
?>