<?PHP
  // Sources.ru Donation Script

  $tMessage = '';
  $right_referer = 'http://www.sources.ru/donate.php';
  $right_referer = 'http://sources/donate.php';

  $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
  $referer = strtolower($referer);

/*
  // Check for proper referer
  
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    if(!preg_match("#sources(\.ru)?/donate\.php#i",$referer)) {
      $error = 'referer';
      header('Location: '.$right_referer);
      exit;
    }
  }
*/




  @header("Content-type: text/html; charset=windows-1251");


//----------------------------------------------------------------- 
// encode_header
// Action: Encode a string according to RFC 1522 for use in headers 
// if it contains 8-bit characters.
// Call: encode_header (string header, string charset)
//
function encode_header ($string, $charset='iso-8859-1') 
{
  // Check for non-ASCII chars in the string:

  if(preg_match("#[\x80-\xFF]#",$string))
  {
    // The string contain non-ASCII characters, encode this!
    return '=?'.$charset.'?B?'.base64_encode($string).'?='; 
  }

  // The string is ASCII-compatible, return as is:
  return $string; 
}


?>

<html>
<head>
<title>������ ������� ���������.RU</title>

<style>
BODY {
	color: #000;
	margin:30px 100px 60px 40px;
	background-color:#FFF;
	text-align:left;
	font-family: Verdana;
	font-size: 12;
}
H1 {
	font-size: 19;
	font-weight:bold;
}
H2 {
	font-size: 14;
	font-weight:bold;
}
P {
	font-size: 12;
	margin-left:25;
	color: #666666
}
TD {
	color: #000;
	text-align:left;
	font-family: Verdana;
	font-size: 12;
}
</style>

<script language="JavaScript"><!--
function gourl(s) {
  window.top.location.href = s;
}
function check() {
  if (document.myform.name.value=='') {
    alert ('����� ��������� ����\n"��� (��� ���)"');
    document.myform.name.focus();
    return false;
  }
  if (document.myform.date.value=='') {
    alert ('����� ��������� ����\n"���� �������"');
    document.myform.date.focus();
    return false;
  }
  if (document.myform.currency.value=='') {
    alert ('����� ��������� ����\n"������"');
    document.myform.currency.focus();
    return false;
  }
  if (document.myform.summ.value=='') {
    alert ('����� ��������� ����\n"�����"');
    document.myform.summ.focus();
    return false;
  }
  if (document.myform.summ.value<= 0) {
    alert ('����� ��������� ����\n"�����"');
    document.myform.summ.focus();
    return false;
  }

  var name     = document.myform.name.value.toLowerCase();
  var date     = document.myform.date.value.toLowerCase();
  var currency = document.myform.currency.value.toLowerCase();
  var summ     = document.myform.summ.value.toLowerCase();
  var email    = document.myform.email.value.toLowerCase();
  var thestr   = ' '+name+' '+date+' '+currency+' '+summ+' '+email;
  var ind = thestr.indexOf("http:");

  if(ind >= 0) {
    alert('Sorry, NO links enabled here!');
    return false;
  }

/*
  if(!confirm('��������� ��� ��� ������������ ��������� ����������!\n'+
              '���� ��� ��������� ���������, ������� OK.\n'+
              '��� �������� ����������� ������� ������.'
    )) {
    return false;
  }
  alert('������� �� ���� ���������!\n���������� � ������� ���������� ��������������.');
*/
  return true;

}
function clearall() {
 document.myform.reset();
 document.myform.name.value='';
 document.myform.date.value='';
 document.myform.currency.value='';
 document.myform.summ.value='';
 document.myform.email.value='';
 return true;
}
//--></script>
<link rel="stylesheet" type="text/css" href="html/calendar/calendar.css">
<script type="text/javascript" src="html/calendar/calendar.js"></script>
<script type="text/javascript" src="html/calendar/calendar-ru.js"></script>
<script type="text/javascript" src="html/calendar/calendar-setup.js"></script>

</head>




<body>

<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr>
<td width=220 valign=middle><a href="/index.html"><img border=0 hspace=12 src="/img/jassy.gif"></a><br>&nbsp;</td>
<td align="center">
  <BR>
  <H1>������ �������</H1>
<br>

<!--TopList COUNTER--><a target=_top
href="http://top.list.ru/jump?from=89876"><script language="JavaScript"><!--
d=document;a='';a+=';r='+escape(d.referrer)
js=10//--></script><script language="JavaScript1.1"><!--
a+=';j='+navigator.javaEnabled()
js=11//--></script><script language="JavaScript1.2"><!--
s=screen;a+=';s='+s.width+'*'+s.height
a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth)
js=12//--></script><script language="JavaScript1.3"><!--
js=13//--></script><script language="JavaScript"><!--
d.write('<img src="http://top.list.ru/counter'+
'?id=89876;t=57;js='+js+a+';rand='+Math.random()+
'" alt="TopList" '+ 'border=0 height=1 width=1>')
if(js>11)d.write('<'+'!-- ')//--></script><noscript><img
src="http://top.list.ru/counter?js=na;id=89876;t=57"
border=0 height=1 width=1
alt="TopList"></noscript><script language="JavaScript"><!--
if(js>11)d.write('--'+'>')//--></script></a><!--TopList COUNTER-->

<!--Rambler--><a href="http://counter.rambler.ru/top100/"><img src="http://counter.rambler.ru/top100.cnt?163871" alt="Rambler's Top100" width=1 height=1 border=0></a>

<!-- SpyLOG f:0211 --><script language="javascript"> 
u="u1624.10.spylog.com";d=document;nv=navigator;na=nv.appName;p=1; 
bv=Math.round(parseFloat(nv.appVersion)*100); 
n=(na.substring(0,2)=="Mi")?0:1;rn=Math.random();z="p="+p+"&rn="+rn;y=""; 
y+="<a href='http://"+u+"/cnt?f=3&p="+p+"&rn="+rn+"' target=_blank>"; 
y+="<img src='http://"+u+"/cnt?"+z+ 
"&r="+escape(d.referrer)+"&pg="+escape(window.location.href)+"' border=0 width=1 height=1 alt='SpyLOG'>"; 
y+="</a>"; d.write(y);if(!n) { d.write("<"+"!--"); }//--></script><noscript> 
<a href="http://u1624.10.spylog.com/cnt?f=3&p=1" target=_blank> 
<img src="http://u1624.10.spylog.com/cnt?p=1" alt='SpyLOG' border='0' width=1 height=1 > 
</a></noscript><script language="javascript1.2"><!-- 
if(!n) { d.write("--"+">"); }//--></script><!-- SpyLOG -->

<!-- HotLog --><script language="javascript">
hotlog_js="1.0";hotlog_d=document; hotlog_n=navigator;hotlog_rn=Math.random();
hotlog_n_n=(hotlog_n.appName.substring(0,3)=="Mic")?0:1;
hotlog_r=""+hotlog_rn+"&s=14399&r="+escape(hotlog_d.referrer)+"&pg="+
escape(window.location.href);
hotlog_d.cookie="hotlog=1"; hotlog_r+="&c="+(hotlog_d.cookie?"Y":"N");
hotlog_d.cookie="hotlog=1; expires=Thu, 01-Jan-70 00:00:01 GMT"</script>
<script language="javascript1.1">
hotlog_js="1.1";hotlog_r+="&j="+(navigator.javaEnabled()?"Y":"N")</script>
<script language="javascript1.2">
hotlog_js="1.2";hotlog_s=screen;
hotlog_r+="&wh="+hotlog_s.width+'x'+hotlog_s.height+"&px="+((hotlog_n_n==0)?
hotlog_s.colorDepth:hotlog_s.pixelDepth)</script>
<script language="javascript1.3">hotlog_js="1.3"</script>
<script language="javascript">hotlog_r+="&js="+hotlog_js;
hotlog_d.write("<img src='http://hit2.hotlog.ru/cgi-bin/hotlog/count?'+hotlog_r+'&' border=0 width=1 height=1>")</script>
<noscript><img src="http://hit2.hotlog.ru/cgi-bin/hotlog/count?s=14399" border=0 
width=1 height=1></noscript>
<!-- /HotLog -->


</td>

</tr>
</table>

<br>



<HR>



<?
$name    = 'Incognito';
$date    = date("d.m.Y");
$currency= '';
$summ    = '';
$email   = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  // Get Form Parameters
  $remote_addr = $_SERVER['REMOTE_ADDR'];
  foreach($_POST as $k=>$v) {
    if(is_array($v)) {
      foreach($v as $kk=>$vv) {
        $_POST[$k][$kk] = str_replace("\r","",$vv);
        $_POST[$k][$kk] = str_replace("\n","",$vv);
        $_POST[$k][$kk] = str_replace("<","&lt;",$vv);
        $_POST[$k][$kk] = str_replace(">","&gt;",$vv);
      }
    } else {
      $_POST[$k] = str_replace("\r","",$v);
      $_POST[$k] = str_replace("\n","",$v);
      $_POST[$k] = str_replace("<","&lt;",$v);
      $_POST[$k] = str_replace(">","&gt;",$v);
    }
  }
  $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
  $name    = isset($_POST['name']) ? $_POST['name'] : '';
  $date    = isset($_POST['date']) ? $_POST['date'] : '';
  $currency= isset($_POST['currency']) ? $_POST['currency'] : '';
  $summ    = isset($_POST['summ']) ? $_POST['summ'] : '';
  $email   = isset($_POST['email']) ? $_POST['email'] : '';



  // Validate Form Parameters
  if(!preg_match("#^(\d\d\.){2}(\d{4})$#i",$date)) {
    $tMessage = 'Error. Invalid DATE entered!';
  } else if(!preg_match("#^(WMZ|WME|WMR|WMU|Yandex.Money)$#i",$currency)) {
    $tMessage = 'Error. Invalid CURENCY entered!';
  } else if(!preg_match("#^\d+(\.\d+)*#i",$summ)) {
    $tMessage = 'Error. Invalid SUMM entered!';
  } else if($summ <= 0) {
    $tMessage = 'Error. Invalid SUMM entered!';
  } else if($email) {
    if(!preg_match("#^[\w\d\._-]+@([\w\d\.-])?$#i",$email)) {
// !!! DOES NOT WORK for vot@sources.ru !!!
//      $tMessage = 'Error. Invalid EMAIL entered!';
    }
  }

  $to = 'rswag@sources.ru';
  $from = 'donation@sources.ru';
  $subject = 'Sources.ru Donation';
  $subject = encode_header($subject, 'windows-1251');

  $headers = '';
  $headers .= "From: " . $from . "\n";
  $headers .= "Return-Path: <noreply@sources.ru>\n";
  $headers .= "MIME-Version: 1.0\n";
  $headers .= "Content-Type: text/plain; charset=windows-1251\n";
  $headers .= "Content-Transfer-Encoding: 8bit\n";
   
  $message  = "Sources.ru Donation Report\n\n";

  $message .= "Remote_addr: ".$remote_addr."\n";
  $message .= "Referer:     ".$referer."\n";
  $message .= "Subject:     ".$subject."\n";
  $message .= "Name:        ".$name."\n";
  $message .= "Date:        ".$date."\n";
  $message .= "Currency:    ".$currency."\n";
  $message .= "Summ:        ".$summ."\n";
  $message .= "Email:       ".$email."\n";
//  $message .= "Error:       ".$tMessage."\n";

  // DEBUG
  $debug = '';
//  $debug = "From: " . $from . "<br>\n";
  $debug .= "To: " . $to . "<br>\n";
  $debug .= "Subject: " . $subject . "<br>\n";
  $debug .= str_replace("\n","<br>\n",$headers);
  $debug .= "<br>\n";
  $debug .= str_replace("\n","<br>\n",$message);
  $debug .= "<br>\n";
//  echo $debug;

  if(!$tMessage) {
    // Send Mail to the Receiver
    if(@mail($to, $subject, $message, $headers, '-f'.$from))
    {
      $tMessage = "Thank you for your report!<br><br>Your report is successfully delivered to the server admin.";
    } else {

      // Report error if can't send email
      $error = 'mailerror';
      $tMessage = "Sorry, an error occured!<br><br>Could not send the email.";
    }
  }
}

?>



<a name=home></a>

<H2>
 ������� �� ��� ������� � ������ �������!
</H2>

<P>
 ������ "���������.��" �������� �������������� ��������
 � ���������� ������ ��������� ���������� ��� �������,
 ������������� ������ � 2000 ���� �� ������ ������������ �������.
</p>

<P>
 ���� �� ������ ������� ��������� ������ �������,
 �� � �������������� ������ ����� ��������� ������!
</p>

<P>
<b>�������� �������� ������ �������:</b>

<ul type="square">
  <li><a href="#finance">���������� ������</a></li>
  <li><a href="#prize">�������������� ������ ��� ���������� ��������� � ��������</a></li>
  <li><a href="/advert/">���������� ����� ������� �� ��������� ����� ������</a></li>
</ul>
</P>


<HR>


<a name=finance></a>
<H2>
���������� ������ �������
</H2>

<P>
���� � ��� ���� ����������� ��������, �� ������ �����������
����� ����������������� ��� ��� ����� �� ���� �� ����� ����������� ���������:

<table border=0 cellpadding=8>
 <tr valign="top">
  <td>
   <a target="_blank" href="http://www.webmoney.ru"><b>WebMoney:</b></a>
   <br>
   <br>
   Z293399007548<br>
   E269636861423<br>
   R344321089231<br>
   U365486311204<br>
  </td>
  <td>
   <a target="_blank" href="http://money.yandex.ru/"><b>������.������:</b></a>
   <br>
   <br>
   41001151000887
  </td>
 </tr>
</table>
</p>

<p>
��������� ������ ������ ��������� � �������
<a href="#payment">��� ��������� ������</a>
</p>


<P>
���� �� ������, ����� ��� ������ �� ��� ����������,
�������� ��� � ������������� ���� �������!
<br>
��� ����� ������� � ��������� � ������� ��� ��� �� ����� ������
<a href="http://forum.sources.ru/">forum.sources.ru</a>,
<br>
���� �������������� ��������������� ������:
</p>

<p>
  <FORM name="myform" method=POST onsubmit="return check(this);">
    <table border="0" align="center" width=400 cellspacing="0" cellpadding=4 bgcolor="#eeeef0">
      <tr>
        <td align="center" colspan=2><span style='color:red'><? echo $tMessage; ?></span></td>
      </tr>

      <tr>
        <td align="right"><span style='color:red'>*</font> <strong>���</strong><br>(���&nbsp;���)</td>
        <td><input type="text" size="32" maxlength="32"
            name="name" value="<? echo $name; ?>"></td>
      </tr>
      <tr>
        <td align="right"><span style='color:red'>*</font> <strong>���� �������</strong><br>DD.MM.YYYY</td>
        <td><input type="text" size="32" maxlength="32"
            id="date" name="date" value="<? echo $date; ?>">
          <img src='html/calendar/calendar.gif' id='calendar_start_time' style='cursor: pointer; border: 0px;' title='���������'>
          <script type='text/javascript'><!--
            Calendar.setup({
     	    inputField : 'date', // id of the input field
     	    ifFormat : '%d.%m.%Y', // format of the input field
     	    showsTime : false, // will display a time selector
     	    button : 'calendar_start_time', // trigger for the calendar (button ID)
     	    align : 'Br', // alignment (defaults to 'Bl')
     	    singleClick : true,
     	    timeFormat : 24,
     	    firstDay : 1
            });//-->
          </script> 

        </td>
      </tr>
      <tr>
        <td align="right"><span style='color:red'>*</font><strong>������</strong></td>
        <td><select name="currency">
          <option value="WMZ">WMZ</option>
          <option value="WME">WME</option>
          <option value="WMR">WMR</option>
          <option value="WMU">WMU</option>
          <option value="Yandex.Money">Yandex.Money</option>
          </select>
        </td>
      </tr>
      <tr>
        <td align="right"><span style='color:red'>*</font> <strong>�����</strong></td>
        <td><input type="text" size="32" maxlength="256"
            name="summ" value="<? echo $summ; ?>"></td>
      </tr>
      <tr>
        <td align="right"><strong>E-mail&nbsp;&nbsp;</strong></td>
        <td><input type="text" size="32" maxlength="64" name="email"
            value="<? echo $email; ?>"></td>
      </tr>
      <tr align="center">
        <td colspan=2>
        <span style='color:red'>*</font> - ������������ ��� ���������� ����.
        </td>
      </tr>
      <tr align="center">
        <td align="left">
         <input type="button"
         value="�������� �����" onClick="return clearall();">
        </td>
        <td align="right">
         &nbsp;&nbsp;&nbsp;
         <input type="submit" value="�������� � �������">
        </td>
      </tr>
    </table>
  </form>


</p>


<HR>


<a name=payment></a>
<H2>
��� ��������� ������
</H2>

<P>
   1) <b>���� � ��� ���� ����������� �������
   � ����� �� ��������� ������:
   <a target="_blank" href="http://www.webmoney.ru">WebMoney</a> ���
   <a target="_blank" href="http://money.yandex.ru/">������.������</a></b>,
   <br>
   �� �� ������ ��������� ������ �� ���� �� ��������� ���� ���������
   ���� ����� ���-��������� ��������� �������, ���� � ������� ���������������
   ���������� ���������.
</P>

<P>
   2) <b>���� � ��� ���� ����������� ������� � ������ ��������� ��������</b>,
   <BR>
   �� �� ������ ��������� ��� �������� � ������� ����������� �������� �������,
   ����������� � ����.
   <BR>
   ������������ ��������������� - <b>�� ��������� ������������� ����������!</b>
   <BR>
   ����� �� ������� �������� �� ����� ������, ��� �������� ���� �����!
   <BR>
   �������� ���� ��������� ��������� ������� �������� �������:
   <UL type="square">
   <LI><a target="_blank" href="http://webmoney.ru/rus/cooperation/exchange/onlinexchange/index.shtml">����� �����/������ ������� ����� WebMoney Transfer � ������� ���������</a></LI>
   <LI><a target="_blank" href="http://top.owebmoney.ru/index.php?all=1&cid=1">������� �������� ������� �� owebmoney.ru</a></LI>
   <LI><a target="_blank" href="http://obmenniki.com/">������ ���������. ���������� �������� �������. ����� ���� �����</a></LI>
   <LI><a target="_blank" href="http://cursov.net/">���������� �������������� �������� �������. ����� ������.������, webmoney - wmz, wmr � ������ ������.</a></LI>
   </UL>
</P>

<P>
   3) <b>���� � ��� ������ ��� ����������� ���������</b>,
   <BR>
   �� ��� ������� �� ������ ����� ������� ���� ����� �������!
   ��� ����� ���������� ������������������ �� ����� ���������
   ������� <a target="_blank" href="http://www.webmoney.ru">WebMoney</a> ���
   <a target="_blank" href="http://money.yandex.ru/">������.������</a>.
   �������� ���� ��������� ���� �������, � �� ������� ��������� �����
   ����������� �������!
</P>

<P>
   4) <b>���� �� �� ������ (��� �� ������ �����������) �������� �����������
   ��������</b>,
   <BR>
   �� ����� ������ ������� ������� ������� ����� ����� ��������� ��� ���
   �������� (����� ��������, ����, ��������� ��������, ����� �������� � ��.):

   <UL type="square">
   <LI><a target="_blank" href="https://money.yandex.ru/help.xml">��� ����� ������.������?</a></LI>
   <LI><a target="_blank" href="https://money.yandex.ru/prepaid.xml">��� ������� ���������� �������� ������.������</a></LI>
   <LI><a target="_blank" href="http://webmoney.ru/rus/addfunds/">��� ��������� ������� WebMoney</a></LI>
   <LI><a target="_blank" href="http://webmoney.ru/rus/withdrawfunds/">��� ������� �������� WebMoney</a></LI>
   <LI><a target="_blank" href="http://geo.webmoney.ru/aspx/GeoMain.aspx">���������� WebMoney - �������� �������� ������� � 57 ������� ����</a></LI>
   </UL>
</P>


<HR>


<a name=prize></a>
<H2>
����� ��� ���������� ��������� � ��������
</H2>

<P>
�� ��������� �������� ������������ ����, �������� � ���������
�� ����� ������. ������� ����� ����������� � ����� ������������ ����������
�����, �� ���� �� ���� ����������� ��������� ����������� � �������� ����������
����� ������������� �������, ������ ������ ������� � ���������,
�� ������� ��������� ��� �� ����������� ��������.
</P>

<P>
���� �� ��� ���� ����������� ������ ������������ ������ �������
���� ��������� ��� ������ � �������� ������, �� � ��������������
����������� �� � ���������� ����� �����������.
����� ������� � ����� ������� �� ������ ������� ������� ����� ����������,
�� � ������ �������������� �������������� ������������� ������� �����
����������� ����� ���������� ���������.
</P>

<P>
�� ���� �������� ����������� � ������� ���������:
<BR>
E-mail: 

<A href="mailto:stop@spam.net" 
    onmouseover="this.href='mai'+'lto:'+'vot'+'%40'+'sources.ru?Subject=Project Donation'">
stop@spam.net</A>

<BR>
ICQ: 8754415
<BR>
�������: 8 (910) 400-3246

</P>



</body>
</html>
