<?php

/**
 * @file plugins/generic/piwik/PiwikPlugin.inc.php
 *
 * Copyright (c) 2013-2019 Simon Fraser University
 * Copyright (c) 2003-2019 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PiwikPlugin
 * @ingroup plugins_generic_piwik
 *
 * @brief Piwik plugin class
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class PiwikPlugin extends GenericPlugin
{
    /**
     * @copydoc Plugin::register()
     * @param $category
     * @param $path
     * @param null $mainContextId
     * @return bool
     */
    function register($category, $path, $mainContextId = null)
    {
        $success = parent::register($category, $path, $mainContextId);
        if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return true;
        if ($success && $this->getEnabled()) {
            // Insert Piwik page tag to footer
            HookRegistry::register('TemplateManager::display', array($this, 'registerScript'));
            $this->_registerTemplateResource();
        }
        return $success;
    }

    /**
     * Get the plugin display name.
     * @return string
     */
    function getDisplayName()
    {
        return __('plugins.generic.piwik.displayName');
    }

    /**
     * Get the plugin description.
     * @return string
     */
    function getDescription()
    {
        return __('plugins.generic.piwik.description');
    }

    /**
     * @copydoc Plugin::getActions()
     */
    function getActions($request, $verb)
    {
        $router = $request->getRouter();
        import('lib.pkp.classes.linkAction.request.AjaxModal');
        return array_merge(
            $this->getEnabled() ? array(
                new LinkAction(
                    'settings',
                    new AjaxModal(
                        $router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
                        $this->getDisplayName()
                    ),
                    __('manager.plugins.settings'),
                    null
                ),
            ) : array(),
            parent::getActions($request, $verb)
        );
    }

    /**
     * @copydoc Plugin::manage()
     */
    function manage($args, $request)
    {
        switch ($request->getUserVar('verb')) {
            case 'settings':
                $context = $request->getContext();
                AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON, LOCALE_COMPONENT_PKP_MANAGER);
                $templateMgr = TemplateManager::getManager($request);
                $templateMgr->assign('piwikPositionOptions', [
                    'top' => 'plugins.generic.piwik.manager.settings.piwikPosition.top',
                    'bottom' => 'plugins.generic.piwik.manager.settings.piwikPosition.bottom'
                ]);
                $this->import('PiwikSettingsForm');
                $form = new PiwikSettingsForm($this, $context->getId());
                if ($request->getUserVar('save')) {
                    $form->readInputData();
                    if ($form->validate()) {
                        $form->execute();
                        return new JSONMessage(true);
                    }
                } else {
                    $form->initData();
                }
                return new JSONMessage(true, $form->fetch($request));
        }
        return parent::manage($args, $request);
    }

    /**
     * Register the Piwik script tag
     * @param $hookName string
     * @param $params array
     * @return bool
     */
    function registerScript($hookName, $params)
    {
        $request = $this->getRequest();
        $context = $request->getContext();
        if (!$context) return false;
        $router = $request->getRouter();
        if (!is_a($router, 'PKPPageRouter')) return false;
        $piwikSiteId = $this->getSetting($context->getId(), 'piwikSiteId');
        $piwikUrl = $this->getSetting($context->getId(), 'piwikUrl');
        $piwikRequireDSGVO = $this->getSetting($context->getId(), 'piwikRequireDSGVO') == null ? 0 : $this->getSetting($context->getId(), 'piwikRequireDSGVO');
        $piwikRequireConsent = $this->getSetting($context->getId(), 'piwikRequireConsent') == null ? 0 : $this->getSetting($context->getId(), 'piwikRequireConsent');
        $piwikDisableCookies = $this->getSetting($context->getId(), 'piwikDisableCookies') == null ? 0 : $this->getSetting($context->getId(), 'piwikDisableCookies');
        $piwikBannerContent = $this->getSetting($context->getId(), 'piwikBannerContent');
        $piwikLinkColor = $this->getSetting($context->getId(), 'piwikLinkColor') == null ? '#000' : $this->getSetting($context->getId(), 'piwikLinkColor');
        $piwikCookieTTL = $this->getSetting($context->getId(), 'piwikCookieTTL') == null ? 2 : $this->getSetting($context->getId(), 'piwikCookieTTL');
        $piwikPosition = $this->getSetting($context->getId(), 'piwikPosition') == null ? 'bottom' : $this->getSetting($context->getId(), 'piwikPosition');
        $piwikLinkColor = $this->getSetting($context->getId(), 'piwikLinkColor') == null ? '#000' : $this->getSetting($context->getId(), 'piwikLinkColor');
        $piwikAcceptBtnTxt = $this->getSetting($context->getId(), 'piwikAcceptBtnTxt') == null ? 'Accept Tracking' : $this->getSetting($context->getId(), 'piwikAcceptBtnTxt');
        $piwikAcceptBtnColor = $this->getSetting($context->getId(), 'piwikAcceptBtnColor') == null ? '#fff' : $this->getSetting($context->getId(), 'piwikAcceptBtnColor');
        $piwikAcceptBtnBGColor = $this->getSetting($context->getId(), 'piwikAcceptBtnBGColor') == null ? 'green' : $this->getSetting($context->getId(), 'piwikAcceptBtnBGColor');
        $piwikDeclineBtnTxt = $this->getSetting($context->getId(), 'piwikDeclineBtnTxt') == null ? 'Decline Tracking' : $this->getSetting($context->getId(), 'piwikDeclineBtnTxt');
        $piwikDeclineBtnColor = $this->getSetting($context->getId(), 'piwikDeclineBtnColor') == null ? '#fff' : $this->getSetting($context->getId(), 'piwikDeclineBtnColor');
        $piwikDeclineBtnBGColor = $this->getSetting($context->getId(), 'piwikDeclineBtnBGColor') == null ? 'green' : $this->getSetting($context->getId(), 'piwikDeclineBtnBGColor');
        $piwikRelativeUrl = preg_replace('/^https?:/', '', rtrim($piwikUrl, '/')) . '/';
        if (empty($piwikSiteId) || empty($piwikUrl)) return false;
        $contextPath = $context->getPath();
        $templateMgr = TemplateManager::getManager($request);
        $mtmSettings = "var mtm_setting = {'requireDSGVO':'{$piwikRequireDSGVO}','requireConsent':'{$piwikRequireConsent}','bannerContent':'{$piwikBannerContent}','relativeUrl':'{$piwikRelativeUrl}','siteId':'{$piwikSiteId}','contextPath':'{$contextPath}','linkColor':'{$piwikLinkColor}','position':'{$piwikPosition}','cookieTTL':'{$piwikCookieTTL}','disableCookies':'{$piwikDisableCookies}','acceptBtnTxt':'{$piwikAcceptBtnTxt}','acceptBtnColor':'{$piwikAcceptBtnColor}','acceptBtnBGColor':'{$piwikAcceptBtnBGColor}','declineBtnTxt':'{$piwikDeclineBtnTxt}','declineBtnColor':'{$piwikDeclineBtnColor}','declineBtnBGColor':'{$piwikDeclineBtnBGColor}'}";
        $templateMgr->addJavaScript('piwik', $mtmSettings, array('priority' => STYLE_SEQUENCE_LAST, 'inline' => true,));
        $templateMgr->addJavaScript('matomoConsent', $request->getBaseUrl() . '/' . $this->getPluginPath() . '/script/consentGiven.min.js', array('priority' => STYLE_SEQUENCE_LAST));
        return false;
    }

}

