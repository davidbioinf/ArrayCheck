<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if (!defined('MEDIAWIKI')) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/Project_List/Project_List.php");
EOT;
        exit( 1 );
}
 
$wgExtensionCredits['Project_List'][] = array(
	'name' => 'Project_List',
	'author' => 'David',
	'url' => 'http://www.mediawiki.org/wiki/Extension:MyExtension',
	'description' => 'Default description message',
	'descriptionmsg' => 'myextension-desc',
	'version' => '0.0.0',
);
 
$dir = dirname(__FILE__) . '/';
 
$wgAutoloadClasses['Project_List'] = $dir . 'Project_List_body.php'; # Tell MediaWiki to load the extension body.
$wgExtensionMessagesFiles['Project_List'] = $dir . 'Project_List.i18n.php';
$wgExtensionAliasesFiles['Project_List'] = $dir . 'Project_List.alias.php';
$wgSpecialPages['Project_List'] = 'Project_List'; # Let MediaWiki know about your new special page.

// Hooked functions
$wgHooks['MonoBookTemplateToolboxEnd'][]  = 'wfProject_ListToolbox';
$wgHooks['SkinTemplateBuildNavUrlsNav_urlsAfterPermalink'][] = 'wfProject_ListNav';

// Add the link to Special:sequenceupload to all SkinTemplate-based skins for users with the 'upload' user right
function wfProject_ListNav( &$skintemplate, &$nav_urls, &$oldid, &$revid ) {
	global $wgUser;
	wfLoadExtensionMessages( 'Project_List' );
	if( $wgUser->isAllowed( 'upload' ) )
		$nav_urls['Project_List'] = array(
			'text' => wfMsg( 'Project_List_link' ),
			'href' => $skintemplate->makeSpecialUrl( 'Project_List' )
		);

	return true;
}

// Add the link to Special:sequenceupload to the Monobook skin
function wfProject_ListToolbox( &$monobook ) {
	wfLoadExtensionMessages( 'Project_List' );
	if ( isset( $monobook->data['nav_urls']['Project_List'] ) )  {
		if ( $monobook->data['nav_urls']['Project_List']['href'] == '' ) {
			?><li id="t-isProject_List"><?php echo $monobook->msg( 'Project_List-toolbox' ); ?></li><?php
		} else {
			?><li id="t-Project_List"><?php
				?><a href="<?php echo htmlspecialchars( $monobook->data['nav_urls']['Project_List']['href'] ) ?>"><?php
					echo $monobook->msg( 'Project_List-toolbox' );
				?></a><?php
			?></li><?php
		}
	}
	return true;
}

