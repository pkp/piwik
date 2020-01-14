{**
 * plugins/generic/piwik/templates/settingsForm.tpl
 *
 * Copyright (c) 2013-2019 Simon Fraser University
 * Copyright (c) 2003-2019 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Piwik plugin settings
 *
 *}
<div id="piwikSettings">
    <div id="description">{translate key="plugins.generic.piwik.manager.settings.description"}</div>

    <div class="separator"></div>

    <br/>

    <script>
        $(function () {ldelim}
            // Attach the form handler.
            $('#piwikSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
            {rdelim});
    </script>
    <form class="pkp_form" id="piwikSettingsForm" method="post"
          action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}">
        {csrf}

        {fbvFormArea id="piwikSettingsFormArea"}
            {fbvFormSection for="piwikUrl" title="plugins.generic.piwik.manager.settings.piwikUrl" description="plugins.generic.piwik.manager.settings.piwikUrlInstructions"}
                {fbvElement type="text" id="piwikUrl" name="piwikUrl" value=$piwikUrl label="plugins.generic.piwik.manager.settings.piwikUrl" required=true}
            {/fbvFormSection}

            {fbvFormSection for="piwikSiteId" title="plugins.generic.piwik.manager.settings.piwikSiteId" description="plugins.generic.piwik.manager.settings.piwikSiteIdInstructions"}
                {fbvElement type="text" id="piwikSiteId" name="piwikSiteId" value=$piwikSiteId label="plugins.generic.piwik.manager.settings.piwikSiteId" required=true}
            {/fbvFormSection}
        {/fbvFormArea}
        {fbvFormArea id="piwikDsgvoSettings" title="plugins.generic.piwik.manager.settings.piwikDsgvoSettings"}
            {fbvFormSection for="piwikRequireDSGVO" list=true title="plugins.generic.piwik.manager.settings.piwikRequireDSGVO" description="plugins.generic.piwik.manager.settings.piwikRequireDSGVO.desc"}
                {fbvElement type="checkbox" id="piwikRequireDSGVO" name="piwikRequireDSGVO" value="1" checked=$piwikRequireDSGVO label="plugins.generic.piwik.manager.settings.piwikRequireDSGVO"}
            {/fbvFormSection}
            {fbvFormSection for="piwikRequireConsent" list=true title="plugins.generic.piwik.manager.settings.piwikRequireConsent" description="plugins.generic.piwik.manager.settings.piwikRequireConsent.desc"}
                {fbvElement type="checkbox" id="piwikRequireConsent" name="piwikRequireConsent" value="1" checked=$piwikRequireConsent label="plugins.generic.piwik.manager.settings.piwikRequireConsent"}
            {/fbvFormSection}
            {fbvFormSection for="piwikUpperText" title="plugins.generic.piwik.manager.settings.piwikUpperText" description="plugins.generic.piwik.manager.settings.piwikUpperText.desc"}
                {fbvElement type="text" id="piwikUpperText" name="piwikUpperText" value=$piwikUpperText label="plugins.generic.piwik.manager.settings.piwikUpperText"}
            {/fbvFormSection}
        {/fbvFormArea}
        {fbvFormButtons}
    </form>

    <p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
