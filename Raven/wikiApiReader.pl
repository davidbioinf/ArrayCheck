#!/usr/bin/perl

use Cwd;
$ENV{PERL_LWP_SSL_VERIFY_HOSTNAME} = 0;
use strict;
no strict "vars";
no strict "subs";
use MediaWiki::API;

$page=$ARGV[0];
print $page;

$user = "Bot"; my $pass = "botpass";
$mw = MediaWiki::API->new();
$mw->{config}->{api_url} = 'http://54.200.254.72/w/api.php';
$mw->login( { lgname => $user, lgpassword => $pass } )
    || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
$contents= $mw->get_page( { title => $page } ) || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
print $contents->{'*'};
exit;
