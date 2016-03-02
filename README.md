#Cookie Decline

* Author: [Damien Majer](http://www.damienmajer.com/)

## Version 2.0.0

Opt-out alternative to the Cookie Consent module.

## Description

The Cookie Consent module by Ellislab disables ExpressionEngine’s native cookies by default and requires visitors to explicitly consent to the dropping of cookies by clicking a link which, as it has been proven, most don’t do. With the eleventh hour u-turn by the ICO deciding that UK webite owners actually only require “implied consent” from their visitors I felt the need for an alternative to the Cookie Consent module that enables cookies in EE by default and provides a method for notifying vistors that cookies are in use, the ability to disable cookies and a method for showing/hiding content or including/excluding assets based on visitor cookie preferences.

A huge thankyou to Ellislabs’s Robin Sowell for his assistance and ideas on getting this module to work. Without him it never would have happened.

## Installation

1. Copy the cookie_decline addon folder to ./system/user/addons/
1. Copy the cookie_decline themes folder to ./themes/user/addons/
3. In EE, browse to Developer Tools > Add-On Manager and click the 'Install' link for the Cookie Decline add-on

Once installed EE will continue to set cookies by default.

## Updating

Replace all files and click into the module settings to update.

## Optional Template Tags

### Decline Link Tag

The following tag outputs a URL for use in a link that allows visitors to disable cookies. This will only disable EE's native cookies.

	{exp:cookie_decline:decline_link}

An example of how you might use this tag:

	<a href="{exp:cookie_decline:decline_link}">Disallow Cookies</a>

You might want ot use this in your Cookie or Privacy Policy for example.

### Allow Link Tag

The following tag outputs a URL for use in a link that allows visitors to enable cookies if previously disabled.

	{exp:cookie_decline:allow_link}

An example of how you might use this tag:

	<a href="{exp:cookie_decline:allow_link}">Allow Cookies</a>

You might want ot use this in conjunction with the Check Declined tag pair to only show the allow link when cookies have been disabled, lke so:

	{exp:cookie_decline:check_declined}<a href="{exp:cookie_decline:allow_link}">Allow Cookies</a>{/exp:cookie_decline:check_declined}

### Check Consent Tag

This tag pair is used for wrapping around content/assets you wish to display/include dependant whether cookie dropping is allowed. This could be a chunk of content or assets that set cookies themselves that you want to include, only if cookies haven’t been declined, such as the Google Analytics tracking code for example.

Example usage:

	{exp:cookie_decline:check_consent}Display this when cookies haven’t been declined{/exp:cookie_decline:check_consent}

### Check Delined Tag

This tag pair does the opposite to the Check Consent tag pair and is used to only display content contained within when cookies have been disabled.

Example usage:

	{exp:cookie_decline:check_declined}Display this when cookies have been declined{/exp:cookie_decline:check_declined}

### Add Modal Tag

Adding this tag in the head of your web pages will generate a small modal window at the bottom right of the screen that informs visitors the first time they visit your site that cookies are in use and provides them with a link to decline cookies should they so wish. The modal has a default message which can be replaced in the Module’s settings.

Insert this into the head of your web pages:

	{exp:cookie_decline:add_modal}

The styles for the modal can be updated in the stylesheet in the module’s theme folder.

## Version Notes

### Version 2.0.0

Upgraded for ExpressionEngine 3

### Version 1.1

Added Check Declined tag pair

Added Allow Link tag

Added close button to notification modal

Removed uneccessary settings link for extension in CP