<?php
/**
 * @copyright	Copyright (C) 2011 Rouven Weßling. All rights reserved.
 * @license		GNU General Public License version 2 or later; see license.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.form.formfield');
jimport('joomla.environment.browser');

/**
 * Pseudo form field that displays an image from gravatar.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldGravatar extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Gravatar';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   11.1
	 */
	protected function getInput()
	{
		$browser = JBrowser::getInstance();
		$protocol = $browser->isSSLConnection() ? "https" : "http";
		return '<img src="'.$protocol.'://www.gravatar.com/avatar/'.htmlspecialchars($this->value).'" height="80" width="80" alt="" />';
	}
}
