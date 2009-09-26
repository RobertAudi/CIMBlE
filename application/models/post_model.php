<?php

class Post_model extends Model
{
	function __construct()
	{
		parent::Model();
	}
	
	/**
	 * Retrieve many posts
	 *
	 * @param int $all_posts
	 * @param int $num 
	 * @return array
	 */
	function get_posts($all_posts = NULL, $num = NULL) // NOTE: The first parameter is used to be able to see inactive posts in the admin posts area
	{
		$this->db->select('posts.id, posts.title, posts.body, posts.created_at, posts.updated_at, posts.active, users.username');
		$this->db->from('posts');
		$this->db->join('users', 'posts.user_id = users.id', 'left');
		if ($all_posts === NULL)
			$this->db->where('posts.active',1);
		$this->db->order_by('posts.created_at', 'desc');
		if ($num !== NULL) 
			$this->db->limit($num);
		$query = $this->db->get();
		
		$count = $query->num_rows();
		// if the query managed to retrieve data, return the results in an array of objects
		if ($count > 0)
			return array('list' => $query->result(), 'count' => $count);
		
		// if the query did not retrieve any data, return NULL
		return NULL;
	}
	
	/**
	 * Get one post
	 *
	 * @param int $post_id 
	 * @param int $is_active
	 * @return object
	 */
	function get_post($post_id, $is_active = 1) // NOTE: the second parameter is used to be able to edit inactive posts when logged in
	{
		$this->db->select('posts.id, posts.title, posts.body, posts.created_at, posts.updated_at, posts.active, users.username');
		$this->db->from('posts');
		$this->db->join('users', 'posts.user_id = users.id', 'left');
		$this->db->where('posts.id',$post_id);
		if ($is_active == 1)
			$this->db->where('posts.active',1);
		$this->db->limit(1);
		$query = $this->db->get();
		
		// if the query managed to retrieve a row, return that row as an object
		if ($query->num_rows() == 1)
			return $query->row();
		
		// if the query did not retrieve a row, return NULL
		return NULL;
	}
	
	/**
	 * Inserts a new post in the database
	 *
	 * @param array $post
	 * @return void
	 **/
	function new_post($post)
	{
		// if the post variable provided is empty, return FALSE...
		if(empty($post))
			return FALSE;
		// ...otherwise, insert a new post in the database
		$this->db->insert('posts',$post);
	}
	
	/**
	 * Update an existing post
	 *
	 * @param int $post_id 
	 * @param array $post 
	 * @return void
	 **/
	function update_post($post_id,$post)
	{
		// if the post variable provided is empty, return FALSE...
		if(empty($post))
			return FALSE;
		// ...otherwise, update the correct post in the database
		$this->db->where('id',$post_id);
		$this->db->update('posts',$post);
	}
	
	function delete_post($post_id)
	{
		$this->db->delete('posts',array('id' => $post_id));
	}
}