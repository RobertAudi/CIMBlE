<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Text Input Field
 * Extended: Will automatically add an id that has the same value as the name
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
function form_input($data = '', $value = '', $extra = '')
{
	$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value, 'id' => $data);
	
	return "<input "._parse_form_attributes($data, $defaults).$extra." />";
}

// ------------------------------------------------------------------------

/**
 * Password Field
 *
 * Identical to the input function but adds the "password" type
 * Extended: Will automatically add an id that has the same value as the name
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
function form_password($data = '', $value = '', $extra = '')
{
	if ( ! is_array($data))
	{
		$data = array('name' => $data, 'id' => $data);
	}
	
	$data['type'] = 'password';
	return form_input($data, $value, $extra);
}

/* End of file MY_form_helper.php */
/* Location: ./application/helpers/MY_form_helper.php */