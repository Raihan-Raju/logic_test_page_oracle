    <?php
        header('Content-type:text/html; charset=utf-8');
        session_start();
        if ($_SESSION['logic_acc']['user_id'] == "")
            header("location:login.php");

        include('../../../includes/common.php');

        $user_id	= $_SESSION['logic_acc']['user_id'];
        $data		= $_REQUEST['data'];
        $action		= $_REQUEST['action'];

        if ($db_type == 0){ $null_value = "IFNULL"; } else { $null_value = "nvl"; }

        $approval_variable_check_arr = return_library_array("select a.id, $null_value(b.auto_approval,2) as auto_approval from lib_company a left join variable_settings_approval b on(a.id=b.company_name) where a.status_active=1", "id", "auto_approval");

        $country_name = return_library_array("select id, country_name from lib_country", "id", "country_name");

        $balanceCalculationMethortdArr = return_library_array("select company_name, date_type from variable_settings_report_ctl where variable_list=1 and status_active=1 and is_deleted=0", "company_name", "date_type");


if ($action == "seach_multiple_company") 
{
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
       
  ?>
       <script>
            var selected_id = new Array;
            var selected_name = new Array();

            function check_all_data()
            {
                var tbl_row_count = document.getElementById('list_view_com').rows.length;
                tbl_row_count = tbl_row_count - 1;

                for (var i = 1; i <= tbl_row_count; i++)
                {
                    $('#tr_' + i).trigger('click');
                }
            }

            function toggle(x, origColor) {
                var newColor = 'yellow';
                if (x.style) {
                    x.style.backgroundColor = (newColor == x.style.backgroundColor) ? origColor : newColor;
                }
            }

            function js_set_value(str)
            {
                //alert(str); return;
                if (str != "")
                    str = str.split("_");
                    
                if( $("#tr_"+str[0]).css("display") != "none" )
                {

                    toggle(document.getElementById('tr_' + str[0]), '#FFFFCC');
        
                    if (jQuery.inArray(str[1], selected_id) == -1) {
                        selected_id.push(str[1]);
                        selected_name.push(str[2]);
                        //selected_attach_id.push( str[3] );
                    } else
                    {
                        for (var i = 0; i < selected_id.length; i++)
                        {
                            if (selected_id[i] == str[1])
                                break;
                        }
                        selected_id.splice(i, 1);
                        selected_name.splice(i, 1);
                        //selected_attach_id.splice( i, 1 );
                    }
                    var id = '';
                    var name = '';
                    var attach_id = '';
                    for (var i = 0; i < selected_id.length; i++) {
                        id += selected_id[i] + ',';
                        name += selected_name[i] + '*';
                        //attach_id += selected_attach_id[i] + ',';
                    }
                    id = id.substr(0, id.length - 1);
                    name = name.substr(0, name.length - 1);
                    //attach_id = attach_id.substr( 0, attach_id.length - 1 );
        
                    $('#company_selected_id').val(id);
                    $('#company_selected').val(name);
                    
                   // alert(id);
                }
            }

       </script>
   
        </head> 
        <input type="hidden" name="company_selected_id" id="company_selected_id"/>
        <input type="hidden" name="company_selected" id="company_selected"/>
        </html>	

    <?php
        
	
	$sql = "SELECT id,company_name from lib_company WHERE status_active=1  order by company_name";
	// echo $sql ;
    echo create_list_view("list_view_com", "ID,Company Name", "70", "365", "240", 0, $sql, "js_set_value", "id,company_name", "", 1, "0,0", $arr, "id,company_name", "ap_statement_group_wise_controller", 'setFilterGrid("list_view_com",-1);', '0', '', 1);
	
 exit;
}
if ($action == "multiple_search_supplier") 
{
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
	//Dynamic Control Accoutn Code
	//echo $cbo_control_ac; die;
	
    ?>
            <script>
                var selected_id = new Array;
                var selected_name = new Array();

                function check_all_data()
                {
                    var tbl_row_count = document.getElementById('list_view_supp').rows.length;
                    tbl_row_count = tbl_row_count - 1;

                    for (var i = 1; i <= tbl_row_count; i++)
                    {
                        $('#tr_' + i).trigger('click');
                    }
                }

                function toggle(x, origColor) {
                    var newColor = 'yellow';
                    if (x.style) {
                        x.style.backgroundColor = (newColor == x.style.backgroundColor) ? origColor : newColor;
                    }
                }

                function js_set_value_supp(str)
                {
                    // alert(str); return;
                    if (str != "")
                        str = str.split("_");
                        
                    if( $("#tr_"+str[0]).css("display") != "none" )
                    {

                        toggle(document.getElementById('tr_' + str[0]), '#FFFFCC');
            
                        if (jQuery.inArray(str[1], selected_id) == -1) {
                            selected_id.push(str[1]);
                            selected_name.push(str[2]);
                            //selected_attach_id.push( str[3] );
                        } else
                        {
                            for (var i = 0; i < selected_id.length; i++)
                            {
                                if (selected_id[i] == str[1])
                                    break;
                            }
                            selected_id.splice(i, 1);
                            selected_name.splice(i, 1);
                            //selected_attach_id.splice( i, 1 );
                        }
                        var id = '';
                        var name = '';
                        var attach_id = '';
                        for (var i = 0; i < selected_id.length; i++) {
                            id += selected_id[i] + ',';
                            name += selected_name[i] + '*';
                            //attach_id += selected_attach_id[i] + ',';
                        }
                        id = id.substr(0, id.length - 1);
                        name = name.substr(0, name.length - 1);
                        //attach_id = attach_id.substr( 0, attach_id.length - 1 );
            
                        $('#hdn_supplier_id').val(id);
                        $('#hdn_supplier_name').val(name);
                        //$('#txt_attach_id').val( attach_id );
                    }
                }

            </script>
         </head> 
        <input type="hidden" name="hdn_supplier_id" id="hdn_supplier_id"/>
        <input type="hidden" name="hdn_supplier_name" id="hdn_supplier_name"/>
      </html>	
    <?php
	//$sql = "select a.id,a.supplier_name from lib_supplier a, ac_coa_mst b, ac_transaction_dtls c, lib_supplier_tag_company d where a.id=d.supplier_id and d.tag_company='$cbo_company' and c.account_code=b.ac_code and b.control_ac_id=1 and c.supplier_id=a.id and a.is_deleted=0 and a.status_active=1 and b.is_deleted=0 and b.status_active=1  and c.is_deleted=0 and c.status_active=1  group by a.id,a.supplier_name order by a.supplier_name";
	
	//$sql = "select a.id,a.supplier_name from  lib_supplier a,lib_supplier_tag_company b where a.id=b.supplier_id and a.is_deleted=0  and a.status_active=1 and b.tag_company=$cbo_company order by supplier_name";
	
	$sql = "SELECT id,supplier_name from lib_supplier  WHERE status_active=1  order by supplier_name";
	
	
	
    echo create_list_view("list_view_supp", "ID,Supplier Name", "70", "365", "240", 0, $sql, "js_set_value_supp", "id,supplier_name", "", 1, "0,0", $arr, "id,supplier_name", "ap_statement_group_wise_controller", 'setFilterGrid("list_view_supp",-1);', '0', '', 1);
	
exit;
}

if($action=="ap_statement_report")
{
    
    $process = array($_POST);
    extract(check_magic_quote_gpc( $process ));

    // print_r($process);

    $company_name = str_replace("'","",$hdn_cbo_company_id	);
    $supplyer_name = str_replace("'","",$hdn_text_supplier_id);
    $company_date = str_replace("'","",$txt_date_from);

    //  echo   $company_name;
    //  echo   $supplyer_name;
    $com_id_arr = explode(",",$company_name);
    $supp_id_arr = explode(",",$supplyer_name);
    // print_r($com_id_arr) ;
    // print_r($supp_id_arr) ;
    $sql_cond ="";
        if($company_name)
        {
            $sql_cond .= "AND a.company_id in($company_name) ";
        }
        else 
        {
            $sql_cond ="";
        }

        if($supplyer_name)
        {
            $sql_cond.="AND b.supplier_id in($supplyer_name) ";
        }
        else
        {
            $sql_cond ="";
        }
  
        if($company_date)
        {
        $sql_cond.="AND a.journal_date <= '$company_date' ";
        }
        else
        {
            $sql_cond ="";
        }
    
    
    $comp = return_library_array("select id, company_short_name from lib_company", 'id', 'company_short_name');
    $supp = return_library_array("select id, supplier_name from lib_supplier", 'id', 'supplier_name');

   $sql = "SELECT a.company_id , a.journal_date, b.supplier_id, b.debit_amount, b.credit_amount
            FROM ac_transaction_mst a, ac_transaction_dtls b   WHERE  a.id = b.transection_mst_id  
            AND a.status_active = 1 AND b.status_active = 1 AND a.is_deleted = 0  AND b.is_deleted = 0 $sql_cond";

    $result = sql_select($sql);	
    $data_array = array();
    
  
    foreach($result as $key)
    {
        $data_array[$key["SUPPLIER_ID"]][$key['COMPANY_ID']]['DEBIT_AMOUNT'] += $key['DEBIT_AMOUNT'];
        $data_array[$key["SUPPLIER_ID"]][$key['COMPANY_ID']]['CREDIT_AMOUNT'] += $key['CREDIT_AMOUNT'];
    }
    echo"<pre>";
    //   print_r( $data_array);
    echo "<pre>";
    
    
    ob_start();
        $tableWidth=1250;
    ?>
    <div style="width:<? echo $tableWidth; ?>px;">
                <style type="text/css">
                    .rpt_tbl_head td{
                    font-family: Arial Narrow, Arial, sans-serif !important;
                    }
                    .rpt_tbl_head tr:nth-child(1) td{
                    font-size: 1.9em; 
                    }
                    .rpt_tbl_head  tr:nth-child(2) td{
                    font-size: 1.1em; 
                    }
                    .rpt_tbl_head tr:nth-child(3) td{
                    font-size: 1.3em; 
                    }
                    .rpt_tbl_head tr:nth-child(4) td{
                    font-size: 1.2em; 
                    }
                    .rpt_tbl_dtls {
                    font-family: Arial Narrow, Arial, sans-serif !important;
                    }
                    .rpt_tbl_dtls th{
                    font-size: 1.1em !important;
                    vertical-align:middle;
                    }
                    
                    .rpt_tbl_dtls td{
                        font-size: 1.1em !important;
                        vertical-align:middle;
                    }
                </style>
                <table width="<? echo $tableWidth; ?>">
                    <tr>
                        <td width="25%" id="company_image_container">
                        <?php
                            $sql_photo=sql_select("select b.image_location from  lib_company a,common_photo_library b where a.id=b.master_tble_id and a.id=$company_name 
                        and b.form_name='company_details' ");
                        //echo "10**".$sql_photo[0][csf('image_location')];
                        ?>
                        </td>
                        <td>
                            <table width="75%">    	
                                <tr><td align="center" style="font-size:20px;" colspan="7"><b><?php echo 'AP Statement'; ?></b></td></tr>
                                <tr><td align="center" colspan="7"><b>Date Range: </b><?php echo change_date_format(str_replace("'","",$txt_date_from)); ?> </td></tr>
                            </table>
                        </td>
                    </tr>
                </table>
        
        <table border="1" class="rpt_table rpt_tbl_dtls" rules="all" width="<? echo $tableWidth; ?>" cellpadding="0" cellspacing="0">
            <thead>
                <th width="50">Sl#</th>
                <th width="">Supplier</th>
                <?php
                    foreach($com_id_arr as $val)
                    {
                        ?>
                            <th width="80"><?php echo $comp[$val]?></th>
                        <?php
                    }
                ?>
                <th width="100">Grand Total</th>
                
            </thead>
    <!-- ----------dinamic table head end--------- -->

                <tbody class="" id="table_body">
                    <?php
                        
                        $supplier_total = 0;
                        $i=1;  
                       // $bgcolor = (($i % 2) == 0) ? "#1168d1" : "#e62e2e";
                  
                        foreach($data_array as $supplier_id =>$supplier_data)
                        {
                            // if($i%2==0)
                            // {
                            //     $bgcolor =  "#1168d1" ;
                            // }else{
                            //     $bgcolor =  "#e62e2e" ;
                            // }
                            $bgcolor = (($i % 2) == 0) ? "#E9F3FF" : "#ffffff";
                            ?>
                                <tr style="background-color:<?php echo $bgcolor; ?>">
                                    <td><?php echo $i?></td>
                                    <td ><?php echo $supp[$supplier_id]?></td>
                                
                                        <?php
                                            foreach($com_id_arr as $val)
                                            {
                                                $final_qty = $data_array[$supplier_id][$val]['CREDIT_AMOUNT'] - $data_array[$supplier_id][$val]['DEBIT_AMOUNT'];
                                                
                                                $supplier_total += $final_qty;
                                                $grand_total += $supplier_total;
                                                ?>
                                                <td  align="right"><?php echo number_format( $final_qty,2); ?></td>
                                                <?php                                           
                                            }
                                        ?>
                                    <td  align="right"><b><?php echo number_format($supplier_total,2); unset($supplier_total); ?></b></td>
                                
                                </tr>
                            
                            <?php
                            $i++;
                        }  
                    ?>
                </tbody>
                    <tfoot>
                        <tr >
                            <th colspan="2" align="right"><b style="font-size: 20px;">Total:</b></th>
                                <?php
                                    $company_totals = 0;
                                            foreach($com_id_arr as $val)
                                            {
                                                $company_total = 0;
                                                foreach($data_array as $supplier_id => $supplier_data)
                                                {
                                                    $company_total += $data_array[$supplier_id][$val]['CREDIT_AMOUNT'] - $data_array[$supplier_id][$val]['DEBIT_AMOUNT'];
                                                }
                                                $company_totals = $company_total; 
                                                ?>
                                                <td  align="right"><b><?php echo number_format($company_total,2) ; ?></b></td>
                                                <?php
                                            }
                                    ?>
                            
                            <td  align="right"><b><?php echo number_format($grand_total,2); unset($grand_total);?></b></td>
                        </tr>
                    
                    </tfoot>

            
        </table>
        <?php
            $html = ob_get_contents();
            
            //echo $html."** Hello World";die;
            ob_flush();

            //foreach (glob(""."*.xls") as $filename) 
            foreach (glob("*_".$user_id.".xls") as $filename)  // Only delete current user created excel/pdf file @reaz
            {			
                @unlink($filename);
            }
            $name=time();
            //$name="$name".".xls";	
            $name = "$name"."_".$user_id.".xls";// 1232025_65.xls	
            $create_new_excel = fopen(''.$name, 'w');	
            $is_created = fwrite($create_new_excel,$html);
        ?>
        
        <input type="hidden" id="txt_excl_link" value="<?php echo 'requires/'.$name; ?>" />
     
  </div>
 <?php
exit;
   
}




