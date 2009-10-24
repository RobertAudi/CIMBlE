<?php

class Comment_model extends Model
{
	// constructor
	public function __construct()
	{
		parent::Model();
	}
	
// ==================
// = Public Methods =
// ==================
	
	/**
	 * Get all the comments or a set number of them for the current post.
	 * If $post_id is NULL, all the posts will be retrieved, for all the posts, even the 
	 * comments that are set as spam or are not yet approved.
	 *
	 * @access public
	 * @param int $post_id 
	 * @param int|string $num
	 * @return array
	 **/
	public function get_comments($post_id = NULL, $num = NULL)
	{
		if(!empty($post_id) && !is_valid_number($post_id))
			return NULL;
		
		// get the comments
		$this->db->select('a.id, a.post_id, a.author_name, a.author_email, a.author_website, a.body, a.created_at, a.parent_id, a.is_spam, posts.title');
		$this->db->from('comments a');
		$this->db->join('posts', 'a.post_id = posts.id', 'left');
		if (is_valid_number($post_id))
			$this->db->where('a.post_id', $post_id);
		if (!is_valid_number($num))
		{
			if ($num === 'ham')
			{
				$this->db->where('a.is_spam', 0);
			}
			elseif ($num === 'spam')
			{
				$this->db->where('a.is_spam', 1);
			}
		}
		$this->db->order_by('a.created_at', 'desc');
		if(is_valid_number($num))
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
	 * Get exately one comment from the database
	 *
	 * @access public
	 * @param int $comment_id 
	 * @return object
	 **/
	public function get_comment($comment_id)
	{
		if(!is_valid_number($comment_id))
			return NULL;
		
		// get the comment
		$this->db->select('a.id, a.post_id, a.author_name, a.author_email, a.author_website, a.body, a.created_at, a.parent_id, a.is_spam, posts.title');
		$this->db->from('comments a');
		$this->db->join('posts', 'a.post_id = posts.id', 'left');
		$this->db->where('a.id', $comment_id);
		$this->db->limit(1);
		$query = $this->db->get();
		
		// count the number of comments
		$count = $query->num_rows();
		
		// if no comment was returned, or if by some magic more than one comment was returned, it means something went wrong
		if ($count != 1)
			return NULL;
		
		return $query->row();
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
		
	/**
	 * Set a comment to ham and submit the ham to the askismet server
	 *
	 * @access public
	 * @param array $comment 
	 * @return bool
	 **/
	public function submit_ham($comment_id)
	{
		if (!is_valid_number($comment_id))
			return FALSE;
		
		$this->db->update('comments', array('is_spam' => 0), array('id' => $comment_id));
		
		$this->load->library('azakis');
		return $this->azakis->submit_ham($this->_get_minimum_comment_data($comment_id));
	} // End of submit_ham
	
	/**
	 * Set a comment to spam and submit the spam to the askismet server
	 *
	 * @access public
	 * @param array $comment 
	 * @return bool
	 **/
	public function submit_spam($comment_id)
	{
		if (!is_valid_number($comment_id))
			return FALSE;
		
		$this->db->update('comments', array('is_spam' => 1), array('id' => $comment_id));
		
		$this->load->library('azakis');
		return $this->azakis->submit_spam($this->_get_minimum_comment_data($comment_id));
	} // End of submit_spam
	
	/**
	 * Delete one comment from the database
	 *
	 * @param int $comment_id
	 * @return bool
	 **/
	public function delete($comment_id)
	{
		if (!is_valid_number($comment_id))
			return FALSE;
		
		$this->db->delete('comments', array('id' => $comment_id));
		return TRUE;
	} // End of delete
	
// -------------------------
// - End of Public Methods -
// =========================
	
// ===================
// = Private Methods =
// ===================
	
	/**
	 * Get the minimum info about one comment from the database
	 * Intended to be used with the submit_ham and submit_spam methods
	 *
	 * @todo rename that fucking method, its name sucks
	 * @access private
	 * @param int $comment_id 
	 * @return array
	 **/
	private function _get_minimum_comment_data($comment_id)
	{
		if (!is_valid_number($comment_id))
			return NULL;
		
		$this->db->select('author_name, author_email, author_website, body');
		$query = $this->db->get_where('comments', array('id' => $comment_id), 1);
		
		if ($query->num_rows() != 1)
			return NULL;
		
		return $query->row_array();
	} // End of _get_minimum_comment_data
	
// --------------------------
// - End of Private Methods -
// ==========================
	
} // End of Comment_model

/* End of file comment_model */
/* Location: ./application/models/comment_model */