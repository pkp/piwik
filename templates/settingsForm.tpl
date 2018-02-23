{**
 * plugins/generic/piwik/templates/settingsForm.tpl
 *
 * Copyright (c) 2013-2018 Simon Fraser University
 * Copyright (c) 2003-2018 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Piwik plugin settings
 *
 *}
<div id="piwikSettings">
<div id="description">{translate key="plugins.generic.piwik.manager.settings.description"}</div>

<div class="separator"></div>

<br />

<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#piwikSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>
<form class="pkp_form" id="piwikSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}">
	{csrf}

	{fbvFormArea id="piwikSettingsFormArea"}
		{fbvFormSection for="piwikUrl" title="plugins.generic.piwik.manager.settings.piwikUrl" description="plugins.generic.piwik.manager.settings.piwikUrlInstructions"}
			{fbvElement type="text" id="piwikUrl" name="piwikUrl" value=$piwikUrl label="plugins.generic.piwik.manager.settings.piwikUrl" required=true}
		{/fbvFormSection}

		{fbvFormSection for="piwikSiteId" title="plugins.generic.piwik.manager.settings.piwikSiteId" description="plugins.generic.piwik.manager.settings.piwikSiteIdInstructions"}
			{fbvElement type="text" id="piwikSiteId" name="piwikSiteId" value=$piwikSiteId label="plugins.generic.piwik.manager.settings.piwikSiteId" required=true}
		{/fbvFormSection}
	{/fbvFormArea}

	{fbvFormButtons}
</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
