<?php

/**
 * @defgroup plugins_generic_piwik Piwik Plugin
 */
 
/**
 * @file index.php
 *
 * Copyright (c) 2013-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @ingroup plugins_generic_piwik
 * @brief Wrapper for Piwik plugin.
 *
 */

require_once('PiwikPlugin.inc.php');

return new PiwikPlugin();

