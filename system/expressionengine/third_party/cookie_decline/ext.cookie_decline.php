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
	File: ext.cookie_decline.php
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

class Cookie_decline_ext {

	public $name = 'Cookie Decline';
	public $version = '1.0';
	public $settings_exist = 'y';
	public $docs_url = 'https://github.com/damienmajer/CookieDecline';
	public $settings = array();

	private $EE;
	
	/**
	 * Constructor
	 *
	 * @access	public
	 */

	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks if cookies are declined- if not, EE cookies are set
	 *
	 * @access	public
	 * @param	array $data Associative array containing five different keys and values:
	 * 			- prefix: exp_ or as specified by config
	 * 			- name: cookie name without prefix appended
	 * 			- value: cookie value after stripslashes()
	 * 			- expire: expiration
	 * 			- domain: as specified in the config		
	 * 			- secure_cookie: 1 or 0, based on configs secure_cookie setting		
	 * @return	mixed Returns nothing if cookies are declined, ends script and returns
	 * 			FALSE otherwise EE cookies are set
	 *
	 */
	public function check_cookie_permission($data)
	{
		//We'll take over all cookie setting
		$this->EE->extensions->end_script = TRUE;

		if ($this->EE->input->cookie('cookies_declined') == 'y')
		{
			return;
		}

		// Cookies are not declined, so set them.

		if ( ! $this->EE->input->cookie('cookies_allowed') && $data['name'] != 'cookies_allowed')
		{
			$exp = time() + 60*60*24*365;  // 1 year
			setcookie($data['prefix'].'cookies_allowed','y', $exp, $data['path'], $data['domain'], $data['secure_cookie']);			
		}

		setcookie($data['prefix'].$data['name'], $data['value'], $data['expire'], $data['path'], $data['domain'], $data['secure_cookie']);
	}
	
	// --------------------------------------------------------------------
	
	/**
 	* Settings
 	*
 	* @param   Array   Settings
 	* @return  void
 	*/
	function settings_form($current)
	{
		// Let's just send them to the module's page
        $this->EE->functions->redirect(
            BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=cookie_decline'
    	);		
	}


	/**
 	* Activate Extension
 	*
 	* This function enters the extension into the exp_extensions table
 	*
 	* @see http://codeigniter.com/user_guide/database/index.html for
 	* more information on the db class.
 	*
 	* @return void
 	*/
	function activate_extension()
	{
		return TRUE;
	}
	
}

/* End of file ext.cookie_decline.php */
/* Location: ./system/expressionengine/third_party/cookie_decline/ext.cookie_decline.php */