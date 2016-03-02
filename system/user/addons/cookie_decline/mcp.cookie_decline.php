<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
	File: mcp.cookie_decline.php
	--------------------------------------------------------
	Purpose: Opt-out alternative to the Cookie Consent module
	         for use with lower priorty cookies where implied
	         consent may be suffice. It is entirely the 
	         responsibility of the site owener/developer to 
	         decide whether this solution is inline with the 
	         privacy and cookie legislations that affect the
	         site in question and is completely dependant on
	         the types of cookies being used.
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

class Cookie_decline_mcp {

	var $version = '2.0.0';

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function Cookie_decline_mcp()
	{
		$this->EE =& get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Module Index Page
	 *
	 * @access	public
	 * @return	string Parsed index view file
	 */	
	public function index()
	{ 
		ee()->load->library('form_validation');

		$vars = array();

		// The data we'll want to populate our form fields with
		ee()->db->select('settings');
    	ee()->db->where('class', 'Cookie_decline_ext');
    	$query = ee()->db->get('extensions');

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$settings = unserialize(base64_decode($row->settings));
			$vars['cookie_message'] = $settings['cp_cookie_message'];	
		}

		// No message save in the extension settings
		if(!isset($vars['cookie_message'])) {
			$vars['cookie_message'] = lang('cookie_decline_message');
		}

		// Form definition array
		$vars['sections'] = array(
		  array(
		    array(
		      'title' => lang('cp_cookie_message_label'),
		      'fields' => array(
		        'cp_cookie_message' => array(
		          'type' => 'text',
		          'value' => $vars['cookie_message'],
		          'required' => TRUE
		        )
		      )
		    )
		  )
		);

		// Final view variables we need to render the form
		$vars += array(
		  'base_url' => ee('CP/URL')->make('addons/settings/cookie_decline/save_ext_settings'),
		  'cp_page_title' => lang('general_settings'),
		  'save_btn_text' => 'btn_save_settings',
		  'save_btn_text_working' => 'btn_saving'
		);  

		return ee('View')->make('cookie_decline:index')->render($vars);  

	}

	// --------------------------------------------------------------------

	/**
	 * Save the extension settings
	 *
	 * @access	public
	 * @return	void
	 */	
	public function save_ext_settings()
	{
		$settings['cp_cookie_message'] = $this->EE->input->post('cp_cookie_message');
				
    	ee()->db->where('class', 'Cookie_decline_ext');
    	ee()->db->update('extensions', array('settings' => base64_encode(serialize($settings))));

    	ee('CP/Alert')->makeInline('shared-form')
					->asSuccess()
					->withTitle(lang('success'))
					->addToBody(lang('cookie_decline_settings_updated'))
					->defer();
    	ee()->functions->redirect( ee('CP/URL')->make('addons/settings/cookie_decline') );	
	
	}
}
// End Cookie_decline CP Class

/* End of file mcp.cookie_decline.php */
/* Location: ./system/user/addons/cookie_decline/mcp.cookie_decline.php */