<?php

/**
 * @file plugins/generic/piwik/PiwikSettingsForm.inc.php
 *
 * Copyright (c) 2013-2019 Simon Fraser University
 * Copyright (c) 2003-2019 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PiwikSettingsForm
 * @ingroup plugins_generic_piwik
 *
 * @brief Form for managers to modify Piwik plugin settings
 */

import('lib.pkp.classes.form.Form');

class PiwikSettingsForm extends Form
{

    /** @var int */
    var $_contextId;

    /** @var object */
    var $_plugin;

    /**
     * Constructor
     * @param $plugin PiwikPlugin
     * @param $contextId int
     */
    function __construct($plugin, $contextId)
    {
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
    function initData()
    {
        $this->_data = array(
            'piwikSiteId' => $this->_plugin->getSetting($this->_contextId, 'piwikSiteId'),
            'piwikUrl' => $this->_plugin->getSetting($this->_contextId, 'piwikUrl'),
            'piwikRequireDSGVO' => $this->_plugin->getSetting($this->_contextId, 'piwikRequireDSGVO'),
            'piwikRequireConsent' => $this->_plugin->getSetting($this->_contextId, 'piwikRequireConsent'),
            'piwikBannerContent' => $this->_plugin->getSetting($this->_contextId, 'piwikBannerContent'),
            'piwikDisableCookies' => $this->_plugin->getSetting($this->_contextId, 'piwikDisableCookies'),
            'piwikLinkColor' => $this->_plugin->getSetting($this->_contextId, 'piwikLinkColor'),
            'piwikCookieTTL' => $this->_plugin->getSetting($this->_contextId, 'piwikCookieTTL'),
            'piwikPosition' => $this->_plugin->getSetting($this->_contextId, 'piwikPosition'),
            'piwikAcceptBtnTxt' => $this->_plugin->getSetting($this->_contextId, 'piwikAcceptBtnTxt'),
            'piwikAcceptBtnColor' => $this->_plugin->getSetting($this->_contextId, 'piwikAcceptBtnColor'),
            'piwikAcceptBtnBGColor' => $this->_plugin->getSetting($this->_contextId, 'piwikAcceptBtnBGColor'),
            'piwikDeclineBtnTxt' => $this->_plugin->getSetting($this->_contextId, 'piwikDeclineBtnTxt'),
            'piwikDeclineBtnColor' => $this->_plugin->getSetting($this->_contextId, 'piwikDeclineBtnColor'),
            'piwikDeclineBtnBGColor' => $this->_plugin->getSetting($this->_contextId, 'piwikDeclineBtnBGColor'),
        );
    }

    /**
     * Assign form data to user-submitted data.
     */
    function readInputData()
    {
        $this->readUserVars(array(
            'piwikSiteId',
            'piwikUrl',
            'piwikRequireDSGVO',
            'piwikRequireConsent',
            'piwikBannerContent',
            'piwikDisableCookies',
            'piwikLinkColor',
            'piwikCookieTTL',
            'piwikPosition',
            'piwikAcceptBtnTxt',
            'piwikAcceptBtnColor',
            'piwikAcceptBtnBGColor',
            'piwikDeclineBtnTxt',
            'piwikDeclineBtnColor',
            'piwikDeclineBtnBGColor'
        ));
    }

    /**
     * Fetch the form.
     * @copydoc Form::fetch()
     */
    function fetch($request)
    {
        $templateMgr = TemplateManager::getManager($request);
        $templateMgr->assign('pluginName', $this->_plugin->getName());
        return parent::fetch($request);
    }

    /**
     * Save settings.
     */
    function execute()
    {
        $this->_plugin->updateSetting($this->_contextId, 'piwikSiteId', $this->getData('piwikSiteId'), 'int');
        $this->_plugin->updateSetting($this->_contextId, 'piwikUrl', trim($this->getData('piwikUrl'), "\"\';"), 'string');
        $this->_plugin->updateSetting($this->_contextId, 'piwikRequireDSGVO', $this->getData('piwikRequireDSGVO'), 'int');
        $this->_plugin->updateSetting($this->_contextId, 'piwikRequireConsent', $this->getData('piwikRequireConsent'), 'int');
        $this->_plugin->updateSetting($this->_contextId, 'piwikBannerContent', $this->getData('piwikBannerContent'), 'string');
        $this->_plugin->updateSetting($this->_contextId, 'piwikDisableCookies', $this->getData('piwikDisableCookies'), 'int');
        $this->_plugin->updateSetting($this->_contextId, 'piwikLinkColor', $this->getData('piwikLinkColor'), 'string');
        $this->_plugin->updateSetting($this->_contextId, 'piwikCookieTTL', $this->getData('piwikCookieTTL'), 'int');
        $this->_plugin->updateSetting($this->_contextId, 'piwikPosition', $this->getData('piwikPosition'), 'string');
        $this->_plugin->updateSetting($this->_contextId, 'piwikAcceptBtnTxt', $this->getData('piwikAcceptBtnTxt'), 'string');
        $this->_plugin->updateSetting($this->_contextId, 'piwikAcceptBtnColor', $this->getData('piwikAcceptBtnColor'), 'string');
        $this->_plugin->updateSetting($this->_contextId, 'piwikAcceptBtnBGColor', $this->getData('piwikAcceptBtnBGColor'), 'string');
        $this->_plugin->updateSetting($this->_contextId, 'piwikDeclineBtnTxt', $this->getData('piwikDeclineBtnTxt'), 'string');
        $this->_plugin->updateSetting($this->_contextId, 'piwikDeclineBtnColor', $this->getData('piwikDeclineBtnColor'), 'string');
        $this->_plugin->updateSetting($this->_contextId, 'piwikDeclineBtnBGColor', $this->getData('piwikDeclineBtnBGColor'), 'string');
    }
}

