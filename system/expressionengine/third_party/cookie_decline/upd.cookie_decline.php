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

	var $version = '1.0'; 
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
		
		// Add Module
		$this->EE->db->insert('modules', array(
			'module_name'    => 'Cookie_decline',
			'module_version'     => $this->version,
			'has_cp_backend'   => 'y'
		));

		// Add action
		$this->EE->db->insert('exp_actions', array(
			'class' => 'Cookie_decline',
			'method' => 'set_cookies_declined',
		));		

		// Checks if cookies are allowed before setting them
		$this->EE->db->insert('extensions', array(
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
		$query = $this->EE->db->query("SELECT module_id FROM exp_modules WHERE module_name = 'Cookie_decline'"); 
				
		$this->EE->db->delete('module_member_groups', array('module_id' => $query->row('module_id')));
		$this->EE->db->delete('modules', array('module_name' => 'Cookie_decline'));
		$this->EE->db->delete('actions', array('class' => 'Cookie_decline'));
		
		
		// Disable extension
		$this->EE->db->delete('extensions', array('class' => 'Cookie_decline_ext'));

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
		return TRUE;
	}
	
}
/* END Class */

/* End of file upd.cookie_decline.php */
/* Location: ./system/expressionengine/third_party/cookie_decline/upd.cookie_decline.php */