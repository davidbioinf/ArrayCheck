<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if (!defined('MEDIAWIKI')) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/Storage/Storage.php");
EOT;
        exit( 1 );
}
 
$wgExtensionCredits['Storage'][] = array(
	'name' => 'Storage',
	'author' => 'David',
	'url' => 'http://www.mediawiki.org/wiki/Extension:MyExtension',
	'description' => 'Default description message',
	'descriptionmsg' => 'myextension-desc',
	'version' => '0.0.0',
);
 
$dir = dirname(__FILE__) . '/';
 
$wgAutoloadClasses['Storage'] = $dir . 'Storage_body.php'; # Tell MediaWiki to load the extension body.
$wgExtensionMessagesFiles['Storage'] = $dir . 'Storage.i18n.php';
$wgExtensionAliasesFiles['Storage'] = $dir . 'Storage.alias.php';
$wgSpecialPages['Storage'] = 'Storage'; # Let MediaWiki know about your new special page.

// Hooked functions
$wgHooks['MonoBookTemplateToolboxEnd'][]  = 'wfStorageToolbox';
$wgHooks['SkinTemplateBuildNavUrlsNav_urlsAfterPermalink'][] = 'wfStorageNav';

// Add the link to Special:sequenceupload to all SkinTemplate-based skins for users with the 'upload' user right
function wfStorageNav( &$skintemplate, &$nav_urls, &$oldid, &$revid ) {
	global $wgUser;
	wfLoadExtensionMessages( 'Storage' );
	if( $wgUser->isAllowed( 'upload' ) )
		$nav_urls['Storage'] = array(
			'text' => wfMsg( 'Storage_link' ),
			'href' => $skintemplate->makeSpecialUrl( 'Storage' )
		);

	return true;
}

// Add the link to Special:sequenceupload to the Monobook skin
function wfStorageToolbox( &$monobook ) {
	wfLoadExtensionMessages( 'Storage' );
	if ( isset( $monobook->data['nav_urls']['Storage'] ) )  {
		if ( $monobook->data['nav_urls']['Storage']['href'] == '' ) {
			?><li id="t-isStorage"><?php echo $monobook->msg( 'Storage-toolbox' ); ?></li><?php
		} else {
			?><li id="t-Storage"><?php
				?><a href="<?php echo htmlspecialchars( $monobook->data['nav_urls']['Storage']['href'] ) ?>"><?php
					echo $monobook->msg( 'Storage-toolbox' );
				?></a><?php
			?></li><?php
		}
	}
	return true;
}

