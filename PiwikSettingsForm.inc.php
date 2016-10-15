<?php

/**
 * @file plugins/generic/piwik/PiwikSettingsForm.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PiwikSettingsForm
 * @ingroup plugins_generic_piwik
 *
 * @brief Form for journal managers to modify Piwik plugin settings
 */

import('lib.pkp.classes.form.Form');

class PiwikSettingsForm extends Form {

	/** @var int */
	var $_journalId;

	/** @var object */
	var $_plugin;

	/**
	 * Constructor
	 * @param $plugin PiwikPlugin
	 * @param $journalId int
	 */
	function PiwikSettingsForm($plugin, $journalId) {
		$this->_journalId = $journalId;
		$this->_plugin = $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');

		$this->addCheck(new FormValidator($this, 'piwikSiteId', 'required', 'plugins.generic.piwik.manager.settings.piwikSiteIdRequired'));
		$this->addCheck(new FormValidator($this, 'piwikUrl', 'required', 'plugins.generic.piwik.manager.settings.piwikUrlRequired'));

		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$this->_data = array(
			'piwikSiteId' => $this->_plugin->getSetting($this->_journalId, 'piwikSiteId'),
			'piwikUrl' => $this->_plugin->getSetting($this->_journalId, 'piwikUrl'),
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
	function fetch($request) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		return parent::fetch($request);
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$this->_plugin->updateSetting($this->_journalId, 'piwikSiteId', trim($this->getData('piwikSiteId'), "\"\';"), 'string');
		$this->_plugin->updateSetting($this->_journalId, 'piwikUrl', trim($this->getData('piwikUrl'), "\"\';"), 'string');
	}
}

?>
