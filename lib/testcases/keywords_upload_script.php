<?php
	require('../../config.inc.php');
	require_once('common.php');
	require_once('csv.inc.php');
	require_once('xml.inc.php');
	require_once('../../third_party/phpexcel/reader.php');

	testlinkInitPage($db);
	
	error_reporting(E_ALL);
	
	if(isset($_POST['excel_submitted']) && $_POST['excel_submitted'] == 1){
		$source =  	isset($_FILES['uploadedFile']['tmp_name']) ? $_FILES['uploadedFile']['tmp_name'] : null;
		$data 	= 	new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		$data->read($source);
		
		$xls_rows 				= 	$data->sheets[0]['cells'];
		$xls_row_qty 			= 	sizeof($xls_rows);
		
		$flag	=	false;
		if(empty($xls_rows[1][1])){
			$flag	=	True;
		}elseif(empty($xls_rows[1][2])){
			$flag	=	True;
		}
		
		if($flag){
			echo "Wrong format";
		}else{
			$m	=	0;
			$j	=	0;
		
			for ($datastart = 2; $datastart <= $xls_row_qty; $datastart++) 
			{
				$test_case_and_project	=	trim($xls_rows[$datastart][1]);
				$keywords				=	trim($xls_rows[$datastart][2]);
				
				
				$testproject_prefix 	= 	substr($test_case_and_project,0,strlen(strstr($test_case_and_project, '-',true)));	
				$test_case_id			=	substr($test_case_and_project,strlen($testproject_prefix) + 1);
				
				$sql	=	"SELECT id  FROM testprojects WHERE LOWER(prefix) = '" . trim(strtolower($testproject_prefix)) . "'";
				$rst	=	mysql_query($sql);
				if(mysql_num_rows($rst)){
					
					/* while($rst as $key => $value){
						
					} */
					$rr				=	mysql_fetch_assoc($rst);
					$tproject_id 	=	$rr['id'];
					$internalID 	= 	0;
					
					$query			=	"SELECT DISTINCT NH.parent_id AS tcase_id FROM tcversions TCV, nodes_hierarchy NH WHERE TCV.id = NH.id AND  TCV.tc_external_id = '".$test_case_id."' ";
					$result			=	mysql_query($query);
					if(mysql_num_rows($result) > 0){
						while($row	=	mysql_fetch_assoc($result)){
							$tcase_id		=	$row['tcase_id'];
							$the_path 		= 	array();
							$to_node_id		=	null;
							get_detail($tcase_id,$the_path,$to_node_id,$format = 'full');
							
							$the_path	=	array_reverse($the_path);
							
							if($tproject_id == $the_path[0]['parent_id'])
							{
								$internalID = $tcase_id;
								break;
							}
						}
					}
					if(!empty($internalID)){
						if(!empty($keywords)){
							$a_keyword	=	explode(",",$keywords);
							foreach($a_keyword as $k => $v){
								$keyword_id	=	@mysql_result(mysql_query("select id from keywords where LOWER(keyword) = '".trim(strtolower($v))."' "),0);
								if(!empty($keyword_id)){
									$tt_id	=	@mysql_result(mysql_query("SELECT testcase_id FROM testcase_keywords WHERE testcase_id = '".$internalID."' and keyword_id = '".$keyword_id."'  "),0);
									if(empty($tt_id)){
										$m++;
										echo "<br>".$t_query	=	"INSERT INTO testcase_keywords SET testcase_id = '".$internalID."',keyword_id = '".$keyword_id."' ";
										mysql_query($t_query);
									}else{
										$j++;
										echo "<br>keyword : $v has already entered against id :  $test_case_and_project ";
									}
								}
							}
						}
					}
				}else{
					$j++;
					echo "<br><br><font color='red'>Problem for Full test case </font><br>$test_case_and_project</b> <font color='red'><br>with keywords : </font><b>$keywords<b><br>";
				}
			}
			
			echo "<br><br><b>Success count : $m</b>";
			echo "<br><b>Fail count : $j</b>";
		}
	}
	
	
	function get_detail($tcase_id,&$node_list,$to_node_id=null,$format='full'){
		$query	=	"SELECT * from nodes_hierarchy WHERE id = '".$tcase_id."' ";
		$result	=	mysql_query($query);
		if(mysql_num_rows($result)){
			while($row	=	mysql_fetch_assoc($result)){
				if ($row['parent_id'] != '' && $row['id'] != $to_node_id){
					$node_list[] = array('id' => $row['id'],
						'parent_id' => $row['parent_id'],
						'node_type_id' => $row['node_type_id'],
						'node_order' => $row['node_order'],
						'node_table' => '',
						'name' => $row['name'] );
						
					get_detail($row['parent_id'],$node_list,$to_node_id,$format);
				}
			}
		}
	}
	
	
?>

<script>
		function upload_validation()
		{
		   var filename 	= 	document.getElementById("uploadedFile").value;
		   var filelength 	= 	parseInt(filename.length) - 3;
		   var fileext 		= 	filename.substring(filelength,filelength + 3);
			if(filename =='')
			{
				alert("Please Upload .xls file");
				document.getElementById("uploadedFile").focus();
				return false;
			}
			else if(fileext != "xls")
			{
				alert("Please Upload .xls file only");
				document.getElementById("uploadedFile").focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
	<div class="bodypanel">
		<div class="mid_main">
			<form action="" name="script_excel" id="script_excel" method="POST" enctype="multipart/form-data" onsubmit="return upload_validation();" >
			<table width="60%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="right" style="vertical-align:middle;">Upload Excel File:&nbsp;</td>
					<td style="vertical-align:middle;">
						<input type="file" name="uploadedFile" id="uploadedFile" style="font-size:15px; height:auto; width:140px;"></td>
						<?php 
							if(isset($message))
							{
								echo "<br/>";
							} 
						?>
					<td style="vertical-align:middle;"><input align="left" style="color: #FFF;background-color: #900; font-weight: bold; height:auto; width:auto;" type="submit" name="submit" id="submit" value="Submit" title="Submit"></td>
					<input type='hidden' name='excel_submitted' value='1' />
				</tr>
			</table>
			</form>
		</div>
		<div style="clear:both;"></div>
	</div>