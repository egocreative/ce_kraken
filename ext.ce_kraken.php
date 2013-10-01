<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CE Kraken Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		Jon Carlisle - Ego Creative
 * @link		http://www.ego-creative.com
 */

class Ce_kraken_ext {

	public $settings 		= array();
	public $description		= 'Image crusher extension for EE when using CE Image and the Kraken.io API';
	public $docs_url		= '';
	public $name			= 'CE Kraken';
	public $settings_exist	= 'y';
	public $version			= '1.0';
	private $EE;

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{

		$this->EE =& get_instance();
		$this->settings = $settings;
		$this->valid_type = array(
			'png'	=> true,
			'gif'	=> true,
			'jpg'	=> true
		);

		 if (empty($this->settings)) {
		 	$temp_settings = $this->EE->db->where('class', 'Ce_kraken_ext')->get('extensions')->row_array();
		 	if ($temp_settings and unserialize($temp_settings['settings'])) {
		 		$this->settings = unserialize($temp_settings['settings']);
		 	}
		 }

		if (isset($this->settings['kraken_api_key']) and isset($this->settings['kraken_api_secret']))
		{
			$this->auth = array(
				"auth" => array(
					"api_key" => @$this->settings['kraken_api_key'],
					"api_secret" => @$this->settings['kraken_api_secret']
				)
			);
		}
		else
		{
			$this->auth = array();
		}

	}

	// ----------------------------------------------------------------------

	/**
	* Settings Form
	*
	* @param   Array   Settings
	* @return  void
	*/
	function settings_form($current)
	{

		ee()->load->helper('form');
		ee()->load->library('table');

		$vars = array();
		$vars['settings'] = array();

		$kraken_apikey = isset($current['kraken_api_key']) ? $current['kraken_api_key'] : '';
		$vars['settings']['kraken_api_key'] = form_input('kraken_api_key', $kraken_apikey);

		$kraken_api_secret = isset($current['kraken_api_secret']) ? $current['kraken_api_secret'] : '';
		$vars['settings']['kraken_api_secret'] = form_input('kraken_api_secret', $kraken_api_secret);

		$path_to_made = isset($current['path_to_made']) ? $current['path_to_made'] : '';
		$vars['settings']['path_to_made'] = form_input('path_to_made', $path_to_made);

		$url_to_made = isset($current['url_to_made']) ? $current['url_to_made'] : '';
		$vars['settings']['url_to_made'] = form_input('url_to_made', $url_to_made);

		if (isset($_GET['result'])) {
			$vars['test_result'] = $_GET['result'];
			$vars['test_result_color'] = $_GET['color'];
		}

		return ee()->load->view('settings', $vars, TRUE);

	}

	// ----------------------------------------------------------------------

	/**
	* Save Settings
	*
	* This function provides a little extra processing and validation
	* than the generic settings form.
	*
	* @return void
	*/
	function save_settings()
	{

		if (empty($_POST))
		{

			show_error(lang('unauthorized_access'));

		}

		if (isset($_POST['submit_and_test'])) {
			$results = self::test_settings($_POST['kraken_api_key'], $_POST['kraken_api_secret']);
		}

		unset($_POST['submit']);

		ee()->lang->loadfile('ce_kraken');
		ee()->db->where('class', __CLASS__);
		ee()->db->update('extensions', array('settings' => serialize($_POST)));
		ee()->session->set_flashdata('message_success', lang('settings_saved'));

		if (isset($_POST['submit_and_test'])) {
			ee()->functions->redirect(
	            BASE.AMP.'C=addons_extensions'.AMP.'M=extension_settings'.AMP.'file=ce_kraken'.AMP.'result='.$results[0].AMP.'color='.$results[1]
	        );
		}

	}

	// ----------------------------------------------------------------------

	/**
	* Test API Settings
	*
	* This function will test API credentials by uploading a test image to Kraken.
	*
	* @return mixed TRUE on success FALSE on failure
	*/
	function test_settings($key, $secret)
	{

		$params = array(
		    "url" => "http://url-to-image/file.jpg",
		    "wait" => true
		);

		$response = self::request(json_encode(array_merge($this->auth, $params)));

		if ($response)
		{

			if (isset($response['message']) and strpos($response['message'], 'Unknown api key'))
			{

				return array('The API credentials provided were not accepted by the remote server', 'red');

			}
			else
			{

				return array('The API credentials were accepted by the remote server', 'green');

			}

		}
		else
		{

			return array('There was no response from the remote server', 'red');

		}

	}

	// ----------------------------------------------------------------------

	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	public function activate_extension()
	{

		$this->settings = array(
	        'kraken_api_key'		=> '',
	        'krkaen_api_secret'		=> ''
	    );

		$hooks = array(
			'function_ce_hook'	=> 'ce_img_saved'
		);

		foreach($hooks as $k=>$v){
			$data = array(
				'class'     => __CLASS__,
				'method'    => $k,
				'hook'      => $v,
				'settings'  => serialize($this->settings),
				'priority'  => 10,
				'version'   => $this->version,
				'enabled'   => 'y'
			);

			$this->EE->db->insert('extensions', $data);
		}

	}

	// ----------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{

		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');

	}

	// ----------------------------------------------------------------------

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{

		if ($current == '' OR $current == $this->version)
		{

			return FALSE;

		}

	}

	// ----------------------------------------------------------------------

	/**
	 * CE Hook
	 *
	 * This function is triggered by CE Image hook ce_img_saved and will
	 * send the image to the Kraken API and replace the CE Image with the
	 * returned image if successful
	 *
	 * @return 	null
	 */
	function function_ce_hook($path = '', $type = '')
	{

		if (isset($this->valid_type[$type]) and file_exists($path))
		{

			$original_url = str_replace($this->settings['path_to_made'], $this->settings['url_to_made'], $path);
			self::function_kraken_wait($path, $original_url);

		}

	}

	// ----------------------------------------------------------------------

	/**
	 * Kraken Wait
	 *
	 * This function will post a request to the Kraken API to process an image
	 * and WAIT for a response to be returned for processing
	 *
	 * @return 	null
	 */
	function function_kraken_wait($path, $original_url)
	{

		$params = array(
		    "url" => "$original_url",
		    "wait" => true
		);

		$response = self::request(json_encode(array_merge($this->auth, $params)));

		if ($response and $response['success'])
		{

			self::function_replace_image($path, $response['kraked_url']);

		}

	}

	// ----------------------------------------------------------------------

	/**
	 * Kraken Request
	 *
	 * This function will make the call to the Kraken API
	 *
	 * @return 	null
	 */
	private function request($data, $url = 'https://api.kraken.io/v1/url')
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        return $response;
    }

    // ----------------------------------------------------------------

	/**
	 * Replace Image
	 *
	 * This function retrieve the kraked image and overwrite the local
	 *
	 * @return 	null
	 */
	function function_replace_image($current, $kraked)
	{

		$ch = curl_init($kraked);
		$fp = fopen($current, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		@curl_exec($ch);
		curl_close($ch);
		fclose($fp);

	}

}

/* End of file ext.ce_kraken.php */
/* Location: /system/expressionengine/third_party/ce_kraken/ext.ce_kraken.php */