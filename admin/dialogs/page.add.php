<script language="text/javascript">
$(function(){		
			var options = {target: '#message', before: addStart, after: addDone};
        
			$('#add').ajaxForm(options);
				return false; 
				});
			
			function addStart(){
				$("#message").html('<img src="imgs/loading_small.gif" alt="loading" /> Creating page...');
				$("#message").fadeIn(500);
				TB_remove();
				}
			
			function addDone(){}
</script>
<div id="dialog">
	<div class="center">
		<form id="add" method="post" action="handlers/page.add.php" autocomplete="off">
				<label>Create a short title for your page, then click Add</label>
				<input type="text" name="shortTitle" /><input type="submit" value="Add" />		
		</form>
	</div>
</div>		
