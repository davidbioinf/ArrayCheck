#!/usr/bin/perl

use Cwd;
use HTML::Entities;
$ENV{PERL_LWP_SSL_VERIFY_HOSTNAME} = 0;
use strict;
no strict "vars";
no strict "subs";
use MediaWiki::API;
$user = "Bot"; my $pass = "botpass";
$mw = MediaWiki::API->new();
$mw->{config}->{api_url} = 'http://54.187.86.253/w/api.php';
$mw->login( { lgname => $user, lgpassword => $pass } )
    || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
$wikiText=decode_entities($ARGV[1]);
$page=$ARGV[0];
$mw->edit({
		action => 'edit',
     		title => $page,
      		text => $wikiText } ) || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
print $wikiText;
exit;
