<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if (!defined('MEDIAWIKI')) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/Image/Image.php");
EOT;
        exit( 1 );
}
 
$wgExtensionCredits['Image'][] = array(
	'name' => 'Image',
	'author' => 'David',
	'url' => 'http://www.mediawiki.org/wiki/Extension:MyExtension',
	'description' => 'Default description message',
	'descriptionmsg' => 'myextension-desc',
	'version' => '0.0.0',
);
 
$dir = dirname(__FILE__) . '/';
 
$wgAutoloadClasses['Image'] = $dir . 'Image_body.php'; # Tell MediaWiki to load the extension body.
$wgExtensionMessagesFiles['Image'] = $dir . 'Image.i18n.php';
$wgExtensionAliasesFiles['Image'] = $dir . 'Image.alias.php';
$wgSpecialPages['Image'] = 'Image'; # Let MediaWiki know about your new special page.

// Hooked functions
$wgHooks['MonoBookTemplateToolboxEnd'][]  = 'wfImageToolbox';
$wgHooks['SkinTemplateBuildNavUrlsNav_urlsAfterPermalink'][] = 'wfImageNav';

// Add the link to Special:sequenceupload to all SkinTemplate-based skins for users with the 'upload' user right
function wfImageNav( &$skintemplate, &$nav_urls, &$oldid, &$revid ) {
	global $wgUser;
	wfLoadExtensionMessages( 'Image' );
	if( $wgUser->isAllowed( 'upload' ) )
		$nav_urls['Image'] = array(
			'text' => wfMsg( 'Image_link' ),
			'href' => $skintemplate->makeSpecialUrl( 'Image' )
		);

	return true;
}

// Add the link to Special:sequenceupload to the Monobook skin
function wfImageToolbox( &$monobook ) {
	wfLoadExtensionMessages( 'Image' );
	if ( isset( $monobook->data['nav_urls']['Image'] ) )  {
		if ( $monobook->data['nav_urls']['Image']['href'] == '' ) {
			?><li id="t-isImage"><?php echo $monobook->msg( 'Image-toolbox' ); ?></li><?php
		} else {
			?><li id="t-Image"><?php
				?><a href="<?php echo htmlspecialchars( $monobook->data['nav_urls']['Image']['href'] ) ?>"><?php
					echo $monobook->msg( 'Image-toolbox' );
				?></a><?php
			?></li><?php
		}
	}
	return true;
}

