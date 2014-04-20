#!/usr/bin/perl

use Cwd;
$ENV{PERL_LWP_SSL_VERIFY_HOSTNAME} = 0;
use strict;

my $ID=$ARGV[0];
my $tableType=$ARGV[1];
my $updateCol=$ARGV[2];
my $updateText=$ARGV[3];

if($tableType eq "Projects"){



}elsif($tableType eq "Runs"){

}

my $wikiText=`perl /apps/Kingdom/Raven/wikiApiReader.pl Template:$tableType`;

my @lines=split("\n",$wikiText);
$lines[0]="<HTML>";
for(my $count = 0; $count < scalar(@lines); $count++) {
  my @splittedLine=split("\t",$lines[$count]);
  if($splittedLine[0] =~ m/$ID$/){
    print $splittedLine[0];
    $splittedLine[$updateCol]=$updateText;
    $lines[$count]=join("\t",@splittedLine);
  }
}
 $wikiText=join("\n",@lines)."\t\n\n";
 $wikiText=~ s/'/&#39/;
`perl /apps/Kingdom/Raven/wikiApiEditor.pl Template:$tableType '$wikiText'`;
exit;
