#Cookie Decline

* Author: [Damien Majer](http://www.damienmajer.com/)

## Version 1.0

Opt-out alternative to the Cookie Consent module.

## Description

The Cookie Consent module by Ellislab disables ExpressionEngine’s native cookies by default and requires visitors to explicitly consent to the dropping of cookies by clicking a link which, as it has been proven, most don’t do. With the eleventh hour u-turn by the ICO deciding that UK webite owners actually only require “implied consent” from their visitors I felt the need for an alternative to the Cookie Consent module that enables cookies in EE by default and provides a method for notifying vistors that cookies are in use, the ability to disable cookies and a method for showing/hiding content or including/excluding assets based on visitor cookie preferences.

A huge thankyou to Ellislabs’s Robin Sowell for his assistance and ideas on getting this module to work. Without him it never would have happened.

## Installation

1. Copy the cookie_decline addon folder to ./system/expressionengine/third_party/
1. Copy the cookie_decline themes folder to ./themes/third_party/
3. In EE, browse to Add-ons > Modules and click the 'Install' link for the Cookie Decline module and install both the module and the extension

Once installed EE will continue to set cookies by default.

## Optional Template Tags

### Decline Link Tag

The following tag outputs a URL for use in a link that allows visitors to disable cookies. This will only disable EE's native cookies.

	{exp:cookie_decline:decline_link}

An example of how you might use this tag:

	<a href="{exp:cookie_decline:decline_link}">Disallow Cookies</a>

You might want ot use this in your Cookie or Privacy Policy for example.

### Check Consent Tag

This tag pair is used for wrapping around content/assets you wish to display/include dependant on whether or not cookie dropping is allowed. This could be a chuck of content or assets that set cookies themselves that you want to include, only if cookies haven’t been declined, such as the Google Analytics tracking code for example.

Example usage:

	{exp:cookie_decline:check_consent}Display this when cookies haven’t been declined{/exp:cookie_decline:check_consent}

### Add Modal Tag

Adding this tag in the head of your web pages will generate a small modal window at the bottom right of the screen that informs visitors the first time they visit your site that cookies are in use and provides them with a link to decline cookies should they so wish. The modal has a default message which can be replaced in the Module’s settings.

Insert this into the head of your web pages:

	{exp:cookie_decline:add_modal}

The styles for the modal can be updated in the stylesheet in the module’s theme folder.