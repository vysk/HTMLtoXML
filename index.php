<?php 
include("convert.functions.php");
$convertcnt=new convertfunctions();
header('Content-Type:text/plain');
$htmldoc = file_get_contents("release_and_option_keys.htm");
$htmlbody = $convertcnt->get_title_and_content($htmldoc, "<body>", "</body>");
$contentarray = array();
$contentarray = $convertcnt->getDataBetweenTags($htmlbody, "<title>", "</title>");
//print "<pre>";
//print_r($contentarray);
//exit();
header('Content-Type:text/html');
include("ui.php");
$post_array = $_POST;
for($i=0;$i<count($post_array["filetypes"]);$i++){
  if($post_array["filetypes"][$i]!=""){
    if($post_array["filetypes"][$i]=="Topic"){
      $convertcnt->createXmlTopic($contentarray[$i]["title"], $contentarray[$i]["cnt"]);
    }
    if($post_array["filetypes"][$i]=="Task"){
      $convertcnt->createXmlTask($contentarray[$i]["title"], $contentarray[$i]["cnt"]);
    }
    if($post_array["filetypes"][$i]=="Concept"){
      $convertcnt->createXmlConcept($contentarray[$i]["title"], $contentarray[$i]["cnt"]);
    }
    if($post_array["filetypes"][$i]=="Reference"){
     $convertcnt->createXmlReference($contentarray[$i]["title"], $contentarray[$i]["cnt"]);
      //echo "Reference body";
    }
  }
}
//print_r($post_array);
?>
