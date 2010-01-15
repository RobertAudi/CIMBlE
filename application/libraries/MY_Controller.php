<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Controller
 *
 * @package MY_Controller
 * @category CodeIgniter Library
 * @author Aziz Light
 * @link MY_Controller
 * @version 0.0.1
 * @copyright Copyright (c) 2009
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 **/
class MY_Controller extends Controller
{
	protected $login_page;
	protected $main_view;
	protected $main_admin_view;
	
// ------------------------------------------------------------------------
	
	/**
	 * The Constructor!
	 * 
	 * @access public
	 **/
	public function __construct()
	{
		parent::Controller();
		$this->_associate_class_variables($this->config->item('class_variables'));
		$this->_restrict_access();
		
		// ------------------------------------------------------------------------
		// DEBUG = FirePHP + FireIgnition =
		// ------------------------------------------------------------------------
		$this->load->config('fireignition');
		if ($this->config->item('fireignition_enabled'))
		{
			if (floor(phpversion()) < 5)
			{
				log_message('error', 'PHP 5 is required to run fireignition');
			} else {
				$this->load->library('firephp');
			}
		}
		else 
		{
			$this->load->library('firephp_fake');
			$this->firephp =& $this->firephp_fake;
		}
		// ------------------------------------------------------------------------
	} // End of __construct
	
// ------------------------------------------------------------------------
	
	/**
	 * Give to the class variables their values set in the class_variables
	 * config item in the MY_Config config file.
	 * 
	 * @access private
	 * @param array $config : the array containing all the name of the class variables and their values. By default I called this array class_variables.
	 * @return bool
	 */
	private function _associate_class_variables($config)
	{
		if (!is_array($config))
			return FALSE;
		
		foreach ($config as $key => $value)
		{
			$this->$key = $value;
		}
		
		return TRUE;
	} // End of _generate_class_variables
	
// ------------------------------------------------------------------------
	
	/**
	 * Restrict the access to the admin section of the site for users that are not logged in.
	 * Also redirects the user to the login page if he's not already logged in.
	 *
	 * @access private
	 * @return void
	 */
	private function _restrict_access()
	{
		// we need to check the content of the url to prevent getting in an infinite loop of redirects
		if (preg_match(to_regex($this->login_page),uri_string()) !== 1)
		{
			if (preg_match('/^\/admin/',uri_string()) === 1 && $this->azauth->logged_in() === FALSE)
			{
				// I don't know if that's an absolutely horrible hack or a nifty trick but it works like a charm
				redirect($this->login_page);
			}
		}
	} // End of _restrict_access
	
} // End of MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/libraries/MY_Controller.php */