<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wp_import('wp-includes.pluggable');

/**
** Users Library
** Developed 03/01/2013
**/

class wpl_users
{
	/**
		@input {category}, [kind] and [enabled]
		@return boolean result
		@description for deleting user from wpl
		@author Howard
	**/
	public function get_plisting_fields($category = '', $kind = 2, $enabled = 1)
	{
		return wpl_flex::get_fields($category, $enabled, $kind, 'plisting', '1');
	}
	
	/**
		@input {user_id}
		@return boolean result
		@description for deleting user from wpl
		@author Howard
	**/
	public function delete_user_from_wpl($user_id)
	{
		$query = "DELETE FROM `#__wpl_users` WHERE `id`='$user_id'";
		$result = wpl_db::q($query);
		
		return $result;
	}
	
	/**
		@input {user_id}
		@return boolean result
		@description for adding user to wpl
		@author Howard
	**/
	public function add_user_to_wpl($user_id)
	{
		/** first validation **/
		if(wpl_users::get_wpl_user($user_id)) return true;
		
		$user_data = wpl_users::get_user($user_id);
		$default_data = wpl_users::get_wpl_data(-1);
		
		$forbidden_fields = array('id', 'first_name', 'last_name');
		$auto_query1 = '';
		$auto_query2 = '';
		
		foreach($default_data as $key=>$value)
		{
			if(in_array($key, $forbidden_fields)) continue;
			
			$auto_query1 .= "`$key`,";
			$auto_query2 .= "'$value',";
		}
		
		if($user_data)
		{
			$auto_query1 .= "`first_name`,`last_name`,";
			$auto_query2 .= "'".$user_data->data->meta['first_name']."','".$user_data->data->meta['last_name']."',";
		}
		
		$auto_query1 = trim($auto_query1, ', ');
		$auto_query2 = trim($auto_query2, ', ');
		
		$query = "INSERT INTO `#__wpl_users` (`id`, ".$auto_query1.") VALUES ('".$user_id."', ".$auto_query2.")";
		$result = wpl_db::q($query);
		
		return $result;
	}
	
	/**
		@input {args} and [add_data]
		@return users object
		@description for getting all wordpress users (included wpl data)
		@author Howard
	**/
	public function get_all_wp_users($args, $add_data = false)
	{
		$users = get_users($args);
		if(!$add_data) return $users;
		
		foreach($users as $key=>$user)
		{
			$users[$key]->meta = self::get_user_meta($user->ID);
			$users[$key]->wpl_data = self::get_wpl_data($user->ID);
		}
		
		return $users;
	}
	
	/**
		@input {args} and [add_data]
		@return query result
		@description for getting wordpress users
		@author Howard
	**/
	public function get_wp_users($query = '')
	{
		$query = "SELECT * FROM `#__users` AS u LEFT JOIN `#__wpl_users` AS wpl ON u.ID = wpl.id WHERE 1 $query";
		$users = wpl_db::select($query);
		
		return $users;
	}
	
	/**
		@input {args} and [add_data]
		@return query result
		@description for getting wordpress users included in WPL
		@author Howard
	**/
	public static function get_wpl_users($query = '')
	{
		$query = "SELECT * FROM `#__users` AS u INNER JOIN `#__wpl_users` AS wpl ON u.ID = wpl.id WHERE 1 $query";
		$users = wpl_db::select($query);
		
		return $users;
	}
	
	/**
		@input [user_id]
		@return query result
		@description for getting all user data (included wordpress and wpl data)
		@author Howard
	**/
	public static function get_user($user_id = '')
	{
		/** fetch currenr user data if user id is empty **/
		if(trim($user_id) == '') $user_id = self::get_cur_user_id();
		
		/** fetch user data **/
		$user_data = get_userdata($user_id);
		
		$user_data->meta = self::get_user_meta($user_id);
		$user_data->wpl_data = self::get_wpl_data($user_id);
		
		return $user_data;
	}
	
	/**
		@input [user_id]
		@return query result
		@description for getting user data (just wpl data)
		@author Howard
	**/
	public function get_wpl_user($user_id = '')
	{
		/** load current user **/
		if(trim($user_id) == '') $user_id = self::get_cur_user_id();
		
		$query = "SELECT * FROM `#__wpl_users` WHERE `id`='$user_id'";
		return wpl_db::select($query, 'loadObject');
	}
	
	/**
		@input [user_id], [key] and [single]
		@return query result
		@description for getting user meta data
		@author Howard
	**/
	public function get_user_meta($user_id = '', $key = '', $single = '')
	{
		$rendered_meta = array();
		
		if(trim($user_id) == '') $user_id = self::get_cur_user_id();
		if(!$user_id) return false;
		
		$user_meta = get_user_meta($user_id, $key, $single);
		
		foreach($user_meta as $key=>$meta)
		{
			if(count($meta) == 1) $rendered_meta[$key] = $meta[0];
			else $rendered_meta[$key] = $meta;
		}
		
		return $rendered_meta;
	}
	
	/**
		@input [user_id]
		@return query result
		@description for getting user meta data
		@author Howard
	**/
	public function get_wpl_data($user_id = '')
	{
		if(trim($user_id) == '') $user_id = self::get_cur_user_id();
		return $results = wpl_db::get('*', 'wpl_users', 'id', $user_id);
	}
	
	/**
		@input void
		@return int user_id
		@description for getting current user id
		@author Howard
	**/
	public function get_cur_user_id()
	{
		return get_current_user_id();
	}
	
	/**
		@input {username}
		@return user id
		@description for getting user id by username
		@author Howard
	**/
	public function get_id_by_username($username)
	{
		$query = "SELECT * FROM `#__users` WHERE `user_login`='$username'";
		$user = wpl_db::select($query, 'loadObject');
		
		return $user->ID;
	}
	
	/**
		@input {username}
		@return user id
		@description for getting user id by username
		@author Howard
	**/
	public function get_id_by_email($email)
	{
		$query = "SELECT * FROM `#__users` WHERE `user_email`='$email'";
		$user = wpl_db::select($query, 'loadObject');
		
		return $user->ID;
	}
	
	/**
		@input void
		@return string ip
		@description for getting current ip
		@author Howard
	**/
	public function get_current_ip()
	{
		return wpl_request::getVar('REMOTE_ADDR', '', 'SERVER');
	}
	
	/**
		@input [user_id]
		@return wp role of user
		@description for getting user role (wp feature)
		@author Howard
	**/
	public function get_role($user_id = '')
	{
		$user_data = self::get_user($user_id);
		return $user_data->roles[0];
	}
	
	/**
		@input void
		@return array roles
		@author Howard
	**/
	public function get_wpl_roles()
	{
		$roles = array();
		$roles['admin'] = 'administrator';
		$roles['editor'] = 'editor';
		$roles['agent'] = 'author';
		$roles['Contributor'] = 'Contributor';
		$roles['subscriber'] = 'subscriber';
		$roles['guest'] = 'guest';
		
		return $roles;
	}
	
	/**
		@input {role}
		@return role point
		@author Howard
	**/
	public function get_role_point($role)
	{
		/** get all roles **/
		$roles = self::get_wpl_roles();
		
		/** role validation **/
		if(!in_array($role, $roles)) $role = 'guest';
		
		$roles_point = array();
		$roles_point['administrator'] = 5;
		$roles_point['editor'] = 4;
		$roles_point['author'] = 3;
		$roles_point['Contributor'] = 2;
		$roles_point['subscriber'] = 1;
		$roles_point['guest'] = 0;
		
		return $roles_point[$role];
	}
	
	/**
		@input {caps} and {user_id}
		@return boolean
		@description for checking if user have the capability or not
		@author Howard
	**/
	public function is($caps, $user_id)
	{
		$result = false;
		
		if(is_array($caps))
		{
			foreach($caps as $cap)
			{
				if(self::is($cap, $user_id)) return true;
			}
			
			return false;
		}
		
		$user_data = self::get_user($user_id);
		if(!$user_data) return false;
		
		if($user_data->caps[$caps]) $result = true;
		return $result;
	}
	
	/**
		@input void
		@return object memberships
		@description for getting wpl memberships
		@author Morgan
	**/
	public function get_wpl_memberships()
	{
		$query = "SELECT * FROM `#__wpl_users` WHERE `id` < 0 ORDER BY `id` DESC";
		$memberships = wpl_db::select($query);
		
		return $memberships;
	}
	
	/**
		@input void
		@return object membership types
		@description for getting membership types
		@author Morgan
	**/
	public function get_membership_types()
	{
		$query = "SELECT * FROM `#__wpl_user_group_types`";
		$result = wpl_db::select($query);
		
		return $result;
	}
	
	/**
		@input void
		@return in membership id
		@description for getting unique id for new membership
		@author Morgan
	**/
	public function get_mmbership_id()
	{
		$query = "SELECT MIN(id) as min_id FROM `#__wpl_users`";
		$result = wpl_db::select($query, 'loadResult');
		
		/** generate new membership id **/
		return ($result-1);
	}
	
	/**
		@input {membership_id}
		@return boolean
		@description for removing a membership
		@author Howard
	**/
	public function remove_membership($membership_id)
	{
		/** don't remove default and guest membership **/
		if(in_array($membership_id, array(-1, -2))) return false;
		
		$query = "UPDATE `#__wpl_users` SET `membership_id`='-1' WHERE `membership_id`='$membership_id'";
		wpl_db::q($query);
		
		$query = "DELETE FROM `#__wpl_users` WHERE `id`='$membership_id'";
		wpl_db::q($query);
		
		return true;
	}
	
	/**
		@input {membership_id}
		@return object membership_data
		@description for getting a membership data
		@author Morgan
	**/
	public function get_membership($membership_id)
	{
		$query = "SELECT * FROM `#__wpl_users` WHERE `id`='$membership_id'";
		return wpl_db::select($query, 'loadObject');
	}
		
	/**
		@input {table}, {key}, {unit_id} and [value]
		@return boolean result
		@author Howard
	**/
	public function update($table = 'wpl_users', $id, $key, $value = '')
	{
		/** first validation **/
		if(trim($table) == '' or trim($id) == '' or trim($key) == '') return false;
		return wpl_db::set($table, $id, $key, $value);
	}
	
	/**
		@input {user_id}
		@return boolean result
		@description for checking if a user is wordpress admin or not
		@author Howard
	**/
	public function is_super_admin($user_id = '')
	{
		/** get current user id **/
		if(!trim($user_id)) $user_id = wpl_users::get_cur_user_id();
		if($user_id == 0 or $user_id == '') return false;
		
		return wpl_global::has_permission('administrator', $user_id);
	}
	
	/**
		@input {user_id}
		@return boolean result
		@description for checking if a user is added to wpl or not
		@author Howard
	**/
	public function is_wpl_user($user_id = '')
	{
		/** get current user id **/
		if(!trim($user_id)) $user_id = wpl_users::get_cur_user_id();
		if($user_id == 0 or $user_id == '') return false;
		
		$result = wpl_users::get_wpl_user($user_id);
		
		if(!$result) return false;
		else return true;
	}
	
	/**
		@input {user_id} and {membership_id}
		@return void
		@description use this function for changing membership of a user
		@author Howard
	**/
	public function change_membership($user_id, $membership_id = -1, $trigger_event = true)
	{
		$user_data = wpl_users::get_wpl_data($user_id);
		$membership_data = wpl_users::get_wpl_data($membership_id);
	
		$forbidden_fields = array('id');
		$query1 = '';
		
		foreach($membership_data as $key=>$value)
		{
			if(substr($key, 0, 7) != 'access_' and substr($key, 0, 8) != 'maccess_') continue;
			
			$query1 .= "`$key`='$value', ";
		}
		
		$query1 = trim($query1, ', ');
		$query = "UPDATE `#__wpl_users` SET ".$query1.", `membership_id`='".$membership_id."' WHERE `id`='".$user_id."'";
		wpl_db::q($query);
		
		/** trigger event **/
		if($trigger_event and $user_data->membership_id == $membership_id)
			wpl_global::event_handler('user_access_updated', array('user_id'=>$user_id, 'previous_membership'=>$user_data->membership_id, 'new_membership'=>$membership_id));
		elseif($trigger_event and $user_data->membership_id != $membership_id)
			wpl_global::event_handler('user_group_changed', array('user_id'=>$user_id, 'previous_membership'=>$user_data->membership_id, 'new_membership'=>$membership_id));
	}
	
	/**
		@input {access}, [owner_id] and [user_id]
		@return void
		@description use this function for changing membership of a user
		@author Howard
	**/
	public function check_access($access, $owner_id = 0, $user_id = '')
	{
		/** get current user id **/
		if(trim($user_id) == '') $user_id = wpl_users::get_cur_user_id();
		
		$user_data = wpl_users::get_wpl_data($user_id);
		
		if($access == 'edit')
		{
			if($owner_id == $user_id or wpl_users::is_super_admin($user_id)) return true;
		}
		elseif($access == 'add')
		{
			$num_prop_limit = $user_data->maccess_num_prop;
			$num_prop = wpl_users::get_users_properties_count($user_id);
			
			if($num_prop_limit == '-1') return true; # unlimited
			if($num_prop_limit <= $num_prop and !wpl_users::is_super_admin($user_id)) return false;
			else return true;
		}
		elseif($access == 'delete')
		{
			if($user_data->access_delete and ($owner_id == $user_id or wpl_users::is_super_admin($user_id))) return true;
		}
		elseif($access == 'confirm')
		{
			if($user_data->access_confirm and ($owner_id == $user_id or wpl_users::is_super_admin($user_id))) return true;
		}
		else
		{
			return isset($user_data->{'access_'.$access}) ? $user_data->{'access_'.$access} : 0;
		}
		
		return false;
	}
	
	/**
		@input {user_id} and [condition]
		@return void
		@description use this function for getting count of properties
		@author Howard
	**/
	public function get_users_properties_count($user_id = '', $condition = '')
	{
		/** get current user id **/
		if(trim($user_id) == '') $user_id = wpl_users::get_cur_user_id();
		
		$query = "SELECT COUNT(id) FROM `#__wpl_properties` WHERE `user_id`='$user_id' ".$condition;
		return wpl_db::select($query, 'loadResult');
	}
	
	/**
		@inputs {start}, {limit}, {orderby}, {order}, {where}
		@param int $start
		@param int $limit
		@param string $orderby
		@param string $order
		@param array $where
		@return void
		@description for start property model use this function for configuration
		@author Howard
	**/
	public function start($start, $limit, $orderby, $order, $where)
    {
		/** start time of model **/
		$this->start_time = microtime(true);
		
		/** pagination and order options **/
		$this->start = $start;
		$this->limit = $limit;
		$this->orderby = $orderby;
		$this->order = $order;
		
		/** main table **/
		$this->main_table = "`#__wpl_users` AS p";
		
		/** queries **/
		$this->join_query = $this->create_join();
		$this->groupby_query = $this->create_groupby();
		
		/** generate where condition **/
		$where = (array) $where;
		$vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		$vars = array_merge($vars, $where);
		
		$this->where = wpl_db::create_query($vars);
		
		/** generate select **/
		$this->select = '*';
    }
	
	/**
		@inputs void
		@return string $quert
		@description this functions creates complete query
		@author Howard
	**/
	public function query()
    {
		$this->query  = " SELECT ".$this->select;
        $this->query .= " FROM ".$this->main_table;
        $this->query .= $this->join_query;
		$this->query .= " WHERE 1 ".$this->where;
		$this->query .= $this->create_groupby();
        $this->query .= " ORDER BY ".$this->orderby." ".$this->order;
        $this->query .= " LIMIT ".$this->start.", ".$this->limit;
		$this->query  = trim($this->query, ', ');
		
		return $this->query;
    }
	
	/** [TODO] **/
	public function create_join()
	{
		return '';
	}
	
	/** [TODO] **/
	public function create_groupby()
	{
		return '';
	}
	
	/**
		@inputs string $query
		@return object $properties
		@description use this function for running query and fetch the result
		@author Howard
	**/
	public function search($query = '')
    {
        if(!trim($query)) $query = $this->query;
		
        $users = wpl_db::select($query);
        return $users;
    }
	
	/**
		@inputs void
		@return int $time_taken
		@description this function is for calculating token time and total result
		@author Howard
	**/
	public function finish()
	{
		$this->finish_time = microtime(true);
        $this->time_taken = $this->finish_time - $this->start_time;
		$this->total = $this->get_users_count();
		
		return $this->time_taken;
	}
	
	/**
     * @return number of users according to query condition
     * @author Howard
     */
    public function get_users_count()
    {
        $query = "SELECT COUNT(*) AS count FROM `#__wpl_users` WHERE 1 " . $this->where;
        return wpl_db::select($query, 'loadResult');
    }
	
	/**
		@inputs {user_id}
		@return profile_show full link
		@author Howard
	**/
	public function get_profile_link($user_id = '')
	{
		/** fetch currenr user data if user id is empty **/
		if(trim($user_id) == '') $user_id = self::get_cur_user_id();
		
		$user_data = self::get_user($user_id);
		$url = wpl_global::get_wp_site_url().wpl_global::get_setting('main_permalink').'/';
		
		$url .= urlencode($user_data->data->user_login).'/';
		
        return $url;
    }
	
	/**
		@inputs [params]
		@return array or html
		@description Use this function for generating sort options
		@author Howard
	**/
	public function generate_sorts($params = array())
	{
		include _wpl_import('views.basics.sorts.profile_listing', true, true);
		return $result;
	}
	
	/**
		@inputs {property data}, [fields] and [finds]
		@param property data should be raw data from wpl_properties table
		@param fields should be an object of dbst fields
		@param finds detected files array
		@return rendered data
		@author Howard
	**/
	public function render_profile($profile, $fields, &$finds = array())
	{
		_wpl_import('libraries.property');
		return wpl_property::render_property($profile, $fields, $finds);
	}
	
	/**
		@inputs {user_id}
		@description This function finalizes user profile and triggering events
		@author Howard
	**/
	public function finalize($user_id)
	{
		/** create folder **/
		$folder_path = wpl_items::get_path($user_id, 2);
		
		if(!wpl_folder::exists($folder_path)) wpl_folder::create($folder_path);
		
		wpl_users::update_text_search_field($user_id);
        wpl_users::update_location_text_search_field($user_id);
		wpl_users::generate_email_files($user_id);
		
		/** generate rendered data **/
		if(wpl_settings::get('cache')) wpl_users::generate_rendered_data($user_id);
		
        /** throwing event **/
        wpl_events::trigger('user_finalized', $user_id);
		
		return true;
    }
	
	/**
		@inputs {user_id}
		@description This function is for updating location text search field
		@author Howard
	**/
	public function update_location_text_search_field($user_id)
	{
        $user_data = (array) wpl_users::get_wpl_user($user_id);
		$location_text = $user_data['location7_name'].', '.$user_data['location6_name'].', '.$user_data['location5_name'].', '.
						 $user_data['location4_name'].', '.$user_data['location3_name'].', '.$user_data['location2_name'].', '.$user_data['location1_name'];
		
		$location_text = $user_data['zip_name'].', '.trim($location_text, ', ');
		wpl_db::set('wpl_users', $user_id, 'location_text', trim($location_text, ', '));
    }
	
	/**
		@inputs {user_id}
		@description This function is for updating textsearch field
		@author Howard
	**/
	public function update_text_search_field($user_id)
	{
        $user_data = (array) wpl_users::get_wpl_user($user_id);
		
		/** get text_search fields **/
		$fields = wpl_flex::get_fields('', 1, 2, 'text_search', '1');
		$rendered = self::render_profile($user_data, $fields);
		
		$text_search_data = array();
		
		foreach($rendered as $data)
		{
			if(!trim($data['type']) or !trim($data['value'])) continue;
			
			/** default value **/
			$value = $data['value'];
			$value2 = '';
			$type = $data['type'];
			
			if($type == 'text' or $type == 'textarea')
			{
				$value = $data['name'] .' '. $data['value'];
			}
			elseif($type == 'locations')
			{
				$location_value = '';
				foreach($data['locations'] as $location_level=>$value)
				{
					$location_value .= $data['keywords'][$location_level] .' ';
					$location_value .= $value . ' ';
				}
				
				$value = $location_value;
			}
			elseif(isset($data['value']))
			{
				$value = $data['name'] .' '. $data['value'];
				if(is_numeric($data['value']))
				{
					$value2 = $data['name'] .' '. wpl_global::number_to_word($data['value']);
				}
			}
			
			/** set value in text search data **/
			$text_search_data[] = $value;
			if(trim($value2) != '') $text_search_data[] = $value2;
		}
		
		wpl_db::set('wpl_users', $user_id, 'textsearch', implode(' ', $text_search_data));
    }
	
	/**
		@inputs {user_id}
		@description This function is for generating email files of user
		@author Howard
	**/
	public function generate_email_files($user_id)
	{
		/** import library **/
		_wpl_import('libraries.images');
		
        $user_data = (array) wpl_users::get_wpl_user($user_id);
		
		if(trim($user_data['main_email']) != '') wpl_images::text_to_image($user_data['main_email'], '000000', wpl_items::get_path($user_id, 2).'main_email.png');
		if(trim($user_data['secondary_email']) != '') wpl_images::text_to_image($user_data['secondary_email'], '000000', wpl_items::get_path($user_id, 2).'second_email.png');
    }
	
	/**
		@inputs {user_id}
		@description this function will generate rendered data of user and save them into db
		@author Howard
	**/
	public function generate_rendered_data($user_id)
	{
		_wpl_import('libraries.render');
		
		/** get user data **/
		$user_data = (array) wpl_users::get_wpl_user($user_id);
		
		/** location text **/
		$location_text = wpl_users::generate_location_text($user_data);
		$rendered = self::render_profile($user_data, wpl_users::get_plisting_fields());
		
		$result = json_encode(array('rendered'=>$rendered, 'location_text'=>$location_text));
		$query = "UPDATE `#__wpl_users` SET `rendered`='".wpl_db::escape($result)."' WHERE `id`='$user_id'";
		
		/** update **/
		wpl_db::q($query, 'update');
	}
	
	/**
		@inputs [user_data], {user_id} and {glue}
		@return string location_text
		@author Howard
	**/
	public function generate_location_text($user_data, $user_id = 0, $glue = ', ')
	{
		/** fetch user data if user id is setted **/
		if($user_id) $user_data = (array) wpl_users::get_wpl_user($user_id);
		
		$levels = array('location1', 'location2', 'location3', 'location4', 'location5', 'location6', 'location7', 'zip');
		$locations = array();
		
		foreach($levels as $level)
		{
			if(!trim($user_data[$level.'_name'])) continue;
			$locations[] = $user_data[$level.'_name'];
		}
		
		$location_text = implode($glue, array_reverse($locations));
		
		/** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('generate_user_location_text', array('locations'=>$locations)));
		
		return $location_text;
    }
	
	/**
		@inputs {user}
		@return array full render of user
		@description This is a very useful function for rendering whole data of user. you need to just pass user_id and get everything!
		@author Howard
	**/
	public function full_render($user_id, $plisting_fields = NULL)
	{
		/** get plisting fields **/
		if(!$plisting_fields) $plisting_fields = self::get_plisting_fields();
		
		$raw_data = (array) self::get_wpl_user($user_id);
		$profile = (object) $raw_data;
		
		$rendered = json_decode($raw_data['rendered'], true);
		$result = array();
		
		$result['data'] = (array) $profile;
		$result['items'] = wpl_items::get_items($profile->id, '', 2, '', 1);
		$result['raw'] = $raw_data;
		
		/** render data **/
		if($rendered['rendered']) $result['rendered'] = $rendered['rendered'];
		else $result['rendered'] = self::render_profile($profile, $plisting_fields);
		
		/** location text **/
		if($rendered['location_text']) $result['location_text'] = $rendered['location_text'];
		else $result['location_text'] = self::generate_location_text($raw_data);
		
		/** property full link **/
		$result['profile_link'] = self::get_profile_link($profile->id);
		
		/** profile picture **/
		if(trim($raw_data['profile_picture']) != '')
		{
			$result['profile_picture'] = array(
				'url'=>wpl_items::get_folder($profile->id, 2).$raw_data['profile_picture'],
				'path'=>wpl_items::get_path($profile->id, 2).$raw_data['profile_picture'],
				'name'=>$raw_data['profile_picture']
			);
		}
		
		/** company logo **/
		if(trim($raw_data['company_logo']) != '')
		{
			$result['company_logo'] = array(
				'url'=>wpl_items::get_folder($profile->id, 2).$raw_data['company_logo'],
				'path'=>wpl_items::get_path($profile->id, 2).$raw_data['company_logo'],
				'name'=>$raw_data['company_logo']
			);
		}
		
		/** Emails url **/
		if(wpl_file::exists(wpl_items::get_path($profile->id, 2).'main_email.png'))
			$result['main_email_url'] = wpl_items::get_folder($profile->id, 2).'main_email.png';
		
		if(wpl_file::exists(wpl_items::get_path($profile->id, 2).'second_email.png'))
			$result['second_email_url'] = wpl_items::get_folder($profile->id, 2).'second_email.png';
		
		return $result;
	}
	
	/**
		@input string $username, string $password
		@return result
	**/
	public function authenticate($username, $password)
	{
		$wp_auth = wp_authenticate($username, $password);
		$result = array();
		
		if(get_class($wp_auth) == 'WP_User')
		{
			$result['status'] = 1;
			$result['uid'] = $wp_auth->ID;
		}
		else
		{
			$result['status'] = 0;
			$result['uid'] = 0;
		}
		
		return $result;
	}
}