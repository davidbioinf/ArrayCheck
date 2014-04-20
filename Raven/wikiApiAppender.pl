#!/usr/bin/perl

use Cwd;
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
$wikiText=$ARGV[2];
$page=$ARGV[0];
$section = $ARGV[1];
my $ref = $mw->get_page( { title => $page } );
my $timestamp = $ref->{timestamp};

unless ( $ref->{missing} ) {
if($section eq "-"){
	$mw->edit({
		action => 'edit',
		title => $page,
		basetimestamp => $timestamp, # to avoid edit conflicts
		text => $ref->{'*'} ."\n". $wikiText } ) || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
	print $wikiText . "\n";
}
elsif($section eq "prepend"){
  $mw->edit({
                action => 'edit',
                title => $page,
                basetimestamp => $timestamp, # to avoid edit conflicts
                text => $wikiText ."\n". $ref->{'*'} } ) || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
        print $wikiText . "\n";

}
elsif($section eq "readfile"){
open FILE, "<$wikiText";
$output_lines = do { local $/; <FILE> };
  $mw->edit({
                action => 'edit',
                title => $page,
                basetimestamp => $timestamp, # to avoid edit conflicts
                text => $ref->{'*'} ."\n". $output_lines } ) || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
        print $output_lines . "\n";
}
elsif($section eq "readfilesection"){
print "HERE2$section";
        $section=$ARGV[3];
        $split_element="=".$section."=\n";

open FILE, "<$wikiText";
$output_lines = do { local $/; <FILE> };

        @ref_array=split(/=\Q$section=/,$ref->{'*'});
        if($ref_array[1] =~ m/=/){
         @ref_suffix=split("=Comments=",$ref_array[1]);
          $ref_suffix[0]=$ref_suffix[0]."\n".$output_lines."\n";
          $ref_array[1]=join("=Comments=",@ref_suffix);
        }else{
          $ref_array[1]=$ref_array[1]."\n".$output_lines."\n";
        }

        $ref_text=join("$split_element",@ref_array);

        $mw->edit({
                action => 'edit',
                title => $page,
                basetimestamp => $timestamp, # to avoid edit conflicts
                text => "\n" . $ref_text } ) || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
#print $ref_text;
}
else{
	$split_element="===".$section."===\n";
	@ref_array=split(/===\Q$section===/,$ref->{'*'});	
	if($ref_array =~ m/===/){
	  @ref_suffix=split("===",$ref_array[1]);
	  $ref_suffix[0]=$ref_suffix[0]."\n".$wikiText."\n";
	  $ref_array[1]=join("===",@ref_suffix);
	}else{
	  $ref_array[1]=$ref_array[1]."\n".$wikiText."\n";
	}
	
	$ref_text=join("$split_element",@ref_array);

	$mw->edit({
		action => 'edit',
		title => $page,
		basetimestamp => $timestamp, # to avoid edit conflicts
		text => "\n" . $ref_text } ) || die $mw->{error}->{code} . ': ' . $mw->{error}->{details};
print $wikiText;
}
}
exit;
