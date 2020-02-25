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
            {fbvFormSection for="piwikRequireDSGVO" list=true label="plugins.generic.piwik.manager.settings.piwikDSGVO" description="plugins.generic.piwik.manager.settings.piwikDSGVO.desc"}
                {fbvElement type="checkbox" id="piwikRequireDSGVO" name="piwikRequireDSGVO" value="1" checked=$piwikRequireDSGVO label="plugins.generic.piwik.manager.settings.piwikRequireDSGVO"}
                {fbvElement type="checkbox" id="piwikRequireConsent" name="piwikRequireConsent" value="1" checked=$piwikRequireConsent label="plugins.generic.piwik.manager.settings.piwikRequireConsent"}
                {fbvElement type="checkbox" id="piwikDisableCookies" name="piwikDisableCookies" value="1" checked=$piwikDisableCookies label="plugins.generic.piwik.manager.settings.piwikDisableCookies"}
            {/fbvFormSection}
            {fbvFormSection for="piwikCookieTTL" label="plugins.generic.piwik.manager.settings.piwikCookieTTL" description="plugins.generic.piwik.manager.settings.piwikCookieTTL.desc"}
                {fbvElement type="text" id="piwikCookieTTL" name="piwikCookieTTL" value=$piwikCookieTTL}
            {/fbvFormSection}
            {fbvFormSection for="piwikPosition" list=true label="plugins.generic.piwik.manager.settings.piwikPosition" description="plugins.generic.piwik.manager.settings.piwikPosition.desc"}
                {fbvElement id="piwikPosition" name="piwikPosition" type="select" from=$piwikPositionOptions selected=$piwikPosition}
            {/fbvFormSection}
            {fbvFormSection for="piwikBannerContent" label="plugins.generic.piwik.manager.settings.piwikBannerContent" description="plugins.generic.piwik.manager.settings.piwikBannerContent.desc"}
                {fbvElement type="textarea" name="piwikBannerContent" id="piwikBannerContent" value=$piwikBannerContent rich=true height=$fbvStyles.height.TALL}
            {/fbvFormSection}
            {fbvFormSection for="piwikLinkColor" label="plugins.generic.piwik.manager.settings.piwikLinkColor" description="plugins.generic.piwik.manager.settings.piwikLinkColor.desc"}
                {fbvElement type="colour" id="piwikLinkColor" name="piwikLinkColor" value=$piwikLinkColor}
            {/fbvFormSection}
            {fbvFormSection for="piwikAcceptBtnTxt" label="plugins.generic.piwik.manager.settings.piwikAcceptBtn" description="plugins.generic.piwik.manager.settings.piwikAcceptBtn.desc"}
                <div style="display: inline-block; width: 33%; margin-right: 4%;">
                    {fbvElement type="text" id="piwikAcceptBtnTxt" name="piwikAcceptBtnTxt" value=$piwikAcceptBtnTxt label="plugins.generic.piwik.manager.settings.piwikBtnTxt"}
                </div>
                <div style="display: inline-block; width: 30%">
                    {fbvElement type="colour" id="piwikAcceptBtnColor" name="piwikAcceptBtnColor" value=$piwikAcceptBtnColor label="plugins.generic.piwik.manager.settings.piwikBtnColor"}
                </div>
                <div style="display: inline-block; width: 30%">
                    {fbvElement type="colour" id="piwikAcceptBtnBGColor" name="piwikAcceptBtnBGColor" value=$piwikAcceptBtnBGColor label="plugins.generic.piwik.manager.settings.piwikBtnBGColor"}
                </div>
            {/fbvFormSection}
            {fbvFormSection for="piwikDeclineBtnTxt" label="plugins.generic.piwik.manager.settings.piwikDeclineBtn" description="plugins.generic.piwik.manager.settings.piwikDeclineBtn.desc"}
                <div style="display: inline-block; width: 33%; margin-right: 4%;">
                    {fbvElement type="text" id="piwikDeclineBtnTxt" name="piwikDeclineBtnTxt" value=$piwikDeclineBtnTxt label="plugins.generic.piwik.manager.settings.piwikBtnTxt"}
                </div>
                <div style="display: inline-block; width: 30%">
                    {fbvElement type="colour" id="piwikDeclineBtnColor" name="piwikDeclineBtnColor" value=$piwikDeclineBtnColor label="plugins.generic.piwik.manager.settings.piwikBtnColor"}
                </div>
                <div style="display: inline-block; width: 30%">
                    {fbvElement type="colour" id="piwikDeclineBtnBGColor" name="piwikDeclineBtnBGColor" value=$piwikDeclineBtnBGColor label="plugins.generic.piwik.manager.settings.piwikBtnBGColor"}
                </div>
            {/fbvFormSection}
        {/fbvFormArea}
        {fbvFormButtons}
    </form>
    <p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
