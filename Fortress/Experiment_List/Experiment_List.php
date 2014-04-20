<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if (!defined('MEDIAWIKI')) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/Experiment_List/Experiment_List.php");
EOT;
        exit( 1 );
}
 
$wgExtensionCredits['Experiment_List'][] = array(
	'name' => 'Experiment_List',
	'author' => 'David',
	'url' => 'http://www.mediawiki.org/wiki/Extension:MyExtension',
	'description' => 'Default description message',
	'descriptionmsg' => 'myextension-desc',
	'version' => '0.0.0',
);
 
$dir = dirname(__FILE__) . '/';
 
$wgAutoloadClasses['Experiment_List'] = $dir . 'Experiment_List_body.php'; # Tell MediaWiki to load the extension body.
$wgExtensionMessagesFiles['Experiment_List'] = $dir . 'Experiment_List.i18n.php';
$wgExtensionAliasesFiles['Experiment_List'] = $dir . 'Experiment_List.alias.php';
$wgSpecialPages['Experiment_List'] = 'Experiment_List'; # Let MediaWiki know about your new special page.

// Hooked functions
$wgHooks['MonoBookTemplateToolboxEnd'][]  = 'wfExperiment_ListToolbox';
$wgHooks['SkinTemplateBuildNavUrlsNav_urlsAfterPermalink'][] = 'wfExperiment_ListNav';

// Add the link to Special:sequenceupload to all SkinTemplate-based skins for users with the 'upload' user right
function wfExperiment_ListNav( &$skintemplate, &$nav_urls, &$oldid, &$revid ) {
	global $wgUser;
	wfLoadExtensionMessages( 'Experiment_List' );
	if( $wgUser->isAllowed( 'upload' ) )
		$nav_urls['Experiment_List'] = array(
			'text' => wfMsg( 'Experiment_List_link' ),
			'href' => $skintemplate->makeSpecialUrl( 'Experiment_List' )
		);

	return true;
}

// Add the link to Special:sequenceupload to the Monobook skin
function wfExperiment_ListToolbox( &$monobook ) {
	wfLoadExtensionMessages( 'Experiment_List' );
	if ( isset( $monobook->data['nav_urls']['Experiment_List'] ) )  {
		if ( $monobook->data['nav_urls']['Experiment_List']['href'] == '' ) {
			?><li id="t-isExperiment_List"><?php echo $monobook->msg( 'Experiment_List-toolbox' ); ?></li><?php
		} else {
			?><li id="t-Experiment_List"><?php
				?><a href="<?php echo htmlspecialchars( $monobook->data['nav_urls']['Experiment_List']['href'] ) ?>"><?php
					echo $monobook->msg( 'Experiment_List-toolbox' );
				?></a><?php
			?></li><?php
		}
	}
	return true;
}

