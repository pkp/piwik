<?php

/**
 * @file PiwikPlugin.php
 *
 * Copyright (c) 2013-2025 Simon Fraser University
 * Copyright (c) 2003-2025 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @class PiwikPlugin
 * @brief Piwik plugin class
 */

namespace APP\plugins\generic\piwik;

use APP\template\TemplateManager;
use PKP\config\Config;
use PKP\core\JSONMessage;
use PKP\linkAction\LinkAction;
use PKP\linkAction\request\AjaxModal;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;

class PiwikPlugin extends GenericPlugin
{
    /**
     * @copydoc Plugin::register()
     */
    public function register($category, $path, $mainContextId = null)
    {
        $success = parent::register($category, $path, $mainContextId);
        if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) {
            return true;
        }
        if ($success && $this->getEnabled()) {
            // Insert Piwik page tag to footer
            Hook::add('TemplateManager::display', [$this, 'registerScript']);
            $this->_registerTemplateResource();
        }
        return $success;
    }

    /**
     * Get the plugin display name.
     */
    public function getDisplayName(): string
    {
        return __('plugins.generic.piwik.displayName');
    }

    /**
     * Get the plugin description.
     */
    public function getDescription(): string
    {
        return __('plugins.generic.piwik.description');
    }

    /**
     * @copydoc Plugin::getActions()
     */
    public function getActions($request, $verb)
    {
        $router = $request->getRouter();
        return array_merge(
            $this->getEnabled() ? [
                new LinkAction(
                    'settings',
                    new AjaxModal(
                        $router->url($request, null, null, 'manage', null, ['verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic']),
                        $this->getDisplayName()
                    ),
                    __('manager.plugins.settings'),
                    null
                ),
            ] : [],
            parent::getActions($request, $verb)
        );
    }

    /**
     * @copydoc Plugin::manage()
     */
    public function manage($args, $request)
    {
        switch ($request->getUserVar('verb')) {
            case 'settings':
                $context = $request->getContext();
                $templateMgr = TemplateManager::getManager($request);
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
     */
    public function registerScript($hookName, $params)
    {
        $request = $this->getRequest();
        $context = $request->getContext();
        if (!$context) {
            return false;
        }
        $router = $request->getRouter();
        if (!is_a($router, 'PKPPageRouter')) {
            return false;
        }

        $piwikSiteId = $this->getSetting($context->getId(), 'piwikSiteId');
        $piwikUrl = $this->getSetting($context->getId(), 'piwikUrl');
        $piwikRelativeUrl = preg_replace('/^https?:/', '', rtrim($piwikUrl, '/')) . '/';
        if (empty($piwikSiteId) || empty($piwikUrl)) {
            return false;
        }

        $contextPath = $context->getPath();

        $piwikCode = <<< EOF
            var _paq = _paq || [];
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
EOF;

        $templateMgr = TemplateManager::getManager($request);
        $templateMgr->addJavaScript(
            'piwik',
            $piwikCode,
            [
                'priority' => TemplateManager::STYLE_SEQUENCE_LAST,
                'inline'   => true,
            ]
        );

        return false;
    }
}
