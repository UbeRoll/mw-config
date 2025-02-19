<?php

$wgNoticeProject = 'all';
if ( $wmgSiteNoticeOptOut ) {
	// Only show important notices when optout
	$wgNoticeProject = 'optout';
}

// Increment this version number whenever you change the site notice
$wgMajorSiteNoticeID = 86;

/**
 * Wrap your sitenotice with <div data-nosnippet>(sitenotice)</div>
 * or Google will use the sitenotice for their search result snippet.
 */

// Global SiteNotice
/*if ( !$wmgSiteNoticeOptOut ) {
	$wgHooks['SiteNoticeAfter'][] = 'wfGlobalSiteNotice';

	function wfGlobalSiteNotice( &$siteNotice, $skin ) {
		$skin->getOutput()->enableOOUI();
		$skin->getOutput()->addInlineStyle(
			'.mw-dismissable-notice .mw-dismissable-notice-body { margin: unset; }' .
			'.skin-cosmos #sitenotice-learnmore-button { margin-left: 50px; }'
		);

		$siteNotice .= <<<EOF
			<table style="width: 100%;">
				<tbody><tr><td style="font-size: 120%; border-left: 4px solid #fc3; background-color: #fef6e7; padding: 10px 15px; color: black;">
					<div data-nosnippet style="padding-top:0.3em; padding-bottom:0.1em;">
						<div class="floatleft"><img alt="Maintenance" src="https://upload.wikimedia.org/wikipedia/commons/d/d0/OOjs_UI_icon_imageLock-ltr.svg" decoding="async" width="50" height="50"></div>
						<div style="padding-bottom: 15px; font-size: 13pt; font-weight: bold;">
							Miraheze will be doing maintenance on the servers on the 6th of July at 22:00 UTC. Some wiki's will be unavailable, whilst others will be read-only during the period of 3 hours (ends at 01:00 UTC). Less if we complete it before the 3 hour window is up. We apologize for the inconvenience caused.
						</div>
					</div>
				</td></tr></tbody>
			</table>
		EOF;
	}

}*/

// Specific wiki SiteNotice
if ( $wi->isExtensionActive( 'Graph' ) ) {
	$wgHooks['SiteNoticeAfter'][] = 'wfConditionalSiteNotice';

	function wfConditionalSiteNotice( &$siteNotice, $skin ) {
		$skin->getOutput()->enableOOUI();
		$skin->getOutput()->addInlineStyle(
			'.mw-dismissable-notice .mw-dismissable-notice-body { margin: unset; }' .
			'.skin-cosmos #sitenotice-learnmore-button { margin-left: 50px; }'
		);

		$siteNotice .= <<<EOF
			<table style="width: 100%;">
				<tbody><tr><td style="font-size: 120%; border-left: 4px solid #67440F; background-color: #FFF2F6; padding: 10px 15px; color: black;">
					<div data-nosnippet style="padding-top:0.3em; padding-bottom:0.1em;">
						<div class="floatleft"><img alt="Miraheze Logo" src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Font_Awesome_5_solid_bug.svg" decoding="async" width="50" height="50"></div>
						<div style="padding-bottom: 15px; font-size: 13pt; font-weight: bold;">
							 Graph has been temporarily disabled due to a severe security bug.
						</div>

						<span id="sitenotice-learnmore-button" class="oo-ui-widget oo-ui-widget-enabled oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-iconElement oo-ui-labelElement oo-ui-buttonWidget">
							<a class="oo-ui-buttonElement-button" role="button" tabindex="0" href="https://meta.miraheze.org/wiki/Tech:SRE_noticeboard#Graph_disabled">
								<span class="oo-ui-iconElement-icon oo-ui-icon-notice"></span>
								<span class="oo-ui-labelElement-label">{$skin->msg( 'miraheze-sitenotice-learnmore' )->escaped()}</span>
								<span class="oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator"></span>
							</a>
						</span>
					</div>
				</td></tr></tbody>
			</table>
		EOF;
	}
}

// Meta Tech NS sitenotice
if ( $wgDBname === 'metawiki' ) {
$wgHooks['SiteNoticeAfter'][] = 'wfMetaSiteNotice';

function wfMetaSiteNotice( &$siteNotice, $skin ) {
	$title = $skin->getTitle();
	if ( $title->getNamespace() !== 1600 ) {
		return;
	}

	$skin->getOutput()->enableOOUI();
	$skin->getOutput()->addInlineStyle(
		'.mw-dismissable-notice .mw-dismissable-notice-body { margin: unset; }' .
		'.skin-cosmos #sitenotice-learnmore-button { margin-left: 50px; }'
	);
	$siteNotice .= <<<EOF
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td style="border-left: 4px solid #fc3; background-color: #fef6e7; padding: 10px 15px;">
                    <div style="padding-top: 0.3em; padding-bottom: 0.1em; font-size: 100%;">
                        <img alt="OOjs UI icon web-progressive" src="https://upload.wikimedia.org/wikipedia/commons/9/9e/OOjs_UI_icon_web-progressive.svg" decoding="async" width="35" height="35" style="float: left; margin-right: 10px;">
                        <div style="font-weight: bold;">Vacancy</div>
                        SRE is looking for Software Engineers to join our MediaWiki Team to develop code to improve the user experience of Miraheze users, build tools that allow communities to grow, and tools that support our valuable volunteers in managing a dynamic and active global community. If you think this could be you, please do have a look at the <a href="https://meta.miraheze.org/wiki/Miraheze_Vacancies#Software_Engineer_(Developer)_(MediaWiki)">the Vacancies page</a> which includes more information.
                    </div> <br /> Other vacancies are also available on that page.
                </td>
            </tr>
            <tr>
                <td style="height: 10px;"></td>
            </tr>
        </tbody>
    </table>
EOF;
}
}
