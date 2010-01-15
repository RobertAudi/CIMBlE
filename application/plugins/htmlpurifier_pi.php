<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Purify HTML code using HTMLPurifier
 *
 * @param string|array $html : The html to purify
 * @return string|array
 */
function purify($html)
{
	if (empty($html) || trim((string)$html) === '')
	{
		log_message('error','htmlpurifier_pi::purify : The html you sent to the HTML Purifier is empty...I wonder how is that possible...');
		return FALSE;
	}
	
	if (is_array($html))
	{
		foreach ($html as $key => $value)
		{
			$html[$key] = purify($value);
		}
		
		return $html;
	}
	else
	{
		require_once(APPPATH . 'plugins/htmlpurifier/HTMLPurifier.standalone.php'); 
		
		$allowed_tags = 'p,em,i,strong,b,a[href],ul,ol,li,code,pre,blockquote';
		
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
		$config->set('HTML.Allowed', $allowed_tags);
		$config->set('HTML.TidyLevel', 'heavy');
		$config->set('AutoFormat.Linkify', 'true');
		$htmlpurifier = new HTMLPurifier($config);
		
		return $htmlpurifier->purify($html);
	}
} // End of purify

/* End of file htmlpurifier_pi.php */
/* Location: ./application/plugins/htmlpurifier_pi.php */