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
		$link = ee()->functions->fetch_site_index(0, 0).QUERY_MARKER.'ACT='
			.ee()->functions->fetch_action_id('Cookie_decline', 'set_cookies_declined');

		$link .= AMP.'RET='.ee()->uri->uri_string();	
		
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
		ee()->lang->loadfile('cookie_decline'); 
		
		$prefix = ( ! ee()->config->item('cookie_prefix')) ? 'exp_' : ee()->config->item('cookie_prefix').'_';
		$expire = time() - 86500;

		// Load cookie helper
		ee()->load->helper('cookie');
		$prefix = ( ! ee()->config->item('cookie_prefix')) ? 
			'exp_' : ee()->config->item('cookie_prefix').'_';
		$prefix_length = strlen($prefix);

		foreach($_COOKIE as $name => $value)
		{
			// Is it an EE cookie?
			// Use Functions method so cookie properties properly set
			if (strncmp($name, $prefix, $prefix_length) == 0)
			{
				ee()->input->set_cookie(substr($name, $prefix_length));
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

		ee()->input->set_cookie('cookies_declined', 'y', $expires);

		$ret = (ee()->input->get('RET')) ? ee()->input->get('RET') : '';
		$return_link = ee()->functions->create_url($ret);

		// Send them a success message and redirect link
		$data = array(
			'title' 	=> lang('cookies_declined'),
			'heading'	=> lang('cookies_declined'),
			'content'	=> lang('cookies_declined_description'),
			'redirect'	=> $return_link,
			'link'		=> array($return_link, lang('cookies_declined_return_to_page')),
			'rate'		=> 3
		);

		ee()->output->show_message($data);		
	}


	// --------------------------------------------------------------------

	/**
	 * Create cookies allowed link
	 *
	 * @access	public
	 * @return	string
	 *
	 */
	public function allow_link()
	{
		$link = ee()->functions->fetch_site_index(0, 0).QUERY_MARKER.'ACT='
			.ee()->functions->fetch_action_id('Cookie_decline', 'set_cookies_allowed');

		$link .= AMP.'RET='.ee()->uri->uri_string();	
		
		return $link;		
	}


	// --------------------------------------------------------------------

	/**
	 * Set the 'cookies_allowed' cookie
	 *
	 * @access	public
	 * @return	string
	 *
	 */
	public function set_cookies_allowed()
	{
		ee()->lang->loadfile('cookie_decline');

		// Load cookie helper
		ee()->load->helper('cookie');
		$prefix = ( ! ee()->config->item('cookie_prefix')) ? 
			'exp_' : ee()->config->item('cookie_prefix').'_';
		$prefix_length = strlen($prefix);

		setcookie("exp_cookies_declined", "", time()-3600);

		ee()->input->set_cookie('cookies_allowed', 'y', $expires);

		$ret = (ee()->input->get('RET')) ? ee()->input->get('RET') : '';
		$return_link = ee()->functions->create_url($ret);

		// Send them a success message and redirect link
		$data = array(
			'title' 	=> lang('cookies_allowed'),
			'heading'	=> lang('cookies_allowed'),
			'content'	=> lang('cookies_allowed_description'),
			'redirect'	=> $return_link,
			'link'		=> array($return_link, lang('cookies_declined_return_to_page')),
			'rate'		=> 3
		);

		ee()->output->show_message($data);
	}
	
	
	public function check_consent()
	{ 		
		if (ee()->input->cookie('cookies_allowed') == 'y' || ee()->input->cookie('cookies_declined') != 'y')
		{
		
			$this->return_data = ee()->TMPL->tagdata;
			
		} else {
			
			$this->return_data = "";
			
		}
		
		return $this->return_data;
	}


	public function check_declined()
	{ 		
		if (ee()->input->cookie('cookies_declined') == 'y')
		{
		
			$this->return_data = ee()->TMPL->tagdata;
			
		} else {
			
			$this->return_data = "";
			
		}
		
		return $this->return_data;
	}
	
	
	public function add_modal()
	{ 
		if (ee()->input->cookie('cookies_allowed') != 'y' && ee()->input->cookie('cookies_declined') != 'y')
		{
			ee()->lang->loadfile('cookie_decline'); 
			
			$link = ee()->functions->fetch_site_index(0, 0).QUERY_MARKER.'ACT='.ee()->functions->fetch_action_id('Cookie_decline', 'set_cookies_declined');			
			$link .= AMP.'RET=';  
			$this->return_data .= "<link rel='stylesheet' media='all' href='/themes/user/addons/cookie_decline/css/cookie_decline.css'>";
			ee()->db->select('settings');
	    	ee()->db->where('class', 'Cookie_decline_ext');
	    	$query = ee()->db->get('extensions');
			$message = "";
            if ($query->num_rows() > 0)
			{
				$row = $query->row();
				$settings = unserialize(base64_decode($row->settings));
				$message = $settings['cp_cookie_message'];
				if($message == "")
				{
					$message .= ee()->lang->line('cookie_decline_message');   
				}
            } else {
	           $message .= ee()->lang->line('cookie_decline_message');
			}
			$this->return_data .= "<script>
							   function init() {
							       $('body').append('<div id=\"cookie_decline\"><p><strong>Cookie Policy</strong></p><p>".$message."</p><p><a href=\"".$link."\">Disallow cookies</a><a href=\"#\" class=\"cd_close\"></a></div>');
							       $('.cd_close').click(function(e){
							       		$('#cookie_decline').remove();
							       		e.preventDefault();
							       })
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
/* Location: ./system/user/addons/cookie_decline/mod.cookie_decline.php */