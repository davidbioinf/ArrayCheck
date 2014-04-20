#!/usr/bin/perl

use Cwd;

use Getopt::Long;
use strict;
no strict "vars";
no strict "subs";

################################ Arguments ################################

my ($action,$user,$group,$UserRightsPage)=GatherOptions();

################################ Inputs ###################################
my $api_reader="/apps/Kingdom/Raven/wikiApiReader.pl";
my $api_writer="/apps/Kingdom/Raven/wikiApiEditor.pl";
my $dbi_create_group="/apps/Kingdom/Legend/SQL/bin/abgen_create_group.pl";
my $dbi_create_user="/apps/Kingdom/Legend/SQL/bin/abgen_create_user.pl";
################################ Actions ##################################

$user=lc($user);

if($action eq "add"){
  addUser($user,$group,$UserRightsPage);

}elsif($action eq "remove"){
  removeUSER($user,$group,$UserRightsPage);

}elsif($action eq "check"){
  print checkUserGroup($user,$UserRightsPage);

}elsif($action eq "update"){
  updateUsers($UserRightsPage);

}



############################### Subroutines ###############################

sub updateUsers {
  my ($UserPage)=@_;
  my $rights_content= `perl $api_reader $UserPage`;
  $rights_content =~ s/UserRights//;
  my @rights_array=split(/\n/,$rights_content);
  foreach my $rights (@rights_array){
    chomp $rights;
    if($rights =~ m/<accesscontrol>/ or $rights eq ""){
      next;
    }
    my ($group,$users)=split(/:/,$rights);
    $users_content="";
    foreach my $user (split(/,/,$users)){
       $user =~ s/^\s+//;
       $user =~ s/\s+$//;
       $users_content.="*$user\n";
     }
    $users_content.="*Bot";
    #$users_content="*".join("\n*",split(/,/,$users))."\n*Bot";
    `perl $api_writer Main:$group '$users_content'`;
     print $users_content;
     #Update database and rights
     `$dbi_create_group -g $group`;
     
     foreach my $user (split(/,/,$users)){
       $user =~ s/^\s+//;
       $user =~ s/\s+$//;
       `$dbi_create_user -u $user -g $group`;

     }

  }
}

sub addUser {
  

}

sub removeUser {


}

sub checkUserGroup {
  my ($check_user,$UserPage)=@_;
  my $rights_content= `perl $api_reader $UserPage`;
  $rights_content =~ s/UserRights//;
  my $checked_groups="";
  my @rights_array=split(/\n/,$rights_content);
  foreach my $rights (@rights_array){
    chomp $rights;
    if($rights =~ m/<accesscontrol>/ or $rights eq ""){
      next;
    }
    my ($group,$users)=split(/:/,$rights);
    foreach my $user (split(/,/,$users)){
      #chomp $user;
      $user =~ s/^ //;
       $user =~ s/ $//;
      if(lc($user) eq lc($check_user)){
        $checked_groups.=$group."\n";
      }
    }
  }
  if($checked_groups eq ""){
    return "None";
  }else{
    return $checked_groups;
  }
}

sub GatherOptions {
  my $action              =   ""; # sequence file
  my $user      =   "";
  my $group      =   "";
  my $UserRightsPage=   "UserRights";
  GetOptions(
    "--action=s"          => \$action,
    "--user=s"  => \$user,
    "--group=s"  => \$group,
    "--UserRightsPage=s" => \$UserRightsPage,
  );
  unless($action ne "" ){
    printUsage();
  }
  return ($action,$user,$group,$UserRightsPage);
}

sub printUsage {
    print "\nUsage: $0\n";
    print " --action           Can be add,remove,check,update\n";
    print " --user             User to be added/removed to/from a group, or checked which group belonged to\n";
    print " --group            Group to be used for adding or removing\n";
    print " --UserRightsPage   Page where user group rights configuration is located on wiki\n";
    exit;
}

