<?php 
//maunal display function
function dd_button_manual_setup(){
	
	global $dd_manual_code;
  	// display admin screen
  	dd_print_manual_form($dd_manual_code);
}

function dd_print_manual_form($dd_manual_code){
?>

<div id="dd_admin_block">

	<div id="dd_head_block">
		<span id="dd_plugin_title">Digg Digg - Manual Placement</span>
	</div>

<!-- start of dd_admin_left_block -->
<div id="dd_admin_left_block">
	
	<div class="dd-block">
		<div class="dd-title">
			<h2>Support Digg Digg</h2>
		</div>
		<div class="dd-insider">
			<p>
			If you like this plugin and find it useful, 
			help keep this plugin free and actively developed by a
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AWXCPTQXB7YAS" target="_blank">donation</a>. 
			</p>
		</div>	
	</div>
	
	<div class="dd-block">
		<div class="dd-title"><h2>Advance Usage</h2></div>
		<div class="dd-insider">
			<p>
			For advance user, you can use the following code to generate a particular button and place it anywhere;
			Or group few buttons with your custom CSS formating layout. Your only limit is your imagination :)  
			</p>
			<h4>Example</h4>
			<p>
				1. Place <code>&lt;?php dd_digg_generate('Normal') ?&gt;</code> in your "single.php",
				it will generate a normal digg button in your single post page. 
			</p>
			<p>
				2. It's always recommended to rewrite above code with function_exists() checking : <br/>
				<code>&lt;?php if(function_exists('dd_digg_generate')){dd_digg_generate('Normal');} ?&gt;</code>
			</p>
			


			<?php 
				
			foreach(array_keys($dd_manual_code) as $key){
				echo "<table class='dd_table_manual dd-table'>";
					
		    	echo "<tr><td colspan='2' ><h3>" . $key . "</h3></td></tr>";
		    	
				foreach($dd_manual_code[$key] as $subkey => $value){
					echo "<tr>";
					echo "<td>" . $subkey . "</td>";
					echo "<td><code>&lt;?php " . $value . " ?&gt; </code></td>";
					echo "</tr>";					
				}
				
				echo "</table>";
		    }
		    

			?>
			
			
		</div>	
	</div>
	
	<!-- start of dd-footer.php -->
	<?php include("dd-footer.php"); ?>
	<!-- end of dd-footer.php -->
	
	
</div>
<!-- end of dd_admin_left_block -->

<!-- start of dd-sidebar.php -->
<?php include("dd-sidebar.php"); ?>
<!-- end of dd-sidebar.php -->

</div>
<?php 
}
?>