<?php

/**
 * @file PiwikSettingsForm.inc.php
 *
 * Copyright (c) 2013-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @class PiwikSettingsForm
 * @ingroup plugins_generic_piwik
 *
 * @brief Form for managers to modify Piwik plugin settings
 */

import('lib.pkp.classes.form.Form');

class PiwikSettingsForm extends Form {

	/** @var int */
	var $_contextId;

	/** @var object */
	var $_plugin;

	/**
	 * Constructor
	 * @param $plugin PiwikPlugin
	 * @param $contextId int
	 */
	function __construct($plugin, $contextId) {
		$this->_contextId = $contextId;
		$this->_plugin = $plugin;

		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

		$this->addCheck(new FormValidator($this, 'piwikSiteId', 'required', 'plugins.generic.piwik.manager.settings.piwikSiteIdRequired'));
		$this->addCheck(new FormValidatorUrl($this, 'piwikUrl', 'required', 'plugins.generic.piwik.manager.settings.piwikUrlRequired'));

		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$this->_data = array(
			'piwikSiteId' => $this->_plugin->getSetting($this->_contextId, 'piwikSiteId'),
			'piwikUrl' => $this->_plugin->getSetting($this->_contextId, 'piwikUrl'),
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('piwikSiteId','piwikUrl'));
	}

	/**
	 * Fetch the form.
	 * @copydoc Form::fetch()
	 */
	function fetch($request, $template = null, $display = false) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		return parent::fetch($request, $template, $display);
	}

	/**
	 * Save settings.
	 */
	function execute(...$functionArgs) {
		$this->_plugin->updateSetting($this->_contextId, 'piwikSiteId', $this->getData('piwikSiteId'), 'int');
		$this->_plugin->updateSetting($this->_contextId, 'piwikUrl', trim($this->getData('piwikUrl'), "\"\';"), 'string');
		return parent::execute(...$functionArgs);
	}
}

