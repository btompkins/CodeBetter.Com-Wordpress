<div class="wrap">
	
    <?php    echo "<h2>" . __( 'S3 Bucket Contents' ) . "</h2>";?>
    
            
            <table class='widefat'>
				<thead>
					<tr>
						<th>Filename</th>
						<th>Size</th>
						<th>URL</th>
						<th>Download</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Filename</th>
						<th>Size</th>
						<th>URL</th>
						<th>Download</th>
					</tr>
				</tfoot>
				<tbody>
            
            <?php 
        $s3key = get_option('s3key'); 
        $s3secret = get_option('s3key_secret');
        $s3bucket = get_option('s3bucketID');   
        
        //include the S3 class
                if (!class_exists('S3'))require_once('S3.php');
            
                
                //instantiate the class
                $s3 = new S3($s3key, $s3secret);
    
        // Get the contents of our bucket
        $contents = $s3->getBucket("$s3bucket");
        
        foreach ($contents as $file){
        
            $fname = $file['name'];
            $fsize = $file['size'];
            $furl = "http://".$s3bucket.".s3.amazonaws.com/".$fname;
            
            //output a link to the file
            echo "<tr>
                    <td>$fname</td>
                    <td>$fsize</td>
                    <td><span style='font-weight:bold;'>$furl</span></td>
                    <td><a href=\"$furl\">(Download File)</a></td>
                  </tr>";
        }
    ?><?php endforeach ?>
                
	<?php 
        $s3key = get_option('s3key'); 
        $s3secret = get_option('s3key_secret');
        $s3bucket = get_option('s3bucketID');   
        
        //include the S3 class
                if (!class_exists('S3'))require_once('S3.php');
            
                
                //instantiate the class
                $s3 = new S3($s3key, $s3secret);
    
        // Get the contents of our bucket
        $contents = $s3->getBucket("$s3bucket");
        
        foreach ($contents as $file){
        
            $fname = $file['name'];
            $fsize = $file['size'];
            $furl = "http://".$s3bucket.".s3.amazonaws.com/".$fname;
            
            //output a link to the file
            echo "<tr>
                    <td>$fname</td>
                    <td>$fsize</td>
                    <td><span style='font-weight:bold;'>$furl</span></td>
                    <td><a href=\"$furl\">(Download File)</a></td>
                  </tr>";
        }
    ?><?php endforeach ?>

        </tbody>
                  </table>

</div>