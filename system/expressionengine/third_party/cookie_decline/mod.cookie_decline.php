<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
	File: mod.cookie_decline.php
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

class Cookie_decline {

	var $return_data = '';	 	// Final data	


	/**
	  * Constructor
	  */
	public function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();

	}

	// --------------------------------------------------------------------

	/**
	 * Create cookies declined link
	 *
	 * @access	public
	 * @return	string
	 *
	 */
	public function decline_link()
	{
		$link = $this->EE->functions->fetch_site_index(0, 0).QUERY_MARKER.'ACT='
			.$this->EE->functions->fetch_action_id('Cookie_decline', 'set_cookies_declined');

		$link .= AMP.'RET='.$this->EE->uri->uri_string();	
		
		return $link;		
	}	

	// --------------------------------------------------------------------

	/**
	 * Set the 'cookies_declined' cookie
	 *
	 * @access	public
	 * @return	string
	 *
	 */
	public function set_cookies_declined()
	{
		$this->EE->lang->loadfile('cookie_decline'); 
		
		$prefix = ( ! $this->EE->config->item('cookie_prefix')) ? 'exp_' : $this->EE->config->item('cookie_prefix').'_';
		$expire = time() - 86500;

		// Load cookie helper
		$this->EE->load->helper('cookie');
		$prefix = ( ! $this->EE->config->item('cookie_prefix')) ? 
			'exp_' : $this->EE->config->item('cookie_prefix').'_';
		$prefix_length = strlen($prefix);

		foreach($_COOKIE as $name => $value)
		{
			// Is it an EE cookie?
			// Use Functions method so cookie properties properly set
			if (strncmp($name, $prefix, $prefix_length) == 0)
			{
				$this->EE->functions->set_cookie(substr($name, $prefix_length));
			}
			else
			{
                $pieces = parse_url($_SERVER['HTTP_HOST']);
				$domain = isset($pieces['host']) ? $pieces['host'] : '';
				if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
				    $hostname = '.'.$regs['domain'];
				}
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/'); 
				setcookie($name, '', time()-1000, '/', $hostname);
			}
		}
		
		$expires = 60*60*24*365;  // 1 year

		$this->EE->functions->set_cookie('cookies_declined', 'y', $expires);

		$ret = ($this->EE->input->get('RET')) ? $this->EE->input->get('RET') : '';
		$return_link = $this->EE->functions->create_url($ret);

		// Send them a success message and redirect link
		$data = array(
			'title' 	=> lang('cookies_declined'),
			'heading'	=> lang('cookies_declined'),
			'content'	=> lang('cookies_declined_description'),
			'redirect'	=> $return_link,
			'link'		=> array($return_link, lang('cookies_declined_return_to_page')),
			'rate'		=> 3
		);

		$this->EE->output->show_message($data);		
	}
	
	
	public function check_consent()
	{ 		
		if ($this->EE->input->cookie('cookies_allowed') == 'y' || $this->EE->input->cookie('cookies_declined') != 'y')
		{
		
			$this->return_data = $this->EE->TMPL->tagdata;
			
		} else {
			
			$this->return_data = "";
			
		}
		
		return $this->return_data;
	}
	
	
	public function add_modal()
	{ 
		if ($this->EE->input->cookie('cookies_allowed') != 'y' && $this->EE->input->cookie('cookies_declined') != 'y')
		{
			$this->EE->lang->loadfile('cookie_decline'); 
			
			$link = $this->EE->functions->fetch_site_index(0, 0).QUERY_MARKER.'ACT='.$this->EE->functions->fetch_action_id('Cookie_decline', 'set_cookies_declined');			
			$link .= AMP.'RET=';  
			$this->return_data .= "<link rel='stylesheet' media='all' href='/themes/third_party/cookie_decline/css/cookie_decline.css'>";
			$this->EE->db->select('settings');
	    	$this->EE->db->where('class', 'Cookie_decline_ext');
	    	$query = $this->EE->db->get('extensions');
			$message = "";
            if ($query->num_rows() > 0)
			{
				$row = $query->row();
				$settings = unserialize(base64_decode($row->settings));
				$message = $settings['cp_cookie_message'];
				if($message == "")
				{
					$message .= $this->EE->lang->line('cookie_decline_message');   
				}
            } else {
	           $message .= $this->EE->lang->line('cookie_decline_message');
			}
			$this->return_data .= "<script>
							   function init() {
							       $('body').append('<div id=\"cookie_decline\"><p><strong>Cookie Policy</strong></p><p>".$message."</p><p><a href=\"".$link."\">Disallow cookies</a></div>');
							       $('#cookie_decline').css({'bottom':'-'+$(this).height()}).animate({
    								   bottom: '30px'
  								   }, 2000).delay(8000).animate({
    								   bottom: '-'+$(this).height()+'px'
  								   }, 2000);
							   }
                               window.onload = init;
                               </script>";
        }
		
		return $this->return_data;
	}

}

// END CLASS

/* End of file mod.cookie_decline.php */
/* Location: ./system/expressionengine/third_party/cookie_decline/mod.cookie_decline.php */