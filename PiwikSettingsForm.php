<?php

/**
 * @file PiwikSettingsForm.php
 *
 * Copyright (c) 2013-2025 Simon Fraser University
 * Copyright (c) 2003-2025 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @class PiwikSettingsForm
 * @brief Form for managers to modify Piwik plugin settings
 */

namespace APP\plugins\generic\piwik;

use APP\template\TemplateManager;
use PKP\form\Form;
use PKP\form\validation\FormValidator;
use PKP\form\validation\FormValidatorCSRF;
use PKP\form\validation\FormValidatorPost;
use PKP\form\validation\FormValidatorUrl;

class PiwikSettingsForm extends Form
{
    protected int $contextId;
    protected PiwikPlugin $plugin;

    /**
     * Constructor
     * @param $plugin PiwikPlugin
     * @param $contextId int
     */
    public function __construct($plugin, $contextId)
    {
        $this->contextId = $contextId;
        $this->plugin = $plugin;

        parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

        $this->addCheck(new FormValidator($this, 'piwikSiteId', 'required', 'plugins.generic.piwik.manager.settings.piwikSiteIdRequired'));
        $this->addCheck(new FormValidatorUrl($this, 'piwikUrl', 'required', 'plugins.generic.piwik.manager.settings.piwikUrlRequired'));

        $this->addCheck(new FormValidatorPost($this));
        $this->addCheck(new FormValidatorCSRF($this));
    }

    /**
     * Initialize form data.
     */
    public function initData()
    {
        $this->_data = array(
            'piwikSiteId' => $this->plugin->getSetting($this->contextId, 'piwikSiteId'),
            'piwikUrl' => $this->plugin->getSetting($this->contextId, 'piwikUrl'),
        );
    }

    /**
     * Assign form data to user-submitted data.
     */
    public function readInputData()
    {
        $this->readUserVars(array('piwikSiteId','piwikUrl'));
    }

    /**
     * Fetch the form.
     * @copydoc Form::fetch()
     */
    public function fetch($request, $template = null, $display = false)
    {
        $templateMgr = TemplateManager::getManager($request);
        $templateMgr->assign('pluginName', $this->plugin->getName());
        return parent::fetch($request, $template, $display);
    }

    /**
     * Save settings.
     */
    public function execute(...$functionArgs)
    {
        $this->plugin->updateSetting($this->contextId, 'piwikSiteId', $this->getData('piwikSiteId'), 'int');
        $this->plugin->updateSetting($this->contextId, 'piwikUrl', trim($this->getData('piwikUrl'), "\"\';"), 'string');
        return parent::execute(...$functionArgs);
    }
}
