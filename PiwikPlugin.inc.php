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
        $piwikUpperText = $this->getSetting($context->getId(), 'piwikUpperText');
        $piwikLowerText = 'Lorem Ipsum';
        $piwikLinkText = 'This is a link';
        $piwikLinkHref = 'https://google.de';
        $piwikLinkcolor = 'red';
        $piwikCookieTTL = 12;
        $piwikPosition = 'top';


        $piwikRelativeUrl = preg_replace('/^https?:/', '', rtrim($piwikUrl, '/')) . '/';
        if (empty($piwikSiteId) || empty($piwikUrl)) return false;

        $contextPath = $context->getPath();
        $templateMgr = TemplateManager::getManager($request);

        $piwikCode = <<< EOF
			var _paq = _paq || [];
			  if({$piwikRequireDSGVO}===1)
			    disableTracking(_paq, {$piwikRequireConsent}===1);
			  _paq.push(['trackPageView']);
			  _paq.push(['enableLinkTracking']);
			  (function() {
			    var u="{$piwikRelativeUrl}";
			    _paq.push(['setTrackerUrl', u+'piwik.php']);
			    _paq.push(['setSiteId', {$piwikSiteId}]);
			    _paq.push(['setDocumentTitle', "{$contextPath}"]);
			    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
			  })();
			  if({$piwikRequireDSGVO}===1)
			    loadBanner("{$piwikUpperText}", "{$piwikLowerText}", "{$piwikLinkText}", "{$piwikLinkHref}", "{$piwikLinkcolor}","{$piwikPosition}", "{$piwikCookieTTL}");
EOF;

        if ($piwikRequireDSGVO == 1)
            $templateMgr->addJavaScript('matomoConsent', $request->getBaseUrl() . '/' . $this->getPluginPath() . '/script/consentGiven.js');
        $templateMgr->addJavaScript(
            'piwik',
            $piwikCode,
            array(
                'priority' => STYLE_SEQUENCE_LAST,
                'inline' => true,
            )
        );

        return false;
    }

}

