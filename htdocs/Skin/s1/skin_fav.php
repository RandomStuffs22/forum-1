<?php

class skin_fav {



function main($html) {
global $ibforums;
return <<<EOF
	<div class="tableborder">
	<table width="100%" cellpadding="4" cellspacing="1">
    	<tr>
    		<td class="maintitle" colspan="6">��������� ����</td>
    	</tr>
        <tr>
        	<td class="titlemedium" colspan="6">����, � ������� ���� ����� ��������� � ������� ������ ���������� ������</td>
        </tr>
        <tr>
            <td class="pformstrip">����</td>
            <td class="pformstrip">�����</td>
            <td class="pformstrip">���������</td>
            <td class="pformstrip">��������� �</td>
            <td class="pformstrip">�������</td>
            <td class="pformstrip">������� � �����������<br>�� ����������� �� ��. �����</td>
        </tr>
        {$html['new']}
        <tr>
        	<td class="titlemedium" colspan="6">����, � ������� ��� ����� ���������</td>
        </tr>
        	<td class="pformstrip">����</td>
            <td class="pformstrip">�����</td>
            <td class="pformstrip">���������</td>
            <td class="pformstrip">��������� �</td>
            <td class="pformstrip">�������</td>
            <td class="pformstrip">������� � �����������<br>�� ����������� �� ��. �����</td>
        </tr>
        {$html['nonew']}
    </table>
    </div>

EOF;
}


function none() {
global $ibforums;
return <<<EOF

    <tr>
    	<td class="row1" style="text-align: center; padding: 5px; font-weight: 900;" colspan="6">���</td>
    </tr>

EOF;
}


function error($e) {
global $ibforums;
return <<<EOF

	<center>
    <div class="tableborder" style="width: 75%;">
    	<table width="100%" cellpadding="0" cellspacing="1">
        	<tr align="center">
        		<td class="maintitle">Error</td>
        	</tr>
            <tr align="center">
        		<td class="row1" style="padding: 3px;">{$e}</td>
        	</tr>
        </table>
    </div>
    </center><br>

EOF;
}


function topic_row($t) {
global $ibforums;
return <<<EOF
    <tr align="center">
    	<td class="row1" align="left"><a href="{$ibforums->base_url}showtopic={$t['tid']}&view=getnewpost">{$t['title']}</td>
    	<td class="row1"><a href="{$ibforums->base_url}showuser={$t['starter_id']}">{$t['starter_name']}</a></td>
    	<td class="row1"><a href="{$ibforums->base_url}showuser={$t['last_poster_id']}">{$t['last_poster_name']}</td>
    	<td class="row1">{$t['last_post']}</td>
    	<td class="row1"><a href="{$ibforums->base_url}act=fav&amp;topic={$t['tid']}">�������</a></td>
    	<td class="row1"><a href="{$ibforums->base_url}act=fav&amp;topic={$t['tid']}&amp;track=1">������� � �����������</a></td>
	</tr>

EOF;
}


}
?>