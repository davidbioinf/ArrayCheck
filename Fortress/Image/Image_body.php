<?php

class Image extends SpecialPage {

  function __construct() {
    parent::__construct( 'Image' ,'block');
    wfLoadExtensionMessages('Image');
  }
  function displayImage($wgRequest){
    global $wgOut, $wgServer,$wgUser,$abgen_utils_dir,$abgen_data_dir;

    $wikiText="";
    list($project,$experiment,$gid,$file)=array($wgRequest->getVal("p"),$wgRequest->getVal("e"),$wgRequest->getVal("g"),$wgRequest->getVal("image"));
    $wikiText="\n[[Special:Experiment_List|Experiments]]->[[Experiment_$project.$experiment]]->Analysis Files\n";
    if(file_exists("/apps/Kingdom/Keep/1/$project/$experiment/$file")){
 
      $wikiText .= "<br><embed_document>$wgServer/w/tools/Keep/1/$project/$experiment/$file</embed_document>";
    }else{
      $wikiText.="<br><br><font color=red>It seems this Image does not exist or you do not have the correct permissions to access files at this location. Please contact your administrator for permission managements.</font>";
    }
    $wgOut->addWikiText($wikiText);
  }
  #this is where we begin form processing
  function execute( $par ) {
    global $wgRequest, $wgOut, $wgUser, $wgHooks, $wgTitle, $wgLocalFileRepo, $wgServer;
    if($wgRequest->getVal('image')!=""){
      $this->displayImage($wgRequest);
    }
 
  }
}

?>
