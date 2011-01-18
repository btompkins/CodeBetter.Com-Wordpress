<div class="wrap">
	
     <?php    echo "<h2>" . __( 'Amazon S3 Video Upload Settings' ) . "</h2>";?>
       
    <form name="s3settings" method="post" action="options.php">
    
        <?php settings_fields( 's3upload_settings' ); ?>
    
    	<p>S3 Access Key:<input type="text" size="40" name="s3key" value="<?php echo get_option('s3key'); ?>" /></p>
        <p>S3 Secret Key:<input type="password" size="40" name="s3key_secret" value="<?php echo get_option('s3key_secret'); ?>" /><br /><a href="http://aws-portal.amazon.com/gp/aws/developer/account/index.html/?ie=UTF8&action=access-key">Login to AWS to retrieve your secret key >></a></p>
        <p>S3 Bucket ID :<input type="text" size="40" name="s3bucketID" value="<?php echo get_option('s3bucketID'); ?>" /></p>
        
        <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="s3key,s3key_secret,s3bucketID" />
			
			<p><input type="submit" name="Submit" value='<?php esc_attr_e('Submit Info') ?>'/></p>
    
        
     </form> 
     
     <br />
     
     <p><span style="font-weight:bold;">AMAZON S3 FAQs</span> : <a href="http://aws.amazon.com/s3/faqs/#How_can_I_get_started_using_Amazon_S3">Click Here</a></p> 
     
      <p class="alignleft" style="width:300px; text-align:center; border:1px solid #404040; padding:2px">If you enjoy using this plugin, please consider making a monetary donation.  It will help with upkeep and improvements to the plugin!  </p><br /> <p class="alignright"> <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="10034264">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></p>
	
</div>