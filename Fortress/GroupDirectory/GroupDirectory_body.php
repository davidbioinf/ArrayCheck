<?php

class GroupDirectory extends SpecialPage {
  var $analysisRoot="/apps/Kingdom/Keep/";
  var $appRoot="/apps/Kingdom/Keep/Apps/";

  function __construct() {
    parent::__construct( 'GroupDirectory' ,'block');
    wfLoadExtensionMessages('GroupDirectory');
  }
  function displayDirectory($wgRequest){
    global $wgOut, $wgServer,$wgUser;

    $wikiText="";
    $user=$wgUser->mName;
    $gid=exec("/apps/Kingdom/Legend/SQL/bin/abgen_query_user.pl -u '$user'");
    #$isAdmin=exec("/apps/Kingdom/Legend/SQL/bin/abgen_query_user.pl -a '$user'");
    $isAdmin=0;
    if($isAdmin=="0"){
       $gid="*";
    }
    if(trim($gid) == ""){
      $wikiText="<html><font color=red>Your username  doesn't seem to have directory accessible permissions, please contact david@distributedbio.com for directory read privileges!</font></html>";
    }else{
    if($wgRequest->getVal("type") == "experiment"){
      list($project,$experiment)=array($wgRequest->getVal("p"),$wgRequest->getVal("e"));
      $wikiText="\n[[Special:Experiment_List|Experiments]]->[[Experiment_$project.$experiment]]->Analysis Files\n";
        $files = glob($this->analysisRoot . "$gid/Project$project-*/Project$project-Experiment$project.$experiment-*/*");
        if(count($files) > 0){
        $wikiText.="\n{| class='wikitable' style='text-align: center; width: 95%;' style=\"margin-left: 100px;\"";

        foreach ($files as $file){
          chmod($file,0777);
          $wikiText.="\n|-\n|<html><a href='$wgServer/w/index.php/Special:GroupDirectory?action=download&type=experiment&p=$project&e=$experiment&file=".basename($file)."'>".basename($file)."</a></html>";
        }

        $wikiText.="|-\n|}";

        }else{
           $wikiText.="<br><br><font color=red>It seems this directory is either empty or you do not have the correct permissions to access files at this location. Please contact david@distributedbio.com for permission managements.</font>";
        }
      
      $wgOut->addWikiText("<br>\n==Analysis Files:==\n*Project: $project<br>\n*Experiment: $experiment<br>\n*User: $user<br><br>");
    }else if($wgRequest->getVal("type") == "app"){
      list($directory)=array($wgRequest->getVal("d"));
        $wikiText="\n$directory Files\n";
        $files = glob($this->appRoot . "$gid/$directory/*");
        if(count($files) > 0){
        $wikiText.="\n{| class='wikitable' style='text-align: center; width: 95%;' style=\"margin-left: 100px;\"";

        foreach ($files as $file){
          chmod($file,0777);
          #$wikiText.="\n|-\n|<html><a href='$wgServer/w/index.php/Special:GroupDirectory?action=download&type=experiment&p=$project&e=$experiment&file=".basename($file)."'>".basename($file)."</a></html>";
	$wikiText.="\n|-\n|<html><a href='$wgServer/w/index.php/Special:GroupDirectory?action=download&type=app&d=$directory&file=".basename($file)."'>".basename($file)."</a></html>";
        }

        $wikiText.="|-\n|}";      
        }else{
           $wikiText.="<br><br><font color=red>It seems this directory is either empty or you do not have the correct permissions to access files at this location. Please contact david@distributedbio.com for permission managements.</font>";
        }
      }
    }
    $wgOut->addWikiText($wikiText);
  }

  function downloadFile($wgRequest){
    global $wgOut,$wgUser;
    $path="";
    $user=$wgUser->mName;
    $gid=0;
    $gid=exec("/apps/Kingdom/Legend/SQL/bin/abgen_query_user.pl -u '$user'");
    #$isAdmin=exec("/apps/Kingdom/Legend/SQL/bin/abgen_query_user.pl -a '$user'");
    $isAdmin=0;
    if($isAdmin=="0"){
       $gid="*";
    }
    if($gid == ""){
      $wikiText="<html><font color=red>Your username  doesn't seem to have directory accessible permissions, please contact david@distributedbio.com for directory read privileges!</font></html>";
    }else{
    if($wgRequest->getVal("type") == "experiment"){
     list($file,$project,$experiment)=array($wgRequest->getVal("file"),$wgRequest->getVal("p"),$wgRequest->getVal("e"));
       # $path=$this->analysisRoot . "$gid/Project$project-*/Project$project-Experiment$project.$experiment-*/$file";
       #echo $this->analysisRoot . "$gid/Project$project-*/Project$project-Experiment$project.$experiment-*/$file";
        $path=glob($this->analysisRoot . "$gid/Project$project-*/Project$project-Experiment$project.$experiment-*/$file");
    }else if($wgRequest->getVal("type") == "app"){
     list($file,$directory)=array($wgRequest->getVal("file"),$wgRequest->getVal("d"));
       # $path=$this->analysisRoot . "$gid/Project$project-*/Project$project-Experiment$project.$experiment-*/$file";
        $path=glob($this->appRoot . "$gid/$directory/$file");
       
    }
   # $wgOut->addWikiText( "H".$path[0]);
  if(count($path)>0){
    if(file_exists($path[0])){
      $mm_type="application/octet-stream"; // modify accordingly to the file type of $path, but in most cases no need to do so
#      header("Content-Type: " . $mm_type);
      header("Content-type: application/force-download");
      header("Content-Transfer-Encoding: Binary");
      $sizeoffile=filesize($path[0]);
      header("Content-Length: " . $sizeoffile);
      header('Content-Disposition: attachment; filename="'.basename($path[0]).'"');
      $test=readfile($path[0]); // outputs the content of the file
      exit; 
    }else{
      $wgOut->addWikiText("You do no have permissions to download this file");
    }
   }else{
     $wgOut->addWikiText("<html><font color=red>Your username  doesn't seem to have directory accessible permissions, please contact david@distributedbio.com for directory read privileges!</font></html>");
   }
   }
  }

  #this is where we begin form processing
  function execute( $par ) {
    global $wgRequest, $wgOut, $wgUser, $wgHooks, $wgTitle, $wgLocalFileRepo, $wgServer;
    if($wgRequest->getVal('action')!="download"){
      $this->displayDirectory($wgRequest);
    }else{
      $this->downloadFile($wgRequest);
    }
 
  }
}

?>
