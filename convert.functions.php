<?php 
class convertfunctions{
	public function get_title_and_content($htmlstring, $start, $end){
		 $ini = strpos($htmlstring, $start);
		 if($ini == 0) return "";
		 $ini += strlen($start);
		 $len = strpos($htmlstring, $end, $ini) - $ini;
		 $content = substr($htmlstring, $ini, $len);
		 $out = $this->generalConversion($content);

		 //print $out;
		 //exit();
		 return $out;
		}

	public function generalConversion($htmlcnt){
		$cntnt = str_replace("<h1>", "<title>", $htmlcnt);
		$cntnt = str_replace("</h1>", "</title>", $cntnt);
		$cntnt = str_replace("<h2>", "<title>", $cntnt);
		$cntnt = str_replace("</h2>", "</title>", $cntnt);
		$cntnt = str_replace("<h3>", "<title>", $cntnt);
		$cntnt = str_replace("</h3>", "</title>", $cntnt);

		$err = "output.withheld";
		$tagerr1 = "<MadCap:conditionalText MadCap:conditions=".'"'.$err.'"'.">";
		$tagerr2 = "</MadCap:conditionalText>";
		
		$cntnt = str_replace($tagerr1, "", $cntnt);
		$cntnt = str_replace($tagerr2, "", $cntnt);	
		
		$end = ' ">';
		$end1 = '">';
		$cntnt = $this->removeStringBetween($cntnt, "<h2", $end);
		//$string = $this->removeStringBetween($string, "<h3", $end1);
		$cntnt = str_replace("<h3", "<title>", $cntnt);
		$cntnt = str_replace('<h2 ">', '<title>', $cntnt);
		$cntnt = str_replace('> MadCap:conditions="family.VCS"', "", $cntnt);
		$cntnt = str_replace(' MadCap:conditions="family.Conductor"', "", $cntnt);
		/*$string = str_replace(' MadCap:conditions="family.VCS"', "", $string);
		$string = str_replace(' MadCap:conditions="family.VCS,output.withheld"', "", $string);
		$string = str_replace(' <MadCap:variable name="call_control.VCSShort"></MadCap:variable>', "", $string);
		$string = str_replace(' <MadCap:variable name="LocalVariables.ProductShortname" />', "", $string);
		$string = str_replace(' <MadCap:xref href="../additional_information.htm#Upgradin">', "", $string);
		$string = str_replace('</MadCap:xref>', "", $string);
		$string = str_replace('(described in <MadCap:xref href="../creating_a_backup.htm">', "", $string);
		$string = str_replace('<MadCap:snippetBlock src="../z_call_control/snippets/c_caution-NoSnapshot.flsnp" />', "", $string);
		$string = str_replace(' MadCap:autonum="Table 1 &#160;&#160;&#160;"', "", $string);
		$string = str_replace(' <MadCap:variable name="call_control.VCSShort" />', "", $string);
		$string = str_replace(' MadCap:conditions=""', "", $string);
		$string = str_replace('<MadCap:variable name="call_control.VCSShort" />', "", $string);
		$string = str_replace('<MadCap:snippetBlock src="../z_call_control/snippets/medium_ova_change.flsnp" />', "", $string);
		$string = str_replace(' MadCap:conditions="output.withheld"', "", $string);

		$string = str_replace(' MadCap:conditions="output.withheld"', "", $string);*/


		$h2='<h2 ">';
		$cntnt = str_replace($h2, "<title>", $cntnt);
		//print $cntnt;
		//exit();
		return $cntnt;
	}
	function getDataBetweenTags($content, $start, $end){
	$offset = 0;
	$allpos = array();
	while(($pos = strpos($content, $start, $offset))!==FALSE){
		$offset = $pos+1;
		$allpos[]=$pos;
	}
	$i=0;
	$offsetone = 0;
	$allposone = array();
	while(($pos1 = strpos($content, $end, $offsetone))!==FALSE){
		$offsetone = $pos1 + 1;
		$allposone[$i] = $pos1;
		$diff=$allposone[$i]-$allpos[$i];
		$diff=$diff-strlen($start);
		$result[$i]["title"]=substr($content, $allpos[$i]+strlen($start), $diff);
		if($i==count($allpos)-1){
			$nextindex=strlen($content);
		}else{
			$nextindex=$allpos[$i+1];
		}
		$cnt_diff=$nextindex-$allposone[$i];
		$cnt_diff=$cnt_diff-strlen($end);
		$result[$i]["cnt"]=substr($content, $allposone[$i]+strlen($end), $cnt_diff);
		$i=$i+1;
	}
	//print "<pre>";
	//print_r($result);
	return $result;
}
public function removeStringBetween($string,$start,$end){
		$offset = 0;
		$allpos = array();
		while (($pos = strpos($string, $start, $offset)) !== FALSE) {
			$offset   = $pos + 1;
			$allpos[] = $pos;
		}
		$offset1 = 0;
		$allpos1 = array();
		while (($pos = strpos($string, $end, $offset1)) !== FALSE) {
			$offset1   = $pos + 1;
			$allpos1[] = $pos;
		}
		$arr_count=count($allpos);
		$newstring=$string;
		for($i=0;$i<$arr_count;$i++){
			$diff=$allpos1[$i]-$allpos[$i];
			$diff = $diff - strlen($start);
			$str=substr($string,$allpos[$i]+strlen($start),$diff);
			$newstring=str_replace($str,"",$newstring);
		}
		return trim($newstring);
	}

public function createXmlTopic($title, $cnt){
	$newxmlfile = fopen("destination/"."Tpc-".str_replace(" ", "", $title).".xml", "w");
	fwrite($newxmlfile, "<?xml version='1.0'?>"."\n");
	$hd='<!DOCTYPE topic PUBLIC "-//CISCO//DTD DITA 1.3 Topic v1.0//EN" "cisco-topic.dtd" [
]><topic>';
	fwrite($newxmlfile, $hd);
	//$refbody = "<refbody>".$cnt."</refbody></reference>";
	$newcnt = "<title>".$title."</title></topic>";
	fwrite($newxmlfile, $newcnt);
	fclose($newxmlfile);
}

public function createXmlTask($title, $cnt){
	$newxmlfile = fopen("destination/"."TK-".str_replace(" ", "", $title).".xml", "w");
	fwrite($newxmlfile, "<?xml version='1.0'?>"."\n");
	$hd = '<!DOCTYPE task PUBLIC "-//CISCO//DTD DITA 1.3 Task v1.0//EN" "cisco-task.dtd" [
]><task>'."\n";
	fwrite($newxmlfile, $hd);
	$cnt = str_replace("<ul>", "<steps>", $cnt);
	$cnt = str_replace("</ul>", "</steps>", $cnt);
	$cnt = str_replace("<ol>", "<steps>", $cnt);
	$cnt = str_replace("</ol>", "</steps>", $cnt);
	$cnt = str_replace("<li>", "<step><cmd>", $cnt);
	$cnt = str_replace("</li>", "</cmd></step>", $cnt);
	$cnt = str_replace('<span class="field_name">', '<uicontrol>', $cnt);
	$cnt = str_replace('<span class="button_name">', '<uicontrol>', $cnt);
	$cnt = str_replace('<span class="page_name">', '<uicontrol>', $cnt);
	$cnt = str_replace('<span class="page_navigation">', '<uicontrol>', $cnt);
	$cnt = str_replace("<span>", "<uicontrol>", $cnt);
	$cnt = str_replace("</span>", "</uicontrol>", $cnt);
	$cnt = str_replace("<i>", '<userinput>', $cnt);
	$cnt = str_replace("</i>", '</userinput>', $cnt);
	$cnt = str_replace('<li MadCap:conditions="family.VCS">', '<step><cmd>', $cnt);
	//adding context tag//
	$dcontxt = $this->dataBeforetag($cnt);

	//changing substeptags//
	$results = $this->gettingDataOfSteps($dcontxt, "<steps>", "</steps>");
	$dcontxt = str_replace("</substeps>
            </cmd></step>", "</substeps></step>", $results);
	$dcontxt = str_replace('<substeps>', '</cmd><substeps>', $dcontxt);
	$dcontxt = str_replace('</substeps></cmd></step>', '</substeps></step>', $dcontxt);
	$dcontxt = str_replace("</p>
                <substeps>", "</p></cmd>
                <substeps>", $dcontxt);
	//echo htmlspecialchars($dcontxt);
	//exit();

	$taskbody = "<taskbody>".$dcontxt."</taskbody></task>";
	$newcnt = "<title>".$title."</title>"."\n".$taskbody;
	fwrite($newxmlfile, $newcnt);
	fclose($newxmlfile);
}
public function createXmlConcept($title, $cnt){
	$newxmlfile = fopen("destination/"."Con-".str_replace(" ", "", $title).".xml", "w");
	fwrite($newxmlfile, "<?xml version='1.0'?>"."\n");
	$hd = '<!DOCTYPE concept PUBLIC "-//CISCO//DTD DITA 1.3 Concept v1.0//EN" "cisco-concept.dtd" []><concept>'."\n";
	fwrite($newxmlfile, $hd);
	$conbody = "<conbody>".$cnt."</conbody></concept>";
	$newcnt = "<title>".$title."</title>"."\n".$conbody;
	fwrite($newxmlfile, $newcnt);
	fclose($newxmlfile);
}
public function createXmlReference($title, $cnt){
	$newxmlfile = fopen("destination/"."Ref-".str_replace(" ", "", $title).".xml", "w");
	fwrite($newxmlfile, "<?xml version='1.0'?>"."\n");
	$hd='<!DOCTYPE reference PUBLIC "-//CISCO//DTD DITA 1.3 Reference v1.0//EN" "cisco-reference.dtd" []><reference>';
	fwrite($newxmlfile, $hd);
	$cnt = str_replace(' MadCap:conditions="family.VCS"', "", $cnt);
	$cnt = str_replace("<li>", "<li><p>", $cnt);
	$cnt = str_replace("</li>", "</p></li>", $cnt);
	$cnt = str_replace('<caption MadCap:autonum="Table 1 &#160;&#160;&#160;">', '<title>', $cnt);
	$cnt = str_replace('</caption>', '</title><tgroup>', $cnt);
	$cnt = str_replace('<col style="width: 20%;" />', '', $cnt);
	$cnt = str_replace('<col />', '', $cnt);
	$cnt = str_replace('<tr MadCap:conditions="">', '<row>', $cnt);
	$cnt = str_replace('</tr>', '</row>', $cnt);
	$cnt = str_replace('<th>', '<entry><p>', $cnt);
	$cnt = str_replace('</th>', '</p></entry>', $cnt);
	$cnt = str_replace('<td>', '<entry><p>', $cnt);
	$cnt = str_replace('</td>', '</p></entry>', $cnt);
	$refbody = "<refbody><section>".$cnt."</section></refbody></reference>";
	$newcnt = "<title>".$title."</title>"."\n".$refbody;
	fwrite($newxmlfile, $newcnt);
	fclose($newxmlfile);
}
public function dataBeforetag($string){
	//print htmlspecialchars($string)."\n";
	$strt = "<steps>";
	$dcntxt = strstr($string, $strt, true);
	$nwcntxt = str_replace("<p>", "<context>", $dcntxt);
	$nwcntxt = str_replace("</p>", "</context>", $nwcntxt);
	$ncntxt = str_replace($dcntxt, $nwcntxt, $string);
	//print htmlspecialchars($dcntxt);
	return $ncntxt;
}
public function gettingDataOfSteps($cnt, $start, $end){
	$offset = 0;
	$allpos = array();
	while(($pos = strpos($cnt, $start, $offset))!==FALSE){
		$offset = $pos+1;
		$allpos[]=$pos;
	}
	//print "<pre>";
	//print_r($allpos);
	$i=0;
	$offsetone = 0;
	$allposone = array();
	$etagcnt=0;
	//print $string."\n\n";
	while(($pos1 = strpos($cnt, $end, $offsetone))!==FALSE){
		$offsetone = $pos1 + 1;
		$allposone[] = $pos1;
		if(isset($allpos[$i+1])){
			$etagcnt=$allpos[$i+1];
		}else{
			$etagcnt=0;
		}
		//print $allpos[$i]."----------".$allposone[$i]."----".$etagcnt."\n\n";
		$j=0;
		if($etagcnt>$allposone[$i]){
			//print $allpos[$i]."----------".$allposone[$i]."----".$etagcnt."\n\n";
			//print "grater -------->". $allpos[$i]."---".$allposone[$i]."----"."\n\n";
		}else{
			if($etagcnt>0){
				
				$diff=$allposone[$i]-$etagcnt;
				$diff=$diff+strlen($end);
				//print "lesser tahn --->".$etagcnt."---".$allposone[$i]."----".$diff."\n\n";
				$old_temp_str=substr($cnt, $allpos[$i+1], $diff);
				$temp_str=str_replace("<step","<substep",$old_temp_str);
				$temp_str=str_replace("</step","</substep",$temp_str);
				$cnt=str_replace($old_temp_str,$temp_str,$cnt);
			}

		}
		
	/*	$diff=$allposone[$i]-$allpos[$i];
		$diff=$diff-strlen($start);
		$result[$i]["step"]=substr($string, $allpos[$i]+strlen($start), $diff);*/
		$i=$i+1;
	}
	//print $cnt;
	return $cnt;
}
}
?>