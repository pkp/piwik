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
            {fbvFormSection for="piwikUrl" label="plugins.generic.piwik.manager.settings.piwikUrl" description="plugins.generic.piwik.manager.settings.piwikUrlInstructions"}
                {fbvElement type="text" id="piwikUrl" name="piwikUrl" value=$piwikUrl label="plugins.generic.piwik.manager.settings.piwikUrl" required=true}
            {/fbvFormSection}
            {fbvFormSection for="piwikSiteId" label="plugins.generic.piwik.manager.settings.piwikSiteId" description="plugins.generic.piwik.manager.settings.piwikSiteIdInstructions"}
                {fbvElement type="text" id="piwikSiteId" name="piwikSiteId" value=$piwikSiteId label="plugins.generic.piwik.manager.settings.piwikSiteId" required=true}
            {/fbvFormSection}
        {/fbvFormArea}
        {fbvFormArea id="piwikDsgvoSettings" title="plugins.generic.piwik.manager.settings.piwikDsgvoSettings"}
            <div id="dsgvo_description">{translate key="plugins.generic.piwik.manager.settings.piwikDsgvoSettings.desc"}</div>
            <br/>
            {fbvFormSection for="piwikRequireDSGVO" list=true label="plugins.generic.piwik.manager.settings.piwikRequireDSGVO" description="plugins.generic.piwik.manager.settings.piwikRequireDSGVO.desc"}
                {fbvElement type="checkbox" id="piwikRequireDSGVO" name="piwikRequireDSGVO" value="1" checked=$piwikRequireDSGVO label="plugins.generic.piwik.manager.settings.piwikRequireDSGVO.check"}
            {/fbvFormSection}
            {fbvFormSection for="piwikRequireConsent" list=true title="plugins.generic.piwik.manager.settings.piwikRequireConsent" description="plugins.generic.piwik.manager.settings.piwikRequireConsent.desc"}
                {fbvElement type="checkbox" id="piwikRequireConsent" name="piwikRequireConsent" value="1" checked=$piwikRequireConsent label="plugins.generic.piwik.manager.settings.piwikRequireConsent"}
            {/fbvFormSection}
            {fbvFormSection for="piwikBannerContent" label="plugins.generic.piwik.manager.settings.piwikBannerContent" description="plugins.generic.piwik.manager.settings.piwikBannerContent.desc"}
                {fbvElement type="textarea" name="piwikBannerContent" id="piwikBannerContent" value=$piwikBannerContent rich=true height=$fbvStyles.height.TALL}
            {/fbvFormSection}
        {/fbvFormArea}
        {fbvFormButtons}
    </form>
    <p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
