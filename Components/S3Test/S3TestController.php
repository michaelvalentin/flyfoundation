<?php
namespace Flyf\Components\S3Test;

class S3TestController extends \Flyf\Components\Abstracts\AbstractController{
	/* (non-PHPdoc)
	 * @see Flyf\Components\Abstracts.AbstractController::collectData()
	 */protected function collectData() {
		// TODO Auto-generated method stub
		}

	/* (non-PHPdoc)
	 * @see Flyf\Components\Abstracts.AbstractController::prepare()
	 */protected function prepare() {
		// TODO Auto-generated method stub
		}

	/* (non-PHPdoc)
	 * @see Flyf\Components\Abstracts.AbstractController::selectTemplate()
	 */protected function selectTemplate() {
		// TODO Auto-generated method stub
		}

		public function Render() {
			$output = "";
			require_once('Flyf/External/aws/sdk.class.php');
			$s3 = new \AmazonS3();
			$bucket = strtolower($s3->key);
			$response = $s3->create_bucket($bucket,\AmazonS3::REGION_IRELAND,\AmazonS3::ACL_PUBLIC);
			$output .= $response->isOK() ? "SUCCES CREATING BUCKET" : "FAILURE CREATING BUCKET";
			$output .= "\n";
			echo '<pre>';
			print_r($response);
			echo '</pre>';
			$filename = "testfolder/test.txt";
			$file = __DIR__.DS."test.txt";
			$response = $s3->create_object($bucket,$filename,array(
					"fileUpload" => $file,
					"acl" => \AmazonS3::ACL_PUBLIC,
					
			));
			echo '<pre>';
			print_r($response);
			echo '</pre>';
			$output .= $response->isOK() ? "SUCCES CREATING OBJECT" : "FAILURE CREATING OBJECT";
			$output .= "\n";
			$output .= "##".strlen($s3->get_object_url($bucket, $filename))."##";
			$cloud = new \AmazonCloudFront();
			return $output;
		}
}

?>