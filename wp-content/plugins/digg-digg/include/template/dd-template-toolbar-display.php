<?php 
//toolbar display function
function dd_button_toolbar_setup(){
	
  	// display admin screen
  	dd_print_toolbar_form();
}

function dd_print_toolbar_form(){
?>

<div id=msg></div>

<div id="dd_admin_block">

	<div id="dd_head_block">
		<span id="dd_plugin_title">Digg Digg - Toolbar Configuration</span>
	</div>

<!-- start of dd_admin_left_block -->
<div id="dd_admin_left_block">
	
	<div class="dd-block">
		<div class="dd-title"><h2>Toolbar (Under Construction...)</h2></div>
		<div class="dd-insider">
			<p>
			DiggDigg toolbar is inspired by <a href="http://www.wikihow.com/" target="_blank">wikihow</a> 
			and <a href="http://www.wibiya.com/" target="_blank">wibiya</a>
			</p>
			<h2>
			Work in progress......
			</h2>
			<i>
			Please <a href="http://www.mkyong.com/contact-mkyong/" target="_blank">send me</a> your idea :).
			</i>
		</div>	
	</div>
	
</div>
<!-- end of dd_admin_left_block -->

<!-- start of dd-sidebar.php -->
<?php include("dd-sidebar.php"); ?>
<!-- end of dd-sidebar.php -->

</div>
<?php 
}
?>