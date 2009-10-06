<?php

class Comment_model extends Model
{
	// constructor
	public function __construct()
	{
		parent::Model();
	}
	
	// php4 compatibility
	public function Comment_model()
	{
		self::__construct();
	}
	
	/**
	 * Get all the comments or a set number of them for the current post.
	 * At least one of the two arguments must not be NULL.
	 * If $post_id is NULL, then this method will retrieve the {$num} most recent comments.
	 *
	 * @access public
	 * @param int $post_id 
	 * @param int $num
	 * @return array
	 **/
	public function get_comments($post_id = NULL, $num = NULL)
	{
		// get the comments
		$this->db->select('a.id, a.author_name, a.author_email, a.author_website, a.body, a.created_at, a.parent_id');
		$this->db->from('comments a');
		if ($post_id !== NULL)
			$this->db->join('posts', 'a.post_id = posts.id', 'left');
		$this->db->where('posts.id',$post_id);
		$this->db->order_by('a.created_at', 'desc');
		if($num !== NULL)
			$this->db->limit($num);
		$query = $this->db->get();
		
		// count the number of comments
		$count = $query->num_rows();
		
		// if there is any comments, return the list of comments and their count
		if ($count > 0)
			return array('list' => $query->result(), 'count' => $count);
		
		// else return NULL
		return NULL;
	} // End of get_comments
	
	/**
	 * Get one specific comment.
	 *
	 * @access public
	 * @param int $comment_id 
	 * @return
	 **/
	public function get_comment($comment_id = NULL)
	{
		
	} // End of get_comment
	
	/**
	 * Submit a new comment to the current post
	 *
	 * @access public
	 * @return bool
	 **/
	public function add_comment($comment = NULL)
	{
		// if the comment passed as argument is empty we can't add it to the database
		if (empty($comment))
			return FALSE;
		
		// add the comment to the database and return true to let the controller know that the insertion was successful
		$this->db->insert('comments', $comment);
		return TRUE;
	} // End of add_comment
	
} // End of Comment_model

/* End of file comment_model */
/* Location: ./application/models/comment_model */