<?php

class Project_List extends SpecialPage {
  var $analysisRoot="/apps/Kingdom/Legend/";
  var $outputRoot="/apps/Kingdom/Keep/Analysis";

  function __construct() {
    parent::__construct( 'Project_List' ,'block');
    wfLoadExtensionMessages('Project_List');
  }
  function writeAccessGroups(){
    global $wgUser;
    $user_output_array=array();
    $current_user= $wgUser->mName;
    exec("perl /apps/Kingdom/Raven/modifyUserGroup.pl --action=check --UserRightsPage=UserRights --user='$current_user'",$user_output_array);
#    if($user_output_array[0] != "None"){
#      return implode(",",$user_output_array);
#    }else{
      return "General";
#    } 
  }
  function displayTable () {
    global $wgOut,$wgServer;
    
    $wikiText="{{Pagination_Library}}
= All Projects =
<html>
<div id=page_number_holder></div>
<div id=log_messages>
<img src=\"/w/skins/gmwfreeblue/images/loading.gif\"/>
</div>
</html>
<html>
<script language='javascript' type='text/javascript'>
var int=self.setInterval(\"displayOutput(0,'/w/index.php/Template:Projects',0,'".$this->writeAccessGroups()."')\", 3000);
displayOutput(0,'/w/index.php/Template:Projects',0,'".$this->writeAccessGroups()."');
</script>
</html>
{{#form:}}
{|{{#input:type=hidden|id=tablepage|value=0}}
|}
{{#formend:}}
 ";
    $wgOut->addWikiText($wikiText);
 
  }
  #this is where we begin form processing
  function execute( $par ) {
    global $wgRequest, $wgOut, $wgUser, $wgHooks, $wgTitle, $wgLocalFileRepo, $wgServer;
       $this->displayTable();

  }
}
