<?php
/*-------------------------------------------- Comments
Purpose			: 	This form will create // only for demo// chart of account
Functionality	:	
JS Functions	:
Created by		:	CTO // Raihan Raju
Creation date 	: 	01.05.2013 //21.12.2024
Updated by 		: 		
Update date		: 		   
QC Performed BY	:		
QC Date			:	
Comments		:
*/

$account_calculation_method = array(1 => "Copy same amount", 2 => "Previous Trend", 3 => "Same as previous year", 4 => "Increase by fixed %", 5 => "Increase by fixed amount", 6 => "Independent Based");
$account_distribution_method = array(1 => "Apply to next all month", 2 => "Two month same", 3 => "Three month same", 4 => "Six month same");

session_start();
if ($_SESSION['logic_acc']['user_id'] == "") {
	header("location:login.php");
	die;
}
require_once('../includes/common.php');
extract($_REQUEST);
$_SESSION['page_permission'] = $permission;
//--------------------------------------------------------------------------------------------------------------------
echo load_html_head_contents("Budget Entry", "../", 1, 1, $unicode, 1, '');

//$ac_code_length = return_field_value("ac_code_length","lib_company","id = ".$_SESSION['logic_acc']["user_id"]." and status_active = 1 and is_deleted=0");



?>
<script>
	if ($('#index_page', window.parent.document).val() != 1) window.location.href = "../logout.php";
	var permission = '<?php echo $permission; ?>';



	function conversion_rated(rate) {
		// alert(rate);
		// var salary_usd = document.getElementById('#text_salary_usd').value()*1;
        // var salary_bdt =document.getElementById('#text_salary_bdt').value()*1;

		var salary_usd = $("#text_salary_usd").val() * 1;
		var salary_bdt = $("#text_salary_bdt").val() * 1;

		
		if(salary_bdt==0){
			var x = salary_usd * rate;
			document.getElementById('text_salary_bdt').value = x;
		}else if(salary_usd==0){
			var y = salary_bdt / rate;
			document.getElementById('text_salary_usd').value = y;
		}else{
			document.getElementById('text_salary_bdt').value = 0;
			document.getElementById('text_salary_usd').value = 0;
			document.getElementById('text_conversion_rate').value = 0;
		}


		
		// var salary_usd = $("#text_salary_usd").val() * 1;
		// var salary_bdt = $("#text_salary_bdt").val() * 1;

		// var x = salary_usd * rate;
		// var y = salary_bdt / rate;

		// document.getElementById('text_salary_bdt').value = x;
		// document.getElementById('text_salary_usd').value = y;

		//  $("#text_salary_usd").val(x)*1;
		//  $("#text_salary_bdt").val(y)*1;

	}

	function reset_form_custom() {
		reset_form('budgetentry_1', '', '', '', '');

	}

	function fnc_employee_data_entry(operation) {

		var data = "action=save_update_delete&operation=" + operation +
			get_submitted_data_string('text_emplyee_system_id*cbo_company_name*text_employee_name*text_employee_id*text_date_of_birth*text_emplyee_age*text_company_location*text_salary_usd*text_conversion_rate*text_salary_bdt*hidden_update_id', "../");

		// console.log(data) return;
		freeze_window(operation);
		http.open("POST", "requires/new_prog_test_controller.php", true);
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		// alert (data);return;


		http.send(data);

		http.onreadystatechange = fnc_employee_data_entry_response;
	}



	function fnc_employee_data_entry_response() {
		if (http.readyState == 4) {

			var response = trim(http.responseText).split('**');
			//  alert(response);return;

			// if (response[0].length>2) response[0]=10;
			// show_list_view( document.getElementById('populate_budget_list1').value,'populate_budget_list1','populate_budget_list','requires/budget_entry_controller','');

			//show_msg(response[0]);


			if (response[0] == 0) {

				show_list_view('','ac_reload_list','list_view_div','requires/new_prog_test_controller','');

				show_msg(response[0]);
				release_freezing();
				//alert("data saved Successfully");
				$("#hidden_update_id").val(response[1])

			} else if (response[0] == 1) {
				show_list_view('','ac_reload_list','list_view_div','requires/new_prog_test_controller','');
				show_msg(response[0]);
				release_freezing();
				//alert("data Update Successfully");

			} else {
				show_list_view('','ac_reload_list','list_view_div','requires/new_prog_test_controller','');
				show_msg(response[0]);
				release_freezing();
				//alert("data Delete Successfully");
			}

			if (response[0] != 10) {

				set_button_status(1, permission, 'fnc_employee_data_entry', 1);

				
			}

			release_freezing();



		}
	}


	function populate_list_data(system_id){
		//
    freeze_window(0);
    get_php_form_data(system_id,"populate_employee_info","requires/new_prog_test_controller");

	set_button_status(1, permission, 'fnc_employee_data_entry', 1);

    release_freezing();
  }


	//----------------------------------------------------------
</script>

<body>
	<div align="center" style="width:100%;">
		<?= load_freeze_divs("../", $permission); ?>
		<fieldset style="width:950px;height:auto;">
			<legend>Budget Entry (for Demo)</legend>
			<form id="budgetentry_1" autocomplete="off">

				<table width="900">
					<tr>
						<td>System</td>
						<td>
							<input id="text_emplyee_system_id" type="text" placeholder="emplyee_system_id" class="text_boxes" style="width:150px" disabled>
							<input type="hidden" id="hidden_update_id">
						</td>
					</tr>
					<tr>
						<td>Company Name</td>
						<td>
							<?
							echo create_drop_down("cbo_company_name", 160, "select comp.company_name,comp.id from lib_company comp where comp.is_deleted=0  and comp.status_active=1  $company_cond order by comp.company_name", "id,company_name", 1, "-- Select --", $selected, "");
							?>
						</td>

						<td>Employee Name</td>
						<td>
							<input id="text_employee_name" type="text" class="text_boxes">
						</td>

						<td>Employee ID</td>
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

						<td>Location</td>
						<td>
							<input id="text_company_location" type="text" class="text_boxes">
						</td>
					</tr>
					<tr>
						<td>Salary [USD]</td>
						<td>
							<input id="text_salary_usd" type="text" placeholder="" class="text_boxes_numeric" step="0.01" style="width:150px" required>
						</td>

						<td>Conversion Rate [BDT]</td>
						<td>
							<input id="text_conversion_rate" type="text" placeholder="" class="text_boxes_numeric" step="0.01" onchange="conversion_rated(this.value)" required>



							<select name="cbo_jr_currency" id="cbo_jr_currency" class="combo_boxes" style="width:65px">
								\n<option value="1">Taka</option>
								\n<option id="usd_value" value="120" selected="">USD</option>
								\n<option value="3">EURO</option>
								\n<option value="4">CHF</option>
								\n<option value="5">SGD</option>
								\n<option value="6">Pound</option>
								\n<option value="7">YEN</option>
								\n<option value="8">DM</option>
								\n</select>
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
							echo load_submit_buttons($permission, "fnc_employee_data_entry", 0, 0, "reset_form_custom()", 1); //reset_form('chartofaccount_1','','')
							?>
						</td>
					</tr>

					

				</table>
			</form>
			<div id="list_view_div">
				<?php
					$comp = return_library_array("select id, company_name from lib_company", 'id', 'company_name');
					$sql = "SELECT * From new_test_data_entry WHERE status_active = 1 AND is_deleted = 0   
					order by id desc";
					// order by=serial by and desc= from last and assendin = default
					$result = sql_select($sql);

					// echo "<pre>";
					// var_dump($result);
					// echo "</pre>";

				
				?>
					<div> 
						<h1>List view</h1>
						
					</div>
					<div id="list_view_div"  style="height:200px;overflow-y:scroll">
						<table width="900" class="rpt_table" cellspacing="0" cellpadding="0" id="" border="0">
							<thead>
								   <tr>
										<th width="50">SL No</th>
										<th width="50">Sys ID</th>
										<th width="150">Company Name</th>
										<th width="100">EMPLOYEE NAME</th>
										<th width="100">EMPLOYEE ID </th>
										<th width="100">DOB</th>
										<th width="50"> EMPLYEE AGE</th>
										<th width="120">LOCATION</th>
										<th width="80">SALARY USD </th>
										<th width="100">SALARY BDT </th>
										
										
									</tr>
							</thead>
								<?php
									$comp = return_library_array("select id, company_name from lib_company", 'id', 'company_name');
									$sql = "SELECT * From new_test_data_entry WHERE status_active = 1 AND is_deleted = 0   
										order by id desc";

									// $bgcolor = (($row % 2) == 0) ? "#3b87e3" : "#FFFFFF";
									$result =  sql_select($sql);	
									// echo   "<pre> ";
									// print_r($result);
								?>
						</table>

					    <div>
							<table width="900" class="rpt_table" cellspacing="0" cellpadding="0" id="list_view_table" border="0">
								<tbody>
										
									<?php
										$i=1;
										foreach($result as $row)
										{
											$bgcolor = (($i % 2) == 0) ? "#a8c8ed" : "#FFFFFF";
											?>
												<tr bgcolor="<?php echo $bgcolor; ?>" style="text-decoration:none; cursor:pointer" onclick="populate_list_data('<?php echo $row['ID']; ?>');">
												<td  align="center"><?php echo $i; ?></td>
													<td  align="center"><?php echo $row["ID"]; ?></td>
													<td  align="center"><?php echo $comp[$row["COMPANY_ID"]] ?></td>
													<td  align="center"><?php echo $row["EMPLOYEE_NAME"] ?></td>
													<td  align="right"> <?php echo $row["EMPLOYEE_ID"] ?></td>
													<td  align="center"><?php echo change_date_format($row["DATE_OF_BIRTH"]) ?></td>
													<td  align="center"><?php echo $row["EMPLYEE_AGE"] ?></td>
													<td  align="center"><?php echo $row["COMPANY_LOCATION"] ?></td>
													<td  align="right"> <?php echo $row["SALARY_USD"] ?></td>
													<td  align="right"> <?php echo $row["SALARY_BDT"] ?></td>
												</tr>
											<?php
											$i ++;
										}
									?>
									
							   </tbody>
									<script>
									     setFilterGrid('list_view_table', -1);
									</script>
							</table>
					   </div>	        
					</div>
		    </fieldset> 
		      <br> <br>
    </div>
	<script src="../includes/functions_bottom.js" type="text/javascript"></script>
</body>

</html>