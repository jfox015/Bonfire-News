<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Copyright (c) 2012 Shawn Crigger

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

/*
	Class: Author_model
*/
class Author_model extends BF_Model
{

	/**
	 * @var string  User Table Name
	 */
	protected $table		= 'users';
	protected $table_name	= 'users';
	protected $key			= 'id';
	
	/**
	 * @var string  User Name DB Row to Select
	 */
	private   $display_name;
	//--------------------------------------------------------------------

	/**
	 * Simple Constructor to fetch username,  need to check settings to see if we have a Option for Display Name set.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->display_name = 'display_name';

		if ($this->settings_lib->item('auth.use_usernames') == 0 )
		{
			$this->display_name = 'username';
		}

	}

	//--------------------------------------------------------------------

	/**
	 * Returns Name of Author of article.
	 *
	 * @param int $id  User ID of Author
	 *
	 * @return mixed   False if no ID is provided, else String of Display Name of User.
	 */
	public function find_author( $id = 0 )
	{
		if ( (int)$id == 0 )
			return false;

		$this->select( $this->display_name );

		$name = parent::find($id);
		return $name->{$this->display_name};
	}
	
	public function find_author_img( $id = 0 )
	{
		if ( (int)$id == 0 )
			return false;

		
		
		$name = parent::find($id);
		return gravatar_link($name->email);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns array of Users formatted for Select Menu Dropdown
	 *
	 * @return array Returns array of Users formatted for Select Menu Dropdown
	 */
	public function get_users_select ( $insert_empty_row = false )
	{

		$query = $this->db->select('id, '.$this->display_name )->get( $this->table );

		if ( $query->num_rows() <= 0 )
			return '';

		$option = array();

		if ($insert_empty_row !== false)
        {
            $option[-999] = lang('us_select_user');

        }
		foreach ($query->result() as $row)
		{
			$row_id          = (int) $row->id;
			$option[$row_id] = $row->{$this->display_name};
		}

		$query->free_result();

		return $option;
	}

}
