<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if (!defined('MEDIAWIKI')) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/GroupDirectory/GroupDirectory.php");
EOT;
        exit( 1 );
}
 
$wgExtensionCredits['GroupDirectory'][] = array(
	'name' => 'GroupDirectory',
	'author' => 'David',
	'url' => 'http://www.mediawiki.org/wiki/Extension:MyExtension',
	'description' => 'Default description message',
	'descriptionmsg' => 'myextension-desc',
	'version' => '0.0.0',
);
 
$dir = dirname(__FILE__) . '/';
 
$wgAutoloadClasses['GroupDirectory'] = $dir . 'GroupDirectory_body.php'; # Tell MediaWiki to load the extension body.
$wgExtensionMessagesFiles['GroupDirectory'] = $dir . 'GroupDirectory.i18n.php';
$wgExtensionAliasesFiles['GroupDirectory'] = $dir . 'GroupDirectory.alias.php';
$wgSpecialPages['GroupDirectory'] = 'GroupDirectory'; # Let MediaWiki know about your new special page.

// Hooked functions
$wgHooks['MonoBookTemplateToolboxEnd'][]  = 'wfGroupDirectoryToolbox';
$wgHooks['SkinTemplateBuildNavUrlsNav_urlsAfterPermalink'][] = 'wfGroupDirectoryNav';

// Add the link to Special:sequenceupload to all SkinTemplate-based skins for users with the 'upload' user right
function wfGroupDirectoryNav( &$skintemplate, &$nav_urls, &$oldid, &$revid ) {
	global $wgUser;
	wfLoadExtensionMessages( 'GroupDirectory' );
	if( $wgUser->isAllowed( 'upload' ) )
		$nav_urls['GroupDirectory'] = array(
			'text' => wfMsg( 'GroupDirectory_link' ),
			'href' => $skintemplate->makeSpecialUrl( 'GroupDirectory' )
		);

	return true;
}

// Add the link to Special:sequenceupload to the Monobook skin
function wfGroupDirectoryToolbox( &$monobook ) {
	wfLoadExtensionMessages( 'GroupDirectory' );
	if ( isset( $monobook->data['nav_urls']['GroupDirectory'] ) )  {
		if ( $monobook->data['nav_urls']['GroupDirectory']['href'] == '' ) {
			?><li id="t-isGroupDirectory"><?php echo $monobook->msg( 'GroupDirectory-toolbox' ); ?></li><?php
		} else {
			?><li id="t-GroupDirectory"><?php
				?><a href="<?php echo htmlspecialchars( $monobook->data['nav_urls']['GroupDirectory']['href'] ) ?>"><?php
					echo $monobook->msg( 'GroupDirectory-toolbox' );
				?></a><?php
			?></li><?php
		}
	}
	return true;
}

