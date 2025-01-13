<?php
/*-------------------------------------------- Comments
Purpose			: 	This form will create // only for demo// chart of account
Functionality	:	
JS Functions	:
Created by		:	CTO // Raihan Raju
Creation date 	: 	01.05.2013 //21.12.2024//01.05.2025
Updated by 		: 		
Update date		: 		   
QC Performed BY	:		
QC Date			:	
Comments		:
*/

$account_calculation_method = array(1 => "Copy same amount", 2 => "Previous Trend", 3 => "Same as previous year", 4 => "Increase by fixed %", 5 => "Increase by fixed amount", 6 => "Independent Based");
$account_distribution_method = array(1 => "Apply to next all month", 2 => "Two month same", 3 => "Three month same", 4 => "Six month same");

session_start();
if ($_SESSION['logic_acc']['user_id'] == "") 
{
	header("location:login.php");
	die;
}
require_once('../includes/common.php');
extract($_REQUEST);
$_SESSION['page_permission'] = $permission;
//--------------------------------------------------------------------------------------------------------------------
echo load_html_head_contents("new_prog_test_v2", "../", 1, 1, $unicode, 1, '');





?>
<script>
	if ($('#index_page', window.parent.document).val() != 1) window.location.href = "../logout.php";
	var permission = '<?php echo $permission; ?>';

//--------make convarsion rate--------------
function conversion_rate(rate)
{
	const salary_usd = document.getElementById("text_salary_usd").value*1;
	const salary_bdt = document.getElementById("text_salary_bdt").value*1;
	
	if(salary_bdt==0){
	var bdt=rate*salary_usd;
	document.getElementById("text_salary_bdt").value=bdt;
	}else if(salary_usd==0){
	var usd=rate/salary_bdt;
	document.getElementById("text_salary_usd").value=usd;
	}else{
	document.getElementById("text_salary_bdt").value=0;
	document.getElementById("text_salary_usd").value=0;
	document.getElementById("text_conversion_rate").value=0;
	};     
}

function reset_form_custom()
{
 reset_form('budgetentry_1', '', '', '', '');
}

//-------fnc_employee_data_entry(operation)
function fnc_employee_data_entry(operation)
{
	
	if (form_validation('cbo_company_name*text_employee_name*text_employee_id*text_conversion_rate','Company Name*Employee Name*Employee ID*Conversion Rate')==false) 
	{
		return;
	}
	
	//alert (operation+"ok");return;
	var data="action=save_update_delete&operation=" + operation + get_submitted_data_string('text_emplyee_system_id*cbo_company_name*text_employee_name*text_employee_id*text_date_of_birth*text_emplyee_age*text_company_location*text_salary_usd*text_conversion_rate*text_salary_bdt*hidden_update_id', "../");
	//  alert (operation+"ok");return;
	// console.log(data); return;

	freeze_window(operation);
	http.open("POST", "requires/new_prog_test_v2_controller.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			
		//alert (data);return;
		
		http.send(data);

		http.onreadystatechange = fnc_employee_data_entry_response;
}

function fnc_employee_data_entry_response()
{
	if (http.readyState == 4)
	{
	  var response = trim(http.responseText).split('**');
	  show_msg(response[0]);
	  console.log(response);  
      if (response[0] ==0)
	  {
	    // alert('raju');
	    $("#hidden_update_id").val(response[1])
		
		

	  }
	// else if (response[0] == 1)
	//    {
	// 	// show_list_view('','ac_reload_list','list_view_div','requires/new_prog_test_v2_controller','');		

				
	//   } else {

				
	// 			//alert("data Delete Successfully");
	// 		}


	  if (response[0] != 10)
	   {
		
		set_button_status(1, permission, 'fnc_employee_data_entry', 1);
	   }
    //    load_drop_down('','load_drop_down_location','location_td','requires/new_prog_test_v2_controller','');
	   show_list_view('','ac_reload_list','list_view_div','requires/new_prog_test_v2_controller','');
	   release_freezing();
	}
}
    
function populate_list_data(hidden_update_id)
{
	
    get_php_form_data(hidden_update_id,"populate_employee_info","requires/new_prog_test_v2_controller");

	set_button_status(1, permission, 'fnc_employee_data_entry', 1);
	// get_php_form_data(cbo_company_id+"**"+batch_id, "populate_buyer_order_for_batch", "requires/chemical_dyes_issue_controller");
	

}
function search_multiple_emplyee_popup()
{
	// alert("search_multiple_emplyee");return;
	// if (form_validation('text_emplyee_system_id','System')==false) 
	// {
	// 	return;
	// }
	//alert("search_multiple_emplyee");return;

	//('EmailBox', 'iframe', 'controller, + action + field_id nea jabe+ ifreame_heading_name,+'width,height,center=1,resize=0,scrolling=0','')
	emailwindow=dhtmlmodal.open('EmailBox', 'iframe', 'requires/new_prog_test_v2_controller.php?action=search_multiple_emplyee_popup'+'&text_emplyee_system_id='+ $('#text_emplyee_system_id').val(),'Emplyee Search', 'width=750px,height=400px,center=1,resize=0,scrolling=0','')

	emailwindow.onclose=function(){
		
		//var theform=this.contentDoc.forms[0] 
		var employee_id=this.contentDoc.getElementById("popup_id").value;
		//  alert (employee_id);return;

		get_php_form_data(employee_id,"populate_employee_info","requires/new_prog_test_v2_controller");

        set_button_status(1, permission, 'fnc_employee_data_entry', 1);
	}
}

//function location_load_drop_down(location)
//{
	//load_drop_down('requires/new_prog_test_v2_controller','load_drop_down_location', 'location_td');
	//load_drop_down('','load_drop_down_location','location_td','requires/new_prog_test_v2_controller','');

	
	
//}
	

	//----------------------------------------------------------
</script>

<body>
	<div align="center" style="width:100%;">
    <?= load_freeze_divs("../", $permission); ?>
		<fieldset style="width:950px;height:auto;">
			<legend>Budget Entry_v2 (for Demo)</legend>
			<form id="budgetentry_1" autocomplete="off">
            

				<table width="900">
					<tr>
						<td>System</td>
						<td>
							<input id="text_emplyee_system_id" type="text"  class="text_boxes" style="width:150px"tabindex="17" ondblclick="search_multiple_emplyee_popup()" placeholder="Doble click/Write/Browse" title="Allowed Characters:">
							<input type="hidden" id="hidden_update_id">
						</td>
					</tr>
					<tr>
						<td width="120" align="left" class="must_entry_caption" title="Must Entry Field.">
							<font color="blue">
								<strong>Company Name</strong>
							</font>
						</td>
						<td>
							<?php
							echo create_drop_down("cbo_company_name", 160, "select comp.company_name,comp.id from lib_company comp where comp.is_deleted=0  and comp.status_active=1  $company_cond order by comp.company_name", "id,company_name", 1, "-- Select --", $selected, "load_drop_down('requires/new_prog_test_v2_controller', this.value, 'load_drop_down_location', 'location_td');");
							?>
						</td>

						<td width="120" align="left" class="must_entry_caption" title="Must Entry Field.">
							<font color="blue">
								<strong>Employee Name</strong>
							</font>
						</td>

						<td>
							<input id="text_employee_name" type="text" class="text_boxes">
						</td>

						
						<td width="120" align="left" class="must_entry_caption" title="Must Entry Field.">
							<font color="blue">
								<strong>Employee ID</strong>
							</font>
						</td>
						<td>
							<input id="text_employee_id" type="text" class="text_boxes_numeric">
						</td>
					</tr>
					<tr>
						<td>DOB</td>
						<td>
							<input id="text_date_of_birth" type="text" placeholder="date" class="datepicker" style="width:150px">
						</td>

						<td>Age</td>
						<td>
							<input id="text_emplyee_age" type="text" placeholder="age" class="text_boxes_numeric">
						</td>

						<td >Location</td>
						<td id="location_td">
							<!-- <input id="text_company_location" type="text" class="text_boxes" placeholder="-- Select --"> -->
							<?php
			
								echo create_drop_down( "text_company_location", 140,"","id,location_name", 1, "-- Select --",$selected,"",'','','','','','',$new_conn,"");
							?>
						</td>
					</tr>
					<tr>
						<td>Salary [USD]</td>
						<td>
							<input id="text_salary_usd" type="text" placeholder="" class="text_boxes_numeric" step="0.01" style="width:150px" required>
						</td>

						
						<td width="120" align="left" class="must_entry_caption" title="Must Entry Field.">
							<font color="blue">
								<strong>Conversion Rate [BDT]</strong>
							</font>
						</td>
						<td>
							<input id="text_conversion_rate" type="text" placeholder="" class="text_boxes_numeric" step="0.01" onchange="conversion_rate(this.value)" required>

						</td>

						<td>Salary [BDT]</td>
						<td>
							<input id="text_salary_bdt" type="text" placeholder="" class="text_boxes_numeric">
						</td>
					</tr>

					<tr>

						<!-- -----------save/update/delete------------- -->
                        <tr>
						<td colspan="6" align="center" height="35" valign="middle" class="button_container">
							<input type="hidden" id="hidden_update_id" name="update_id">
							<?php
							echo load_submit_buttons($permission, "fnc_employee_data_entry", 0, 0, "reset_form_custom()", 1);
							?>
						</td>
					</tr>

					

					

				</table>
			</form>
				<div id="list_view_div"  style="height:200px;overflow-y:scroll">
                   <?php

						$comp = return_library_array("select id, company_name from lib_company", 'id', 'company_name');
						$sql = "SELECT * From new_test_data_entry WHERE status_active = 1 AND is_deleted = 0   order by id desc";
						

						$arr=array (1=>$comp);
						echo  create_list_view ( "list_view_table", "SYS ID,COMPANY NAME,EMPLOYEE NAME,EMPLOYEE ID,DOB,EMPLYEE AGE,LOCATION,SALARY USD,SALARY BDT", "50,150,100,100,100,50,120,80,100","900","270",0, $sql, " populate_list_data", "id","", 1, "0,COMPANY_ID,0,0,0,0,0,0,0", $arr , "ID,COMPANY_ID,EMPLOYEE_NAME,EMPLOYEE_ID,DATE_OF_BIRTH,EMPLYEE_AGE,COMPANY_LOCATION,SALARY_USD,SALARY_BDT", "new_prog_test_v2_controller",'setFilterGrid("list_view_table",-1);','0,0,0,0,3,0,0,0,0' ) ;
										
				   ?>	        
				</div>
       </fieldset>		
	</div>
	<script src="../includes/functions_bottom.js" type="text/javascript"></script>
	
</body>

</html>