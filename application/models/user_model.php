<?php

class User_model extends Model
{
	function __construct()
	{
		parent::Model();
	}
	
	/**
	 * Get a list of all the users
	 *
	 * @return array
	 **/
	function get_users()
	{
		$this->db->select('users.id, users.username, users.email');
		$query = $this->db->get('users');
		
		if ($query->num_rows() > 0)
			return $query->result();
		return NULL;
	}
	
	/**
	 * Counts the number of users registered
	 *
	 * @return int
	 **/
	function count_users($field = NULL, $value = NULL)
	{
		if ($field === NULL)
		{
			return $this->db->count_all_results('users');
		}
		else
		{
			$this->db->like($field,$value);
			$this->db->from('users');
			return $this->db->count_all_results();
			
		}
	}
	
	/**
	 * Create a user
	 *
	 * @param array $user 
	 * @return bool
	 * @author Robert Audi
	 **/
	function create_user($user)
	{
		return $this->auth->signup($user);
	}
	
	/**
	 * Delete a user
	 *
	 * @param int $user_id 
	 * @return bool
	 **/
	function delete_user($user_id)
	{
		if ($this->count_users() <= 1)
			return FALSE;
		
		$match = $this->count_users('id',$user_id);
		
		if ($match != 1)
		{
			return FALSE;
		}
		else
		{
			$this->db->delete('users',array('id' => $user_id));
		}
	}
}