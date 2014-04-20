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
$mw->{config}->{api_url} = 'http://54.200.254.72/w/api.php';
$mw->login( { lgname => $user, lgpassword => $pass } )
    || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
$wikiText=decode_entities($ARGV[1]);
print $ARGV[2];
if($ARGV[1] eq "readfile"){
open FILE, "<".$ARGV[2];
$output_lines = do { local $/; <FILE> };
$mw->edit({
                action => 'edit',
                title => $page,
                text => $output_lines } ) || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
}else{
$mw->edit({
		action => 'edit',
     		title => $page,
      		text => $wikiText } ) || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
}
exit;
