<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Automatic Phonetic Transcription</title>
<link href="HUC.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-type" value="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="en">
<meta name="Author" content="Amir Zeldes">
<?php 
/*helper function area*/
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.http_output', 'UTF-8');

//Add glossary links to comments and descriptions
function AddLinks($strText) {

	//$strText = mb_ereg_replace('%', '&nbsp;', $strText);
	//$strText = mb_ereg_replace('([Ll]aryngeal|[Pp]alatovelar|[Ll]abiovelar|[Aa]spirate|[Rr]oot)([ds]?)', '<div title="Glossary: \1s" class="LRef" onClick=OpenLink("\1s")>\1\2</div>', $strText);
	//$strText = mb_ereg_replace('([Rr]eduplicat)(ion|e|ing)?', '<div title="Glossary: Reduplication" class="LRef" onClick=OpenLink("\1ion")>\1\2</div>', $strText);
	//$strText = mb_ereg_replace('((?:[Cc]entum|[Ss]atem) languages)', '<div title="Glossary: Centum and Satem Languages" class="LRef" onClick=OpenLink("centum_satem")>\1</div>', $strText);
	//$strText = mb_ereg_replace('((?:Ø|e|full)(?:\-| )grades?|Ablaut|[Vv]owel [Gg]radtion)', '<div title="Glossary: Vowel gradation" class="LRef" onClick=OpenLink("grades")>\1</div>', $strText);
	//$strText = mb_ereg_replace('(s-mobile|mobile s)', '<div title="Glossary: s-mobile" class="LRef" onClick=OpenLink("s-mobile")>\1</div>', $strText);
	//$strText = mb_ereg_replace('(PIE|Proto Indo-European)', '<div title="Glossary: s-mobile" class="LRef" onClick=OpenLink("PIE")>\1</div>', $strText);
	//$strText = mb_ereg_replace('([isnoa]-stems?|suffixe?s?)', '<div title="Glossary: Stems and Suffixes" class="LRef" onClick=OpenLink("stems")>\1</div>', $strText);
	//$strText = mb_ereg_replace('((?:nominative|accusative|genitive|dative|vocative|ablative|locative|instrumental|case)s?)', '<div title="Glossary: Cases" class="LRef" onClick=OpenLink("cases")>\1</div>', $strText);
	return $strText;
}

//Prevent undefined post variables 
function postvar($name) { 
    if(isset($_POST[$name])) { 
        return $_POST[$name]; 
    } else { 
            return ""; 
    } 
} 

//HTML format for code inputs
function FormatCode($strInputA)
{
	$strInput = $strInputA;
	$strInput = mb_ereg_replace("%.%", "", $strInput);
	$strInput = mb_ereg_replace("a/", "á", $strInput);
	$strInput = mb_ereg_replace("e/", "é", $strInput);
	$strInput = mb_ereg_replace("i/", "í", $strInput);
	$strInput = mb_ereg_replace("o/", "ó", $strInput);
	$strInput = mb_ereg_replace("u/", "ú", $strInput);
	$strInput = mb_ereg_replace("</", "&", $strInput);	
	$strInput = mb_ereg_replace("/", "́", $strInput);	
	$strInput = mb_ereg_replace("&", "</", $strInput);	
	$strInput = mb_ereg_replace("^", "", $strInput);	
	$strInput = mb_ereg_replace("@", "̥", $strInput);
	$strInput = mb_ereg_replace("l\.", "ḷ", $strInput);
	$strInput = mb_ereg_replace("r\.", "ṛ", $strInput);
	$strInput = mb_ereg_replace("~", "", $strInput);
	$strInput = mb_ereg_replace("%", "", $strInput);
	$strInput = mb_ereg_replace("R", "ɐ̭", $strInput);
	$strInput = mb_ereg_replace("N", "n̩", $strInput);
	$strInput = mb_ereg_replace("M", "m̩", $strInput);
	$strInput = mb_ereg_replace("L", "l̩", $strInput);
	$strInput = mb_ereg_replace("E", "ɛ̃", $strInput);
	$strInput = mb_ereg_replace("O", "ɔ̃", $strInput);
	$strInput = mb_ereg_replace("B", "bʲ", $strInput);
	$strInput = mb_ereg_replace("P", "pʲ", $strInput);
	$strInput = mb_ereg_replace("W", "vʲ", $strInput);
	$strInput = mb_ereg_replace("ɱ", "mʲ", $strInput);
	return $strInput;
}

function ValidateInput($strInput)
{
	$strInput = mb_strtolower($strInput); //Kill upper case
	if (strlen($strInput) > 30)
	{$strInput=substr($strInput,0,30);}
	$strInput = mb_ereg_replace("[^cqfptkbvdghjzwlmnrs123\./=aeioyußüöä\(\)\-ąęóćńśżźł]", "", $strInput); //czfvßqüöä added, Polish added
	return $strInput;
}

function CleanUp($strInput)
{
	$strInput = mb_ereg_replace("\(.*\)", "", $strInput);
	//$strInput = mb_ereg_replace("\-", "", $strInput); //Leave minus in string
	$strInput = mb_ereg_replace("a=", "ā", $strInput);
	$strInput = mb_ereg_replace("e=", "ē", $strInput);
	$strInput = mb_ereg_replace("i=", "ī", $strInput);
	$strInput = mb_ereg_replace("o=", "ō", $strInput);
	$strInput = mb_ereg_replace("u=", "ū", $strInput);
	return $strInput;
}

function ApplyRule($replace, $messageA, $string, $pat, $desc)
{
	$output = mb_ereg_replace($pat, $replace, $string);
	return $output;
}

?>
</head>
<body>

<h2>&nbsp;Automatic Phonetic Transcription and Syllable Analysis</h2>
<span style="font-size:larger"><strong>Use with caution - an automatic analysis is not always correct :)</strong></span>
<script language="javascript">


function OpenLink(strLink)
{
	strLink = strLink.toLowerCase()
	document.forms[1].elements[0].value = strLink;
	//document.forms[1].action = "IE_Glossary.php#" + strLink;
	document.forms[0].hidNewText.value = "";
	document.forms[1].submit();
	
}
function ChangeWord()
{
	document.forms[0].txtInput.value = document.forms[0].cboOptions.value;
	document.forms[0].hidNewText.value = "";
	document.forms[0].submit();
}function SubmitClick()
{
	document.forms[0].hidNewText.value = "";
	document.forms[0].submit();
}
function ChangeLang()
{
	document.forms[0].cboOptions.value = "";
	document.forms[0].txtInput.value = "";
	document.forms[0].hidNewText.value = "";
	document.forms[0].submit();
}
function RootClick(langs)
{
var arr=langs.split(":");
//document.forms[0].txtInput.value=arr[1];
document.forms[0].hidNewText.value = arr[0];
document.forms[0].submit();

}

</script>
<form action="phon.php" method="post">
<input type="hidden" name="hidNewLang" value="">
<input type="hidden" name="hidNewText" value="">
<table cellspacing="15">
<tr><td>Language:</td><td><select name='cboLang' onChange='ChangeLang()'>
<?php

if (postvar('hidNewLang') != "") 
{
	$_POST['cboLang'] = $_POST['hidNewLang'];
}
echo "<option value='GermanPhonology'";
	if (postvar("cboLang") == "GermanPhonology") 
	{
		echo "selected";
	}
echo ">German</option>";
echo "<option value='PolishPhonology'";
	if (postvar("cboLang") == "PolishPhonology") 
	{
		echo "selected";
	}
echo ">Polish</option>";

if (postvar('hidNewText') != "") 
{
	$_POST['txtInput'] = strip_tags($_POST['hidNewText']);
}
//manage counter
/*
session_start(); 
if (!session_is_registered("counted")){ 
	session_register("counted"); 
	$count_my_page = "../hitcounter.txt";
	$hits = file($count_my_page);
	$hits[0] ++;
	$fp = fopen($count_my_page , "w");
	fputs($fp , $hits[0]);
	fclose($fp);
	echo $hits[0];
}
*/

?>

</select></td></tr><tr><td>
Orthographic form: </td><td>
  <input ID="txtInput" name="txtInput" type="textarea" value="<?php echo strip_tags(postvar("txtInput")); ?>"></input>
  <input name="cmdSubmit" type="submit" onClick="SubmitClick()">
</td>
</tr>
<tr>
<td>Working Examples:
</td><td>
<select  ID="cboOptions" name="cboOptions" onChange="ChangeWord()">
<?php

$lang = postvar("cboLang");

$root="";
$my_root="";
$comment = "";
$trans = "";
$root_found=false;

//Function to use at the start of an element
function start($parser,$element_name,$element_attrs)
{
  global $My_XML_Handler;
  if ($My_XML_Handler=="start2")
  {
  start2($parser,$element_name,$element_attrs);
  }
  else
  {
	global $my_root;
	global $trans, $comment, $root;
	global $root_string, $root_found, $root_final;
	global $options;
	
	$lang = postvar("cboLang");

 if ((string) $element_name=="W") 
 	{

    	$form = (string) $element_attrs['FORM'];
    	$value = (string) $element_attrs['FORM'];
		$langs = (string) $element_attrs['LANG'];

		if (strlen($lang)<1)
		{
			$lang="GermanPhonology"; //Sets default language for first load
		}
		if (strpos($langs, $lang) !== false)
		{
			if (isset($element_attrs['RES']))
				{$res =  mb_ereg_replace("(́|͂)", "", (string) $element_attrs['RES']) ;}
			else
				{$res="";}
			$options[mb_ereg_replace("[\-]","",$res)] =  "<option value='" . $value . "'";
			if (postvar("txtInput") == $value && $lang == $langs) 
			{
				$options[mb_ereg_replace("[\-]","",$res)] = $options[mb_ereg_replace("[\-]","",$res)] . "selected";
				$root=$my_root;
				$root_found=true;
				if (isset($element_attrs['COMMENT']))
				{
				$comment = "<br>Note:<br>".  AddLinks((string) $element_attrs['COMMENT']);
				}
				if (isset($element_attrs['TRANS']))
				{
				$trans = "<i>" . (string) $element_attrs['TRANS'] . "</i>";
				}
			}

			$options[mb_ereg_replace("[\-]","",$res)] = $options[mb_ereg_replace("[\-]","",$res)] . ">" . mb_ereg_replace("[\-]","",$res) . "</option>";
		}
		else //different language
		{
			if ($form!="")
			{
				if (isset($element_attrs['RES']))
				{
					$res = (string) $element_attrs['RES'];
				}
				else
				{
					$res="";
				}
					//$root_string = $root_string . "<li><table border=1><tr><td class='UniFont' width=50 style='vertical-align:middle'>" .  $langs . ":</td><td class='UniFont' style='vertical-align:middle'><a class='sups' href='#' onClick=RootClick('" . $langs . ":" . $form . "')><div class='sups'>"  . $res . " &lt; *" . FormatCode($form) . "</div></a></td></tr></table></li>"; 
					$root_string = $root_string . "<li><span width=50 style='vertical-align:middle;'>" .  $langs . ":</span> <span class='UniFont' style='vertical-align:middle'><a class='sups' href='#' onClick=RootClick('" . $langs . ":" . $form . "')>"  . $res . " &lt; *" . FormatCode($form) . "</a></span></li>"; 
			}
		}
	}
	elseif ((string) $element_name=="R")
	{
		if ($root_found==true)
		{
			$root_final=$root_string;
			$root_found=false;
		}
		else
		{
			$root_string="";
		}
		if (isset($element_attrs['ROOT']))
		{
			$my_root = (string) $element_attrs['ROOT'];
		}
	}
  }
}


//Initialize the XML parser
$parser=xml_parser_create();
//Function to use at the end of an element
function stop($parser,$element_name) {
  global $message;
  global $desc;
  global $input;
  global $prevform;
  global $options;
  
   	if ((string) $element_name=="RULESET" && $prevform!=$input) 
 	{
		echo "<tr><td height=24 style='vertical-align:baseline; border-style:solid; width:150; font-family:" . '"Arial Unicode MS"' .", Lucida Sans Unicode, Tahoma, Gentium, Verdana, sans-serif; font-size:14px; white-space:nowrap;'>";
		echo FormatCode($input);
		echo "</td><td style='border-style:solid; white-space:normal;'>";
		if ($desc!="")
		{
			echo "<a class='hint' href='#'> <span class='note'>" . AddLinks($desc) . "</span>" . $message . "</a></td></tr>";
		}
		else
		{
			echo $message . "</td></tr>";
		}

	}
	elseif ((string) $element_name=="WORDLIST") 
	{

		uksort($options, "SortCapIgnore");
		foreach ($options as $key => $val) 
		{
		   echo  $val . "\n";
		}

	}

}  
function SortCapIgnore($a, $b)
{
$a = ereg_replace('\-\(\)', '', $a);
$b = ereg_replace('\-\(\)', '', $b);
$a = strtolower($a);
$b = strtolower($b);

return strcasecmp($a, $b);
}
//Function to use when finding character data
function char($parser,$data) {}
//Specify element handler
xml_set_element_handler($parser,"start","stop");
//Specify data handler
xml_set_character_data_handler($parser,"char");



//Open XML file
$fp=fopen("../../PhonologyTest.xml","r");
//Read data
while ($data=fread($fp,4096))
  {
  xml_parse($parser,$data,feof($fp)) or 
  die (sprintf("XML Error: %s at line %d", 
  xml_error_string(xml_get_error_code($parser)),
  xml_get_current_line_number($parser)));
  }

if ($root_found==true && $root_string!="" && $root_final=="")
		{$root_final=$root_string;} //last entry in word list doesn't print root in parse loop but here



 ?> 
</select><br><a href="about_phon.php">information and non-working examples</a></td></tr>
<tr><td>Derivation:

</td><td><div ID="Results" style="border-style:solid">

<?php

switch ($lang)
{
case "PolishPhonology":
	$xmlpath = "../../PolishPhonology.xml";
	break;
case "GermanPhonology":
	$xmlpath = "../../GermanPhonology.xml";
	break;
default:
	$xmlpath = "../../GermanPhonology.xml";
}

$input = strip_tags(postvar("txtInput"));

//Function to use at the start of an element
function start2($parser,$element_name,$element_attrs)
{

  global $input; 
  global $trans;
  global $message;
  global $desc;
  global $prevform;
 	if ((string) $element_name=="RULESET") 
 	{
		$prevform = $input;
    	$message = (string) $element_attrs['MESSAGE'];
		if (isset($element_attrs['DESC']))
	    {$desc= (string) $element_attrs['DESC'];}
		else
		{$desc="";}
	}
	elseif ((string) $element_name=="RULE")
	{	
		$input = ApplyRule((string) $element_attrs['REPLACE'], $message, $input, (string) $element_attrs['PAT'], $desc);  
	}
	elseif ((string) $element_name=="STAGE")
	{
		//echo "<tr><td colspan=2  style='border-style:solid; text-align:center; font-style:italic'>" . (string) $element_attrs['NAME'] . " Stage</td></tr>";
	}

}

$parser3=xml_parser_create();
//Specify element handler
xml_set_element_handler($parser3,"start2","stop");
//Specify data handler
xml_set_character_data_handler($parser3,"char");

$input = ValidateInput($input);
echo "<table style='border-style:solid; width:100%' cellpadding=3><tr><td class='UniFont' height=24 style='vertical-align:baseline; border-style:solid; width:150; font-family:" . '"Arial Unicode MS"' .", Lucida Sans Unicode, Tahoma, Gentium, Verdana, sans-serif; font-size:14px;'>" . strip_tags(postvar("txtInput")) . "</td><td style='border-style:solid; font-family:" . '"Arial Unicode MS"' .", Gentium, Lucida Sans Unicode, Verdana, sans-serif; font-size:14px;'>";
if ($input != "" && $input != " ") {
echo "Orthographic form";
}
echo "</td>";
$input = CleanUp($input);

  //Open XML file
$fq=fopen($xmlpath,"r");
  //Read data
$My_XML_Handler="start2";
while ($data2=fread($fq,4096))
  {
  xml_parse($parser3,$data2,feof($fq)) or 
  die (sprintf("XML Error: %s at line %d", 
  xml_error_string(xml_get_error_code($parser3)),
  xml_get_current_line_number($parser3)));
  }
//Free the XML parser
xml_parser_free($parser);
//Free the XML parser
xml_parser_free($parser3);


echo "<tr><td class='UniFont' height=24 style='vertical-align:baseline; border-style:solid; width:150; font-family:" . '"Arial Unicode MS"' .", Lucida Sans Unicode, Tahoma, Gentium, Verdana, sans-serif; font-size:14px;'>" . FormatCode(mb_ereg_replace('[#/]','',$input)) . "</td><td  class='UniFont' height=24 style='vertical-align:baseline; border-style:solid; width:150; font-family:" . '"Arial Unicode MS"' .", Lucida Sans Unicode, Tahoma, Gentium, Verdana, sans-serif; font-size:14px;'>Clear Symbols</td></tr></table>";

//SVG functions
function createNodeSimple($label, $place, $x, $y)
{
global $x_list;
global $y_list;
$x_list["$place"] = $x;
$y_list["$place"] = $y;

return '<svg:text id="ID' . $place . '" x = "' . $x . '" y = "' . $y . '" style = "font-family:\'Arial Unicode MS\'; text-anchor: middle; font-size: 13px;">' . $label . '</svg:text>';

	  // textNode.setData(String.fromCharCode(0x3C3));
	   //textNode.setData(String.fromCharCode(uni("σ")));

}

function createNode($label, $place, $x, $y)
{
global $x_list;
global $y_list;
$x_list["$place"] = $x;
$y_list["$place"] = $y;
		  	if ($label == "%") {$label="";} //empty $label for Silbengelenk
		  	if ($label == "N") {$label="n̩";} // $label for syllabic resonant
		  	if ($label == "M") {$label="m̩";} // $label for syllabic resonant
		  	if ($label == "L") {$label="l ̩";} // $label for syllabic resonant
		  	if ($label == "R") {$label="ɐ̭";} //empty $label for syllabic resonant
		  	if ($label == "F") {$label="pf";} //$label for pf affricate
		  	if ($label == "E") {$label="ɛ̃";} // $label for nasal e
		  	if ($label == "O") {$label="ɔ̃";} // $label for nasal o
		  	if ($label == "B") {$label="bʲ";} // $label for nasal e
		  	if ($label == "P") {$label="pʲ";} // $label for nasal o
		  	if ($label == "W") {$label="vʲ";} // $label for nasal e
		  	if ($label == "ɱ") {$label="mʲ";} // $label for nasal e



return '<svg:text id="ID' . $place . '" x = "' . $x . '" y = "' . $y . '" style = "font-family:\'Arial Unicode MS\'; text-anchor: middle; font-size: 13px;"> ' . $label . ' </svg:text>';

}

function connect($start, $finish, $offset_y1, $offset_y2)
{
global $x_list;
global $y_list;
	$x1 = $x_list["$start"];
	$y1 = $y_list["$start"] + 5;	
	$x2 = $x_list["$finish"];
	$y2 = $y_list["$finish"] -12;
	
	if (!$offset_y1) {$offset_y1=0;}
	if (!$offset_y2) {$offset_y2=0;}
	$y1+=$offset_y1;
	$y2+=$offset_y2;
	
	return   '<svg:line x1 = "' . $x1 . '" y1 = "' . $y1 . '" x2 = "' . $x2 . '" y2 = "' . $y2 . '" style = "stroke-width: 2; stroke: black;"/>';
}
?>

</div>
</td></tr>
</table>

</form>
<form method="post" action="IE_Glossary.php"><input type="hidden" ID="nav" name="nav" value=""></input></form>
<?php 
if ($root_final!="")
{echo "See this root in other languages:<br><ul>" . $root_final . "</ul>";} 
//echo $input;
?>
<br>
<?php 
//$word = "#bi@#te#";
$word = $input;
$word = mb_ereg_replace('#$', '', $word);
$word = mb_ereg_replace('pf', 'F', $word);
$word = mb_ereg_replace('^#', '', $word);
$word = mb_ereg_replace('/', '', $word);
$syllables = mb_split('#',$word);
$myFile = "base.svg";
$fh = fopen($myFile, 'r');
$theData = fread($fh, filesize($myFile));
fclose($fh);

$myFile = "testFile.svg";
$fh = fopen($myFile, 'w') or die("can't open file");

$stringData = "$theData";
$syllable_offset = 0;
$syllable_count = 0;
$pendulum = 0;
$gelenk = false;
foreach ($syllables as &$syllable) {
	$syllable_offset = 180*$syllable_count;
	//Make syllable root
	$stringData = $stringData . createNodeSimple("σ","stop:$syllable_count", 60 + $syllable_offset , 40) . "\n";
	$stringData = $stringData . createNode("Onset","otop:$syllable_count", 40 + $syllable_offset , 80) . "\n";
	$stringData = $stringData . createNode("Rime","rtop:$syllable_count", 110 + $syllable_offset , 80) . "\n";
	$stringData = $stringData . createNode("Nucleus","ntop:$syllable_count", 100 + $syllable_offset , 120) . "\n";
	$stringData = $stringData . createNode("Coda","ctop:$syllable_count", 160 + $syllable_offset , 120) . "\n";
	$stringData = $stringData . connect("stop:$syllable_count","otop:$syllable_count",0,0) . "\n";
	$stringData = $stringData . connect("stop:$syllable_count","rtop:$syllable_count",0,0) . "\n";
	$stringData = $stringData . connect("rtop:$syllable_count","ntop:$syllable_count",0,0) . "\n";
	$stringData = $stringData . connect("rtop:$syllable_count","ctop:$syllable_count", 0 ,0) . "\n";
    $syllable = mb_ereg_replace('([^EOLMNaeioyuɐəɛɶɔʏʊɪː]*)(ɨ|ɐ|[EOLMNayeiouəɛɶɔʏʊɪ]+ː?)(.*)', '\1#\2#\3', $syllable);
    $syllable = mb_ereg_replace('#$', '', $syllable);
	$parts = mb_split('#',$syllable);
	$part_count = 1;
	foreach ($parts as &$part) {
		$phon_count = 1.5;
		$unsplit_part = $part ;
		$part = mb_ereg_replace('(.)', '\1#', $part);
		$part = mb_ereg_replace('#$', '', $part);
		$phones = mb_split('#',$part);
		if ($phones[0]!="") //avoid creating node if phoneme empty, e.g. in naked syllable like ei-ER
		{
			$num_phones = count($phones);
			if ($unsplit_part=="aɪ" || $unsplit_part=="aʊ" || $unsplit_part=="ɔɪ" || mb_substr($unsplit_part,mb_strlen($unsplit_part)-1,1)=="ː")
			{
				$diphthong=15;
				if(mb_substr($unsplit_part,mb_strlen($unsplit_part)-1,1)!="ː") //only put arch on real diphthongs, not long vowels
				{$stringData = $stringData . createNode('‿', 'di$syllable_count:$phon_count:$part_count', (60*($part_count-1)+(23 + (60/$num_phones)*($phon_count-1)) + $syllable_offset), 202) . "\r\n";}
			}
			else
			{
				$diphthong=0;
			}
			switch ($part_count) 
			{
			case 1:
				$letter="C";
				$root="otop:$syllable_count";
				break;
			case 2:
				$letter="V";
				$root="ntop:$syllable_count";
				break;
			default:
				$letter="C";
				$root="ctop:$syllable_count";
				break;
			}
			foreach ($phones as &$phone) {
				if ($gelenk == true)
				{
					$gelenk_offset = -30;
				}
				switch ($phone) //add affricate arch
				{
				case "%": //Silbengelenk
					$gelenk_offset = 30;
					$gelenk = true;
					break;
				case "ʦ"://ts affricate
					$stringData = $stringData . createNode('‿', "ts$syllable_count" . ":" . $phon_count . ":" . $part_count, (60*($part_count-1)+(10 + (60/$num_phones)*($phon_count-1)) + $syllable_offset) , 202) . "\r\n";
					break;
				case "F"://pf affricate
					$stringData = $stringData . createNode('‿', "pf$syllable_count" . ":" . $phon_count . ":" . $part_count, (60*($part_count-1)+(10 + (60/$num_phones)*($phon_count-1)) + $syllable_offset) , 202) . "\r\n";
					break;
				default:
					if (	$gelenk == false)
						{$gelenk_offset = 0;}
						{$gelenk = false;}
				}
				$stringData = $stringData . createNode($phone, "F" . $syllable_count . ":" . $phon_count . ":" . $part_count,  (60*($part_count-1)+$diphthong+(10 + ((60-$diphthong*$phon_count)/$num_phones)*($phon_count-1)) + $syllable_offset + $gelenk_offset), 200) . "\r\n";
				if ($gelenk_offset < 0)
				{
					$stringData = $stringData . connect("L" . intval($syllable_count - 1) . ":Gelenk","$root",-20,20) . "\r\n";					
					
				}
				elseif ($gelenk_offset > 0)
				{
					$stringData = $stringData . createNode($letter, "L" . $syllable_count . ":Gelenk", (60*($part_count-1)+(10 + (60/$num_phones)*($phon_count-1)) + $syllable_offset+30), 160) . "\r\n";
					$stringData = $stringData . connect("L$syllable_count:Gelenk","F$syllable_count:$phon_count:$part_count",0,0) . "\r\n";					
					$stringData = $stringData . connect("L$syllable_count:Gelenk","$root",-20,20) . "\r\n";					
				}
				else
				{
					$stringData = $stringData . createNode($letter, "L" . $syllable_count . ":" . $phon_count . ":" . $part_count, (60*($part_count-1)+(10 + (60/$num_phones)*($phon_count-1)) + $syllable_offset), 160) . "\r\n";
					$stringData = $stringData . connect("L$syllable_count:$phon_count:$part_count","F$syllable_count:$phon_count:$part_count",0,0) . "\r\n";					
					$stringData = $stringData . connect("L$syllable_count:$phon_count:$part_count","$root",-20,20) . "\r\n";
				}							
				//echo  (60*($part_count-1)+(20 + (60/$num_phones)*($phon_count-1))) . "createNode('$phone', '$syllable_count:$phon_count:$part_count:'," .  (40+60*($part_count-1)+(($phon_count-1)*$pendulum)*20  + $syllable_offset) . ", 200);\n<br>";
				$phon_count++;
				$pendulum = $pendulum*-1;
			}
		}
		$part_count++;
	}
	$syllable_count++;
}

$stringData = $stringData . " </svg:g></svg:svg>";
fwrite($fh, $stringData);

fclose($fh);

echo "<iframe src='testFile.svg' width='750' height='220' name='imap'></iframe>";
echo "<p>Can't see the syllable analysis graph? Download <a href='http://www.adobe.com/svg/viewer/install/main.html'>Adobe SVG Viewer</a> or try the latest version of <a href='http://www.mozilla.com/firefox/'>Firefox</a>.</p>";
echo "<p><font color='red'><b>New:</b></font> automatic transcription is now available as Perl scripts: [<a href='download/Text2IPA_German.zip' target='new'>German</a>] [<a href='download/Text2IPA_Polish.zip' target='new'>Polish</a>]</p>";
echo "<br><p>&copy; 2008-2014 <a href='http://corpling.uis.georgetown.edu/amir/'>Amir Zeldes </a></p>";
?>

</body>
</html>