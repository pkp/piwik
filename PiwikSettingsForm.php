<?php

/**
 * @file PiwikSettingsForm.php
 *
 * Copyright (c) 2013-2023 Simon Fraser University
 * Copyright (c) 2003-2023 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @class PiwikSettingsForm
 * @brief Form for managers to modify Piwik plugin settings
 */

namespace APP\plugins\generic\piwik;

use PKP\form\Form;
use APP\template\TemplateManager;

class PiwikSettingsForm extends Form {

    protected int $_contextId;
    protected PiwikPlugin $_plugin;

    /**
     * Constructor
     * @param $plugin PiwikPlugin
     * @param $contextId int
     */
    function __construct($plugin, $contextId) {
        $this->_contextId = $contextId;
        $this->_plugin = $plugin;

        parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

        $this->addCheck(new \PKP\form\validation\FormValidator($this, 'piwikSiteId', 'required', 'plugins.generic.piwik.manager.settings.piwikSiteIdRequired'));
        $this->addCheck(new \PKP\form\validation\FormValidatorUrl($this, 'piwikUrl', 'required', 'plugins.generic.piwik.manager.settings.piwikUrlRequired'));

        $this->addCheck(new \PKP\form\validation\FormValidatorPost($this));
        $this->addCheck(new \PKP\form\validation\FormValidatorCSRF($this));
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

