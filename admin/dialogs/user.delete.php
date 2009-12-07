<div id="dialog">
	<div class="warning">
		Are you sure you want to delete this user?
	</div>

	<div class="center">
		<span class="ok">
			<a href="#" onClick="javascript:deleteConfirm('<? echo $_GET['userId']; ?>');">Delete</a>
		</span>
		<span class="cancel">
			<a href="#" onClick="TB_remove();">Cancel</a>
		</span>
	</div>
</div>