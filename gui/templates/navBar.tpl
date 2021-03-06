{*
	Testlink Open Source Project - http://testlink.sourceforge.net/
	$Id: navBar.tpl,v 1.53 2010/10/28 06:40:14 mx-julian Exp $
	Purpose: smarty template - title bar + menu

	rev :
		20100212 - asimon - BUGID 3950 - changed navbar design -  test project selector
		                                 was put more into user focus. no multiple lines
		                                 on smaller screens (1280px) with large project names
		20100212 - eloff - BUGID 3103 - remove js-timeout alert in favor of BUGID 3088
		20100131 - franciscom - moved get_docs() to javascript library
		20090902 - timeout warning 
		20080504 - access to local documentation
		20080211 - changes action for user management
		20070331 - BUGID 760 - added truncate to fix
*}

{lang_get var="labels"
          s="title_events,event_viewer,home,testproject,title_specification,title_execute,
             title_edit_personal_data,th_tcid,link_logout,title_admin,
             search_testcase,title_results,title_user_mgmt"}
{assign var="cfg_section" value=$smarty.template|replace:".tpl":""}
{config_load file="input_dimensions.conf" section=$cfg_section}

{include file="inc_head.tpl" openHead="yes"}
</head>
<body style="min-width: 800px;">


<div style="height:118px; padding:12px 0px;">
	<div style="height="80px; width=100%; border:1px solid #a00000 !important" >
		
		<div class="menu_title" style=" float:right;">
			<span class="bold" style="font:Trebuchet MS; color:#2E7FC1; font-size:21px; font-weight:bold; " >Testcase Management System 1.9.3 {$VERSION}</span> : {$gui->whoami|escape}</span>
			[<a href='lib/usermanagement/userInfo.php' target="mainframe" accesskey="i"
			tabindex="6">{$labels.title_edit_personal_data}</a>
			| <a href="logout.php" target="_parent" accesskey="q">{$labels.link_logout}</a>			
			| <a href="TCMS_FAQS.pdf" target="_blank" accesskey="q" title="FAQ">FAQ</a>
			| <a  href="release_notes_qtms1.0.pdf" target="_blank" accesskey="q" title="Release Notes">Release Notes</a> ]

		</div>
		<div style="display="block; float:left; height=70px" >
		<a href="index.php" target="_parent" >
		<img alt="Company logo"	title="logo" style="width:211px; height:31px; padding:5px;" 
		src="{$smarty.const.TL_THEME_IMG_DIR}{$tlCfg->company_logo}" /></a>
		</div>
	</div>
	<div style="clear">&nbsp;</div>
	
	<div class="menu_bar" style="margin:-10px 3px 0 3px; padding:0 3px 3px 3px; height:auto">

	{if $gui->TestProjects != ""}
	<div style="display: block; margin:0; float: right;">
		<form style="display:inline" name="productForm" action="lib/general/navBar.php" method="get">
			 {$labels.testproject}
			<select style="font-size: 80%;position:relative; top:-1px;" name="testproject" onChange="this.form.submit();">
	      	{foreach key=tproject_id item=tproject_name from=$gui->TestProjects}
	  		  <option value="{$tproject_id}" title="{$tproject_name|escape}"
	  		    {if $tproject_id == $gui->tprojectID} selected="selected" {/if}>
	  		    {$tproject_name|truncate:#TESTPROJECT_TRUNCATE_SIZE#|escape}</option>
	  		{/foreach}
			</select>
		</form>
	</div>

	{$session.testprojectTopMenu}

	
	
{/if}

{if $gui->tprojectID}
	{if $gui->grants->view_testcase_spec == "yes"}
		<form style="display:inline" target="mainframe" name="searchTC" id="searchTC"
		      action="lib/testcases/archiveData.php" method="get">
		<input style="font-size: 80%; position:relative; top:-1px;" type="text" size="{$gui->searchSize}"
		       title="{$labels.search_testcase}" name="targetTestCase" value="{$gui->tcasePrefix}" />
    	{* useful to avoid a call to method to get test case prefix in called page*}
		<input type="hidden" id="tcasePrefix" name="tcasePrefix" value="{$gui->tcasePrefix}" />
		<img src="{$smarty.const.TL_THEME_IMG_DIR}/magnifier.png"
		     title="{$labels.search_testcase}" alt="{$labels.search_testcase}"
		     onclick="document.getElementById('searchTC').submit()" class="clickable" 
		     style="position:relative; top:2px;" />
		<input type="hidden" name="edit" value="testcase"/>
		<input type="hidden" name="allow_edit" value="0"/>
		</form>
	{/if}
{/if}

</div> 

</div>
<div style="clear">&nbsp;</div>

{if $gui->updateMainPage == 1}
{literal}
<script type="text/javascript">
	parent.mainframe.location = "{/literal}{$basehref}{literal}lib/general/mainPage.php";
</script>
{/literal}
{/if}

</body>
</html>
