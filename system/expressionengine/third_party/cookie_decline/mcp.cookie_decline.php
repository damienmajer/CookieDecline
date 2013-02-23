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
		$vars = array();
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('cookie_decline_module_name'));

		$vars['cookie_message'] = $this->EE->lang->line('cookie_decline_message');
		
    	$this->EE->db->select('settings');
    	$this->EE->db->where('class', 'Cookie_decline_ext');
    	$query = $this->EE->db->get('extensions');

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$settings = unserialize(base64_decode($row->settings));
			$vars['cookie_message'] = $settings['cp_cookie_message'];	
		}
		
		return $this->EE->load->view('index', $vars, TRUE);
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
				
    	$this->EE->db->where('class', 'Cookie_decline_ext');
    	$this->EE->db->update('extensions', array('settings' => base64_encode(serialize($settings))));

    	$this->EE->session->set_flashdata('message_success', lang('preferences_updated'));

        $this->EE->functions->redirect(
            BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=cookie_decline'
    		);		
	
	}
}
// End Cookie_decline CP Class

/* End of file mcp.cookie_decline.php */
/* Location: ./system/expressionengine/third_party/cookie_decline/mcp.cookie_decline.php */