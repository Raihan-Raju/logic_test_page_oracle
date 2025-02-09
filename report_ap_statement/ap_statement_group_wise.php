<?php
/* -------------------------------------------- Comments
  Purpose			: 	This form will create chart of account
  Functionality	    :
  JS Functions	    :
  Created by		:	Shajjad// Raihan Raju
  Creation date 	:
  Updated by 		:   16/01/2025 - 21/01/2025
  Update date		:
  QC Performed BY	:
  QC Date			:
  Comments		    :
 */

session_start();
if ($_SESSION['logic_acc']['user_id'] == "")
    header("location:login.php");
require_once('../../includes/common.php');
extract($_REQUEST);
$_SESSION['page_permission'] = $permission;
//--------------------------------------------------------------------------------------------------------------------
echo load_html_head_contents("AP Statement Group Wise", "../../", 1, 1, $unicode, 1, '');
?>
<script>

     function search_multiple_company()
    {
        
        emailwindow = dhtmlmodal.open('EmailBox', 'iframe', 'requires/ap_statement_group_wise_controller.php?action=seach_multiple_company', 'Company Search', 'width=380px,height=320px,center=1,resize=0,scrolling=0', '../')
        emailwindow.onclose = function ()
        {
            var theform = this.contentDoc.forms[0] //Access the form inside the modal window
            var com_sel_id = this.contentDoc.getElementById("company_selected_id").value;
            var com_sel_name = this.contentDoc.getElementById("company_selected").value;
   
//--------------------
          
            $("#hdn_cbo_company_id").val(com_sel_id)
            $("#cbo_company").val(com_sel_name);
            // document.getElementById('cbo_company').value        = sel_id.value;
            // document.getElementById('cbo_company_name').value   = sel_name.value;
        }
    }

    function search_multiple_supplier()
    {
        // if (form_validation('cbo_company', 'text_supplier') == false)
        // {
        //     return;
        // }
        emailwindow = dhtmlmodal.open('EmailBox', 'iframe', 'requires/ap_statement_group_wise_controller.php?action=multiple_search_supplier', 'Supplier Search', 'width=380px,height=320px,center=1,resize=0,scrolling=0', '../')
        emailwindow.onclose = function ()
        {
            var theform = this.contentDoc.forms[0] //Access the form inside the modal window
            var sel_id = this.contentDoc.getElementById("hdn_supplier_id").value;
            var sel_name = this.contentDoc.getElementById("hdn_supplier_name").value;
  
            $("#hdn_text_supplier_id").val(sel_id);
            $("#text_supplier").val(sel_name);
        }
        
    }



    function generate_report()
    {
		// if (form_validation('cbo_company', 'Company Name') == false)
		// {
		// 	return;
		// }
		// alert ("raju");return;

		// var supplierType = 2;	
		// if( $('#supplierType').prop('checked')==true){ 
		// 	supplierType = 1;
		// }
		
		document.getElementById("accordion_h1").click();

		var data = "action=ap_statement_report"+ get_submitted_data_string('hdn_cbo_company_id*hdn_text_supplier_id*txt_date_from*cbo_balance_type', "../../");
        // alert (data);return;
       
        freeze_window();
       
		http.open("POST", "requires/ap_statement_group_wise_controller.php", true);
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.send(data);
		http.onreadystatechange = generate_report_response;
        // release_freezing();
    }
	
	function generate_report_response()
	{
		if (http.readyState == 4)
		{
			var response = trim(http.responseText).split('**');
            //  alert (response);
			$("#report_container2").html(response[0]);
			show_msg('3'); 

            $('#exl_rpt_link').attr('href',document.getElementById('txt_excl_link').value);

          
	       release_freezing();
			// accordion_menu('accordion_h1', 'content_search_panel', '');
		}
    }
   
    function view_html_report()
    {
        var response = document.getElementById('report_container2').innerHTML;
        var w = window.open("Surprise", "#");
        var d = w.document.open();
        d.write(response);
        d.close();
    }

</script>
</head>

<body>	
    <?php
    $tbl_witdh = 650;
    ?>

    <div style="width:100%" align="center">  
        <?php echo load_freeze_divs("../../", $permission); ?>
        <h3 style="width:<?php echo $tbl_witdh; ?>px; margin-top:20px;" align="left" id="accordion_h1" class="accordion_h" onClick="accordion_menu(this.id, 'content_search_panel', '')"> -Search Panel</h3>
        <div id="content_search_panel" style="width:<?php echo $tbl_witdh; ?>px">
            <fieldset style="width:<?php echo $tbl_witdh; ?>px">  
                <form id="ap_statement">        
                    <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" height="20" valign="middle">
                                    <div id="messagebox" style="background:#F99" align="center"></div>
                                </td>
                            </tr>
                        <tr align="center" valign="middle">
                            <td>
                                <table class="rpt_table" rules="all">
                                        <thead>
                                                <th class="must_entry_caption">Company</th>
                                                <th>Supplier</th>
                                                <th >As on Date</th>
                                                <th >Balance Type </th>
                                                <th width="100">
                                                    <input type="reset" name="reset" id="reset" value="Reset" style="width:70px" class="formbutton" />
                                                </th>
                                        </thead>


                                        <tbody>
                                            <tr class="general">
                                                <td width="">
                                                    <?php
                                                    //echo create_drop_down("cbo_company", 160, "select comp.id,comp.company_name from lib_company comp,  lib_ac_period_mst b where comp.status_active=1 and comp.is_deleted=0 and comp.id=b.company_id $company_cond group by comp.id,comp.company_name order by comp.company_name", "id,company_name", 1, "-- Select --", $selected, "set_month_button()", 0, "", "", "", "", 1);
                                                    ?> 
                                                    <input type="text" name="cbo_company" id="cbo_company" class="text_boxes" style="width: 160px;" placeholder="Double Click" onDblClick="search_multiple_company()" tabindex="1"/>
                                                    <!-- hidden field -->
                                                    <input type="hidden" name="cbo_company" id="hdn_cbo_company_id"/>
                                                </td>
                                            
                                                <td width="">
                                                    <input type="text" name="text_supplier" id="text_supplier" class="text_boxes" style="width: 160px;" placeholder="Double Click" onDblClick="search_multiple_supplier()"   tabindex="2"/>
                                                    <!-- hidden field -->
                                                    <input type="hidden" name="text_supplier_id" id="hdn_text_supplier_id"/>
                                                </td>
                                            
                                                <td width="">
                                                    <input type="text" name="txt_date_from" id="txt_date_from" value="<?php echo date('d-m-Y'); ?>"  class="datepicker" style="width:70px; text-align:center"  tabindex="3" />
                                                </td>

                                                <td align="center">
                                                    <?php  
                                                        $balance_arr=array(1=>'All (0) Balance',2=>'Without (0) Balance');
                                                        echo create_drop_down( "cbo_balance_type", 120, $balance_arr,"",0, "--Select--", "2",$dd,0, "", "", "", "", 7 );
                                                    ?>
                                                </td>
                                                <td width=""><input type="button" name="show" id="show" onClick="generate_report(); " class="formbutton" style="width:70px" value="Show"   tabindex="5"/></td> 
                                                
                                         </tr>
                                     </tbody>
                                  
                                </table>

                                <table>
                                    <tr>
                                        <td colspan="5" align="center" height="30" valign="bottom"  id="month_button_cont">
                                            <?php echo load_month_buttons(0);?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" align="center" height="30" valign="bottom">
                                            <div id="report_container" align="center" style="margin-top:10px; margin-bottom:10px">
                                                <input type="button" id="reprt_html" onClick="view_html_report()" class="formbutton" value="HTML Preview">&nbsp;&nbsp;
                                                <a id="exl_rpt_link"><input type="button" id="reprt_excl" class="formbutton" value="Download Excel"></a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </form>
            </fieldset>
        </div>
        <fieldset style="width:<?php echo $tbl_witdh; ?>px; border:none" >
        	<div id="report_container2" align="center"> 
               
            </div>
 		</fieldset> 

    </div>
</body>
<script>
//set_multiselect( fld_id, max_selection, is_update, update_values, on_close_fnc_param )
//	set_multiselect('cbo_control_ac','0','0','','');
	//set_multiselect('cbo_control_ac','0','0','','__set_buyer_status__../contact_details/requires/supplier_info_controller*0');
</script>
<script src="../../includes/functions_bottom.js" type="text/javascript"></script>


</html>