<?php
if (!defined('ABSPATH')) {exit;}
/**
 * Cross Origin Resource Sharing (CORS)
 *
 * @link       https://themeforest.net/user/nouthemes/portfolio
 * @since      1.0.4
 *
 * @package    Leopard_Wordpress_Offload_Media
 * @subpackage Leopard_Wordpress_Offload_Media/includes
 * @author     Nouthemes <nguyenvanqui89@gmail.com>
 */

class Leopard_Wordpress_Offload_Media_CDN {

	/**
     * @var string
     */

    protected $client = null;
    protected $region = null;
    protected $bucket = null;


    public function __construct() {

        $Bucket_Selected = get_option('nou_leopard_offload_media_connection_bucket_selected_select');

		$this->client = leopard_offload_media_provider();
		
		if($this->client::identifier() == 'google'){
			$this->bucket = $Bucket_Selected;
		}else{

			$Array_Bucket_Selected = explode( "_nou_wc_as3s_separator_", $Bucket_Selected );

	        if ( count( $Array_Bucket_Selected ) == 2 ){
	            $this->bucket = $Array_Bucket_Selected[0];
	            $this->region = $Array_Bucket_Selected[1];
	        }
	        else{
	            $this->bucket = 'none';
	            $this->region = 'none';
	        }

		}

    }

    public function putHostingContent() {
    	return $this->client->putHostingContent($this->region, $this->bucket);
    }

    public function putBucketPolicy() {
		return $this->client->putBucketPolicy($this->region, $this->bucket);
    }
}
