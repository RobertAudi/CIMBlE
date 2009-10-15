<?php

class Post_model extends Model
{
	// constructor
	public function __construct()
	{
		parent::Model();
	}
	
	/**
	 * Retrieves all the posts from the database.
	 * The first parameter is used to be able to
	 * see inactive posts in the admin posts area.
	 *
	 * @access public
	 * @param int $all_posts 
	 * @param int $num 
	 * @return array
	 **/
	public function get_posts($all_posts = NULL, $num = NULL)
	{
		// get the posts
		$this->db->select('posts.id, posts.title, posts.body, posts.created_at, posts.updated_at, posts.active, users.username');
		$this->db->from('posts');
		$this->db->join('users', 'posts.user_id = users.id', 'left');
		if ($all_posts === NULL)
			$this->db->where('posts.active',1);
		$this->db->order_by('posts.created_at', 'desc');
		if ($num !== NULL) 
			$this->db->limit($num);
		$query = $this->db->get();
		
		// count the number of posts
		$count = $query->num_rows();
		// if the query managed to retrieve data, return the results in an array of objects
		if ($count > 0)
			return array('list' => $query->result(), 'count' => $count);
		
		// if the query did not retrieve any data, return NULL
		return NULL;
	} // End of get_posts
	
	/**
	 * Get one post from the database
	 * The second parameter is used to be able
	 * to edit inactive posts when logged in
	 *
	 * @access public
	 * @param int $post_id 
	 * @param int $is_active 
	 * @return object
	 **/
	public function get_post($post_id, $is_active = 1)
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
	} // End of get_post
	
// =================
// = Admin Methods =
// =================
	
	/**
	 * Inserts new post in the database.
	 *
	 * @access public
	 * @param string $post 
	 * @return bool
	 **/
	public function new_post($post)
	{
		// if the post variable provided is empty, return FALSE...
		if(empty($post))
			return FALSE;
		// ...otherwise, insert a new post in the database
		$this->db->insert('posts',$post);
		return TRUE;
	} // End of new_post
	
	/**
	 * Update an existing post
	 *
	 * @access public
	 * @param int $post_id 
	 * @param array $post 
	 * @return void
	 **/
	public function update_post($post_id,$post)
	{
		// if the post variable provided is empty, return FALSE...
		if(empty($post))
			return FALSE;
		// ...otherwise, update the correct post in the database
		$this->db->where('id',$post_id);
		$this->db->update('posts',$post);
	} // End of update_post
	
	/**
	 * Deletes one post from the database.
	 *
	 * @todo send the user to a confirmation page before deleting a post
	 *
	 * @access public
	 * @param int $post_id 
	 * @return bool
	 **/
	public function delete_post($post_id)
	{
		if (!is_valid_number($post_id))
			return FALSE;
		
		$this->db->delete('posts',array('id' => $post_id));
		return TRUE;
	} // End of delete_post
	
// ------------------------
// - End of Admin Methods -
// ========================
	
} // End of Post_model

/* End of file post_model.php */
/* Location: ./application/model/post_model.php */