<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang = array(
	'ce_kraken_module_name'					=> 'CE Kraken',
	'ce_kraken_module_description'			=> 'Image crusher extension for EE when using CE Image and the Kraken.io API',
	'module_home'							=> 'CE Kraken Home',
	'use_callback'							=> 'Callback URL',
	'use_wait'								=> 'Wait for Response',
	'kraken_method'							=> 'Kraken Response Method<br><small>Wait for Response: This method will post the image and then wait for the server to process
												the image and respond.<br>Callback URL: This method will post the image and the script will end. The Kraken.io
												server will then post the resutls to a callback URL once the image has been processed.<br>
												NOTE: Callback URL requires ce kraken module to be installed.</small>',
	'kraken_api_key'						=> 'Kraken API Key',
	'kraken_api_secret'						=> 'Kraken API Secret',
	'settings_saved'						=> 'Your settings have been updated',
	'url_to_third_party'					=> 'URL to third party folder WITH trailing slash<br>
												<small>This is to enable testing of the API credentials and is not required. If used the "test_folder" within the
												kraken addon must have permissions set to 777.</small>',
	'submit_and_test'						=> 'Submit & Test API',
	'path_to_made'							=> 'Path to "Made" folder<br><small>This is the path to the CE Image "made" folder from the root. Include a trailing slash.
												(e.g /root/sites/mysite/public/images/)</small>',
	'url_to_made'							=> 'URL to "Made" folder<br><small>This is the url to the CE Image "made" folder. Include a trailing slash.
												(e.g http://mysite.com/public/images/)</small>'
);

/* End of file lang.ce_kraken.php */
/* Location: /system/expressionengine/third_party/ce_kraken/language/english/lang.ce_kraken.php */
