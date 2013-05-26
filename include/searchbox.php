<?php 
	/* hacked to maintain the same height of input fields... */
	if($_SERVER['PHP_SELF'] == OV_ROOT.'/index.php')
		$inputClass = 'searchInput2';
	else
		$inputClass = 'searchInput';
?>
<div class="searchbar">
	<div class="indexSearchField">
	<form action="search.php" method="get" onsubmit="return (this.keyword.value.length > 2)? true:false;">
		<input id="searchinput" name="name" type="text" class="<?php echo $inputClass; ?>" onFocus="this.value='';"/>
		<input type="hidden" name="what" value="friends" /> 
		<input type="submit" class="searchbutton" value="" />
	</form>
	</div>
</div>
