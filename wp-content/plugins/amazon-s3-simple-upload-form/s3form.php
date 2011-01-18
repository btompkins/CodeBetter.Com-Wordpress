<div class="wrap">
	
    <?php    echo "<h2>" . __( 'Video Upload Form' ) . "</h2>"; 
	
    	$s3key = get_option('s3key'); 
	$s3secret = get_option('s3key_secret');
	$s3bucket = get_option('s3bucketID');   
	
	//include the S3 class
			if (!class_exists('S3'))require_once('S3.php');
		
			
			//instantiate the class
			$s3 = new S3($s3key, $s3secret);
			
			//check whether a form was submitted
			if(isset($_POST['Submit'])){
			
				//retreive post variables
				$fileName = $_FILES['theFile']['name'];
				$fileTempName = $_FILES['theFile']['tmp_name'];
				
				//create a new bucket
				$s3->putBucket("$s3bucket", S3::ACL_PUBLIC_READ);
				
				//move the file
				if ($s3->putObjectFile($fileTempName, "$s3bucket", $fileName, S3::ACL_PUBLIC_READ))

                               
{	   
					echo "<p>Thank you, your file was successfully uploaded. <a href='s3contents.php'>Click here</a> to view or download your files.</p>";
					
				}else{
					echo "<strong>Something went wrong while uploading your file... sorry please try again.</strong>";
				}
			}
		?>

<h1>Upload a file</h1>
<p>Please select a file by clicking the 'Browse' button and press 'Upload' to start uploading your file.</p>
   	<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <input name="theFile" type="file" />
      <input name="Submit" type="submit" value="Upload">
	</form>
</div>