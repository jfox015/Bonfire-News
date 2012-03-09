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

	protected $table		= 'users';

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
	}

	//--------------------------------------------------------------------

	public function find_author( $id = 0 )
	{
		if ( (int)$id == 0 )
			return false;

		$this->select('username');

		$name = parent::find($id);
		return $name->username;
	}

	//--------------------------------------------------------------------

  public function get_users_select ( )
  {
		$query = $this->db->select('id, username')->get( $this->table );

		if ( $query->num_rows() <= 0 )
			return '';

    $option = array();

    foreach ($query->result() as $row)
    {
      $row_id          = (int) $row->id;
      $option[$row_id] = $row->username;
    }

    $query->free_result();

    return $option;
  }

}
