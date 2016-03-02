<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	========================================================
	Module: Cookie Decline
	--------------------------------------------------------
	Copyright: Damien Majer
	License: http://creativecommons.org/licenses/by-sa/3.0/
	http://www.damienmajer.com
	--------------------------------------------------------
	This addon may be used free of charge.
	========================================================
	File: upd.cookie_decline.php
	--------------------------------------------------------
	Purpose: Installer/updater for mod.cookie_decline.php
	========================================================
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF
	ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT 
	LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
	FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO 
	EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
	FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN
	AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE 
	OR OTHER DEALINGS IN THE SOFTWARE.
	========================================================
*/

class Cookie_decline_upd {

	var $version = '2.0.0'; 
	var $ext_settings = ''; 
	
	function Cookie_decline_upd()
	{
		$this->EE =& get_instance();
	}
	
	// --------------------------------------------------------------------

	/**
	 * Module Installer
	 *
	 * @access	public
	 * @return	bool
	 */	
	function install()
	{	
		// Load dbforge
		ee()->load->dbforge();
		
		// Add Module
		ee()->db->insert('modules', array(
			'module_name'    => 'Cookie_decline',
			'module_version'     => $this->version,
			'has_cp_backend'   => 'y',
			'has_publish_fields'	=> 'n'
		));

		// Add action
		ee()->db->insert('actions', array(
			'class' => 'Cookie_decline',
			'method' => 'set_cookies_declined',
		));

		// Add action
		ee()->db->insert('exp_actions', array(
			'class' => 'Cookie_decline',
			'method' => 'set_cookies_allowed',
		));			

		// Checks if cookies are allowed before setting them
		ee()->db->insert('extensions', array(
			'class'    => 'Cookie_decline_ext',
			'hook'     => 'set_cookie_end',
			'method'   => 'check_cookie_permission',
			'settings' => $this->ext_settings,
			'priority' => 1,
			'version'  => $this->version,
			'enabled'  => 'y'
		));  		

		return TRUE;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Module Uninstaller
	 *
	 * @access	public
	 * @return	bool
	 */
	function uninstall()
	{
		ee()->load->dbforge();

		$query = ee()->db->query("SELECT module_id FROM exp_modules WHERE module_name = 'Cookie_decline'"); 	
		ee()->db->delete('module_member_groups', array('module_id' => $query->row('module_id')));
		ee()->db->delete('modules', array('module_name' => 'Cookie_decline'));
		ee()->db->delete('actions', array('class' => 'Cookie_decline'));
		
		// Disable extension
		ee()->db->delete('extensions', array('class' => 'Cookie_decline_ext'));

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Module Updater
	 *
	 * @access	public
	 * @return	bool
	 */	
	function update($current='')
	{
		if ($current == '' OR $current == $this->version)
			return FALSE;

		// Load DB Forge
		ee()->load->dbforge();

		if ($current < '1.1')
		{
			// Add action
			ee()->db->insert('actions', array(
				'class' => 'Cookie_decline',
				'method' => 'set_cookies_allowed',
			));	
		}
	}
	
}
/* END Class */

/* End of file upd.cookie_decline.php */
/* Location: ./system/user/addons/cookie_decline/upd.cookie_decline.php */