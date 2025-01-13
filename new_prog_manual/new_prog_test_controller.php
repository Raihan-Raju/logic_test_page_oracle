<?php
header('Content-type:text/html; charset=utf-8');
session_start();
if( $_SESSION['logic_acc']['user_id'] == "" ){ header("location:login.php");die;}

include('../../includes/common.php');

$data=$_REQUEST['data'];
$action=$_REQUEST['action'];

if($action=="chart_of_account_popup")
{
	echo load_html_head_contents("Popup Info","../../", 1, 1,$unicode,1);
	extract($_REQUEST);
?>
<script>
function js_set_value(id)

{
	document.getElementById('id_field').value=id;
	parent.emailwindow.hide();
}
</script>
</head> 
<input type="hidden" name="id_field" id="id_field"/>
</html>	
<?php
			 //-------------------START POP UP LIST VIEW & INSERT IN ACCOUNT CODE ----------------------------------------
	 $sql="SELECT FORM new_test_data_entry ";	
	 
		$arr=array (3=>$accounts_main_group);
		echo  create_list_view ( "list_view", "A/C Code,A/C Description,Sub Group,Main Group", "100,180,200,150","700","270",0, $sql, "js_set_value", "id","", 1, "0,0,0,main_group", $arr , "ac_code,ac_description,sub_group,main_group", "chart_of_account_controller",'setFilterGrid("list_view",-1);','0,0,0,0' ) ;
}






			
if ($action=="save_update_delete")
{
	$process = array($_POST );
	//  echo "<pre>";
	//  print_r($process);
	extract(check_magic_quote_gpc( $process ));
	//echo $operation;die;


	// echo "<pre>";
	// print_r($process);
	// echo "<pre>"; die;

	 // echo "empoloyee name: ".$text_employee_name;die;

 // insert Here======= Table===NEW_TEST_DATA_ENTRY
	if ($operation==0) 
	{
		
		$con = connect();
		//echo "sure";
		$field_array="id, company_id, employee_name, employee_id, date_of_birth, emplyee_age, company_location, salary_usd, conversion_rate,salary_bdt, inserted_by, insert_date, status_active, is_deleted";
		$id = return_next_id( "id", "new_test_data_entry", 1);

		$data_array ="(".$id.",".$cbo_company_name.",".$text_employee_name.",".$text_employee_id.",".$text_date_of_birth.",".$text_emplyee_age.",".$text_company_location.",".$text_salary_usd.",".$text_conversion_rate.",".$text_salary_bdt.",".$_SESSION['logic_acc']['user_id'].",'".$pc_date_time."',1,0)";
		
		//echo "10** insert into new_test_data_entry($field_array)values".$data_array;

		// echo $data_array; die;

		$rID1=sql_insert("new_test_data_entry",$field_array,$data_array,0);
		
		if($db_type==2 || $db_type==1)
			{
			 if($rID1 )
			    {
					oci_commit($con);   
				    echo "0**".$id;
				}
			else{
					oci_rollback($con);
				    echo "10** not saved";
				}
			}
		disconnect($con);
		die;
	}

//update=================

	if($operation==1){

		$con = connect();
		
		$field_array="company_id*employee_name*employee_id*date_of_birth*emplyee_age*company_location*salary_usd*conversion_rate*salary_bdt*updated_by*update_date";
		
     //  echo $cbo_company_name;

		$data_array ="".$cbo_company_name."*".$text_employee_name."*".$text_employee_id."*".$text_date_of_birth."*".$text_emplyee_age."*".$text_company_location."*".$text_salary_usd."*".$text_conversion_rate."*".$text_salary_bdt."*".$_SESSION['logic_acc']['user_id']."*'".$pc_date_time."'";

		//echo $hidden_update_id; die;
		

	
		$hidden_update_id = str_replace("'","",$hidden_update_id );

	//  echo $hidden_update_id; die;
		
		$rID = sql_update("new_test_data_entry", $field_array, $data_array, "id", "" . $hidden_update_id . "", 1);

		// $rID = execute_query(bulk_update_sql_statement( "new_test_data_entry", "id", $field_array, $data_array, $hidden_update_id ));

	
		// echo $rID."raju2";die;

	

		if($db_type==2 || $db_type==1 )
			{
			 if($rID )
			    {
					oci_commit($con);   
				    echo "1**".$hidden_update_id;
				}
			else{
					oci_rollback($con);
				    echo "10**Not updated";
				}
			}
		disconnect($con);
		die;
	}
	//Delete===========	
	if ($operation==2){
		$con = connect();
          
		$field_array="updated_by*update_date*status_active*is_deleted";
   
		 
		$data_array ="".$_SESSION['logic_acc']['user_id']."*'".$pc_date_time."'*"."0*1";
		$hidden_update_id = str_replace("'","",$hidden_update_id );

		$rID = sql_update("new_test_data_entry", $field_array, $data_array, "id", "" . $hidden_update_id . "", 1);

        // echo $rID;die;

		if($db_type==2 || $db_type==1 )
			{
			 if($rID)
			    {
					oci_commit($con);   
				    echo "2**";
				}
			else{
					oci_rollback($con);
				    echo "10**not deleted";
				}
			}
		disconnect($con);
		die;
	}

	
}

if($action=="populate_employee_info")
{
	//echo $data;
	$sql = "SELECT * From new_test_data_entry WHERE id=$data and status_active = 1 AND is_deleted = 0";
	$result = sql_select($sql);
    // print_r($result);
	foreach($result as $row)
	{
		echo "document.getElementById('text_employee_name').value = '".$row[csf('employee_name')]."';\n";
		echo "document.getElementById('text_employee_id').value = '".$row[csf('EMPLOYEE_ID')]."';\n";
		echo "document.getElementById('text_date_of_birth').value= '".change_date_format($row[csf("DATE_OF_BIRTH")])."';\n";
		echo "document.getElementById('text_emplyee_age').value = '".$row[csf('EMPLYEE_AGE')]."';\n";
		echo "document.getElementById('text_company_location').value = '".$row[csf('COMPANY_LOCATION')]."';\n";
		echo "document.getElementById('text_salary_usd').value = '".$row[csf('SALARY_USD')]."';\n";
		echo "document.getElementById('text_conversion_rate').value = '".$row[csf('CONVERSION_RATE')]."';\n";
		echo "document.getElementById('text_salary_bdt').value = '".$row[csf('SALARY_BDT')]."';\n";
		echo "document.getElementById('text_emplyee_system_id').value = '".$row[csf('ID')]."';\n";
		echo "document.getElementById('hidden_update_id').value = '".$row[csf('ID')]."';\n";
		//echo "set_button_status(1, permission, 'fnc_employee_data_entry', 1);\n";
	}
	
}
if($action=="ac_reload_list")
{
	$comp = return_library_array("select id, company_name from lib_company", 'id', 'company_name');
	$sql = "SELECT * From new_test_data_entry WHERE status_active = 1 AND is_deleted = 0   order by id desc";
	

	$arr=array (1=>$comp);
	echo  create_list_view ( "list_view_table", "SYS ID,COMPANY NAME,EMPLOYEE NAME,EMPLOYEE ID,DOB,EMPLYEE AGE,LOCATION,SALARY USD,SALARY BDT", "50,150,100,100,100,50,120,80,100","900","270",0, $sql, " populate_list_data", "id","", 1, "0,COMPANY_ID,0,0,0,0,0,0,0", $arr , "ID,COMPANY_ID,EMPLOYEE_NAME,EMPLOYEE_ID,DATE_OF_BIRTH,EMPLYEE_AGE,COMPANY_LOCATION,SALARY_USD,SALARY_BDT", "new_prog_test_v2_controller",'setFilterGrid("list_view_table",-1);','0,0,0,0,3,0,0,0,0' ) ;
							
}

?>