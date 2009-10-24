<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Azauth Authentication Library
 * 
 * @package CIMBlE
 * @category CodeIgniter Library
 * @author Aziz Light
 * @version 0.0.1
 * @copyright Copyright (c) 2009, Aziz Light
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 **/
class MY_Pagination extends CI_Pagination
{
	/**
	 * Generate the pagination for the given data.
	 * The data array must contain two keys: the offset
	 * and the list of whatever we want to paginate.
	 *
	 * @param array $data 
	 * @param array $config 
	 * @return array
	 **/
	public function generate($data, $config)
	{
		// If the offset is invalid or NULL (in which case the user goes back to the first page anyway)
		// the user is sent back to the first page and a feedback message is displayed
		if ((!is_valid_number($data['offset']) || !array_key_exists($data['offset'],$data['list'])) && !empty($data['offset']))
			return NULL;
		
		// array of required config items
		$required_config_items = 	array(
										'base_url',
										'total_rows',
										'per_page'
									);
		// if any of the required config items is not provided, the pagination generation will fail
		foreach ($required_config_items as $config_item) {
			if(!array_key_exists($config_item, $config) || empty($config_item))
				return NULL;
		}
		
		// if one of those two keys does not exist we override their defautl value
		if (!array_key_exists('full_tag_open') || !array_key_exists('full_tag_close'))
		{
			$config['full_tag_open']  = '<div class="pagination_links"';
			$config['full_tag_close'] = '</div>';
		}
		
		// initialize pagination with the configuration array above
		$this->initialize($config);
		
		// Create pagination links and store them in the data array
		$links = $this->create_links();
		
		// Dynamically generate the posts pagination everytime the user clicks on a pagination link
		$list = $this->paginate($data['list'], $config['per_page'], $data['offset']);
		
		return array('list' => $list, 'links' => $links);
	} // End of generate
	
	/**
	 * Gets the page number the user is on
	 *
	 * @param int $offset
	 * @param int $number_of_posts_per_page 
	 * @return int
	 **/
	public function get_page_number($offset, $number_of_posts_per_page)
	{
		if (!is_valid_number($offset))
		{
			if (empty($offset))
			{
				return 1;
			}
			else
			{
				return NULL;
			}
		}
		else
		{
			return ($offset / $number_of_posts_per_page) + 1;
		}
	} // End of get_page_number
	
	/**
	 * Create a temporary array of items out of a bigger array
	 * so that the correct items are displayed on each page
	 *
	 * @param array $items 
	 * @param int $number_of_items_per_page 
	 * @param string $offset 
	 * @return array
	 **/
	public function paginate($items, $number_of_items_per_page, $offset)
	{
		if (!is_valid_number($offset))
		{
			if (empty($offset))
			{
				$first_item_to_display = 0;
			}
			else
			{
				return NULL;
			}
		}
		else
		{
			$first_item_to_display = $offset;
		}
		
		// if items is empty, array_slice will return an error, so we will just return the unchanged items variable in that case.
		if(empty($items) || !isset($items))
			return $items;
		
		$page_items = array_slice($items, $first_item_to_display, $number_of_items_per_page);
		
		return $page_items;
	} // End of paginate
	
} // End of MY_Pagination class

/* End of file MY_Pagination.php */
/* Location: ./application/libraries/MY_Pagination.php */