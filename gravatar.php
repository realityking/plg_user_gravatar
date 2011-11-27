<?php
/**
 * @copyright	Copyright (C) 2011 Rouven Weßling. All rights reserved.
 * @license		GNU General Public License version 2 or later; see license.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('joomla.environment.browser');

/**
 * Gravatar plugin.
 *
 */
class plgUserGravatar extends JPlugin
{
	private static $forms = array('com_users.profile', 'com_users.user', 'com_admin.profile')
	
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @param	string	$context	The context for the data
	 * @param	int		$data		The user id
	 * @param	object
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, self::$forms)) {
			return true;
		}

		if (is_object($data)) {
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->gravatar) and $userId > 0) {	
				// Add the gravatar data.
				$data->gravatar = array();
				$data->gravatar['avatar'] = md5(strtolower(trim($data->email)));
			}

			if (!JHtml::isRegistered('users.gravatar')) {
				JHtml::register('users.gravatar', array(__CLASS__, 'gravatar'));
			}
		}

		return true;
	}

	public static function gravatar($value)
	{
		$browser = JBrowser::getInstance();
		$protocol = $browser->isSSLConnection() ? "https" : "http";
		return '<img src="'.$protocol.'://www.gravatar.com/avatar/'.htmlspecialchars($value).'" height="80" width="80" alt="" />';
	}

	/**
	 * @param	JForm	$form	The form to be altered.
	 * @param	array	$data	The associated data for the form.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm)) {
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Check we are manipulating a valid form.
		if (!in_array($form->getName(), self::$forms)) {
			return true;
		}

		// Add the fields to the form.
		JForm::addFieldPath(dirname(__FILE__).'/fields');
		JForm::addFormPath(dirname(__FILE__).'/forms');
		$form->loadFile('gravatar', false);

		return true;
	}
}
