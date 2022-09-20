<!--<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Dashboard</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">Home</a>
            </li>
            <li>
                <a>Dashboard</a>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>-->
<?php include_once('crud_alerts.php') ?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Manage <?= plural($subject) ?></h5>
                    <?php 
                    if($action_buttons["add_action"]){
                    if($unset_all_actions==false){?>
                    <div class="ibox-tools">
                        <a class="btn btn-primary btn-xs" href="<?= $current_page_link ?>/add">
                            <i class="fa fa-plus" aria-hidden="true"></i> <?= singular($subject) ?> 
                        </a>
                    </div>
                    <?php }
                    }?>
                    
                </div>
                <div class="ibox-content">
					<?php if(isset($filter_box_template)){
						$this->load->view("admin/curd_includes/".$filter_box_template);
					}?>
				
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                                <tr>
                                    <?php
                                    $fields = $fileds_info;
                                    if ($fields) {
                                        echo "<th>S.No</th>";
                                        foreach ($fields as $col) {
                                            if ($col->is_primary_key) {
                                                //echo "<th style='display:none;'>Primary Key Column</th>";
                                            } else if (in_array($col->column_name, $hide_columns_in_list_view)) {
                                                
                                            } else if (array_key_exists($col->column_name, $column_name_display_as)) {
                                                echo "<th>" . $column_name_display_as[$col->column_name] . "</th>";
                                            } else if ($col->Field == "created_at") {
                                                echo "<th>" . humanize_column_name($col->column_name) . "</th>";
                                            } else {
                                                echo "<th>" . humanize_column_name($col->column_name) . "</th>";
                                            }
                                        }
                                        if (!$unset_all_actions) {
                                            echo "<th>Action</th>";
                                        }
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($list_items) {
                                    $sno = 0;
                                    for ($i = 0, $l = sizeof($list_items); $i < $l; $i++) {
                                        $row = $list_items[$i];
                                        echo "<tr>";
                                        echo "<td>" . ($i + 1) . "</td>";
                                        for ($j = 0, $jl = sizeof($fields); $j < $jl; $j++) {
                                            $col_field_name = $fields[$j]->Field;
                                            if ($fields[$j]->is_primary_key) {
                                                //echo "<script>alert('".$col[$j]->Key."')</script>";
                                                //echo "<td style='display:none;'>" . $row->$col_field_name . "</td>";
                                            } else if (in_array($fields[$j]->Field, $hide_columns_in_list_view)) {
                                                
                                            } else if (array_key_exists($fields[$j]->Field, $relation_fields)) {
                                                if (in_array($fields[$j]->Field, $multiple_selection_of_options)) {
                                                    $p_t_b_name = $relation_fields[$fields[$j]->Field]['table_name'];
                                                    $d_c_n = $relation_fields[$fields[$j]->Field]['display_column_name'];
                                                    
													$mul_array = explode(",",$row->$col_field_name);
													$wid="";
													foreach($mul_array as $mul_item){
														if(is_numeric($mul_item)){
															$wid .= $mul_item.",";
														}else{
															$wid .= "'".$mul_item."',";
														}
													}
													$wid = substr($wid, 0,-1);
													
                                                    $sql_query = "SELECT id, $d_c_n FROM $p_t_b_name WHERE id IN ($wid)";
                                                    $dpdata = $this->db->query($sql_query);
                                                    if ($dpdata->num_rows()) {
                                                        echo "<td>";
														$dp_res = $dpdata->result();
                                                        foreach ($dp_res as $dpitem) {
                                                            //echo $dc = explode(', ',$d_c_n);
                                                            $dcosfdsjfls = '';
                                                            //for ($mic = 0, $micl = 1; $mic < $micl; $mic++) {
                                                                echo $dpitem->name . "<br/>";
                                                                $dcosfdsjfls .= $dpitem->name . ", ";
                                                           // }
                                                        }
                                                        echo "</td>";
                                                    }else{
														echo "<td></td>";
													}
                                                } else {
                                                    $p_t_b_name = $relation_fields[$fields[$j]->Field]['table_name'];
                                                    $d_c_n = $relation_fields[$fields[$j]->Field]['display_column_name'];
                                                    $wid = $row->$col_field_name;
                                                    if($wid){
                                                       $sql_query = "SELECT id, $d_c_n FROM $p_t_b_name WHERE id=$wid";
                                                        $dpdata = $this->db->query($sql_query);
                                                        if ($dpdata->num_rows()) {
                                                            $datefsafds = $dpdata->result();
                                                            foreach ($datefsafds as $dpitem) {
                                                                $dc = explode(', ', $d_c_n);
                                                                echo "<td>";
                                                                $dcosfdsjfls = '';
                                                                foreach ($dc as $di) {
                                                                    $dcosfdsjfls .= $dpitem->$di . ' - ';
                                                                }echo substr($dcosfdsjfls, 0, -2);
                                                                echo "</td>";
                                                            }
                                                        }else{
															echo "<td></td>";
														} 
                                                    }else{
                                                        echo "<td></td>";
                                                    }
                                                    
                                                }
                                            } else if ($fields[$j]->Field == "image" || in_array($fields[$j]->Field, $image_fields)) {
                                                if ($row->$col_field_name != '') {

                                                    echo "<td>
                                                                <a href='" . base_url() . 'uploads/' . $row->$col_field_name . "' class='fancybox' rel='" . $j . "'>
                                                                <img src='" . base_url() . "thumb/timthumb.php?src=" . base_url() . '/uploads/' . $row->$col_field_name . "&w=50&h=50&q=60' width='50px; height:50px'/>
                                                                </a>
                                                        </td>";
                                                } else {
                                                    echo "<td title='You have not uploaded any image'>
                                                                <a href='" . base_url('admin_assets/images') . "/n_image.png' class='fancybox' rel='" . $j . "' title='Default Image'>
                                                                <img src='" . base_url('admin_assets/images') . "/n_image.png' width='50px; height:30px'/>
                                                                </a>
                                                        </td>";
                                                }
                                            } else if ($fields[$j]->Field == "created_at" || $fields[$j]->Field == "updated_at") {
                                                $show_created_time = $row->$col_field_name != '' ? date('m-d-Y h:i a', $row->$col_field_name) : '';
                                                echo "<td>" . $show_created_time . "</td>";
                                            } else if ($fields[$j]->data_type == "tinyint" && $col_field_name != "status") {
                                                if ($row->$col_field_name == 0) {
                                                    echo "<td class='text-danger'>No</td>";
                                                } else {
                                                    echo "<td class='text-success'>Yes</td>";
                                                }
                                            } else if ($fields[$j]->Field == "status") {
                                                if ($row->$col_field_name == 0) {
                                                    echo "<td class='text-danger'>Inactive</td>";
                                                } else {
                                                    echo "<td class='text-success'>Active</td>";
                                                }
                                            } else {
                                                echo "<td>" . $row->$col_field_name . "</td>";
                                            }
                                        }
                                        ?>
                                        <?php
                                        if (!$unset_all_actions) {
                                            echo '<td>';
                                            if ($action_buttons['edit_action']) {
                                                echo '<a href="' . $current_page_link . '/edit/' . $row->id . '">
                                                            <button class="btn btn-success btn-' . $button_size . '">Edit</button>
                                                     </a>';
                                            }
                                            if ($action_buttons['delete_action']) {
                                                echo '<a href="javascript:msgbox(' . $row->id . ');">
                                                        <button class="btn btn-success btn-' . $button_size . '">Delete</button></a>';
                                            }

                                            if ($add_action_buttons) {
												if (sizeof($add_action_buttons)) {
													?>
												<br/>
												<br/>
												<div class="dropdown">
													<button class="btn btn-success dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">More
														<span class="caret"></span></button>
													<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
														<?php foreach ($add_action_buttons as $item) { ?>
															<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= $item['link'] ?>?<?php
																$param = "";
																foreach ($item['parameters'] as $parameter) {
																	$param .= $parameter . "=" . urlencode($row->$parameter) . "&";
																}
																echo $param;
																echo "param_hash_code=" . base64_encode($param);
																?>"><?= $item['title'] ?></a></li>
															<?php } ?>

													</ul>
												</div>
												<?php
												}
											}
											if(isset($row->add_action_buttons)){?>
												<br/>
												<br/>
												
												<?php foreach ($row->add_action_buttons as $item) { ?>
													<a role="menuitem" tabindex="-1" class="btn btn-success btn-<?=$button_size?>" target="<?=$item["target"]?>" href="<?= $item['link'] ?>"><?= $item['title']?></a>
													<?php } ?>
											
											<?php }

                                        echo '</td>';
                                    }
                                    ?>

                                    <?php
                                    echo "</tr>";
                                    $sno++;
                                }
                            } else {
                                $conspan = sizeof($fields) - sizeof($hide_columns_in_add_view) + 1;
                                $conspan = "100%";
                                echo "<tr><td colspan='" . $conspan . "'><h4 class='text-center'>There is no $subject</h4></td></tr>";
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <?php
                                    $fields = $fileds_info;
                                    if ($fields) {
                                        echo "<th>S.No</th>";
                                        foreach ($fields as $col) {
                                            if ($col->Key == "PRI" || $col->Extra == "auto_increment") {
                                                //echo "<th style='display:none;'>Primary Key Column</th>";
                                            } else if (in_array($col->Field, $hide_columns_in_list_view)) {
                                                
                                            } else if (array_key_exists($col->Field, $column_name_display_as)) {
                                                echo "<th>" . $column_name_display_as[$col->Field] . "</th>";
                                            } else if ($col->Field == "created_at") {
                                                echo "<th>" . humanize_column_name($col->Field) . "</th>";
                                            } else {
                                                echo "<th>" . humanize_column_name($col->Field) . "</th>";
                                            }
                                        }
                                        if (!$unset_all_actions) {
                                            echo "<th>Action</th>";
                                        }
                                    }
                                    ?>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Page-Level Scripts -->
<script>
    $(document).ready(function () {
        <?php  if ($list_items) {?>
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
               <?php /*?> {extend: 'csv'},<?php */ ?>
                {extend: 'excel', title: ' <?= plural($subject) ?>'},
                {extend: 'pdf', title: ' <?= plural($subject) ?>'},

                {extend: 'print',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                    }
                }
            ]

        });
        <?php }?>
    });
</script>
<script language="javascript">
    function msgbox(id)
    {
        if (confirm("Are you sure want to delete ?")) {
            document.location.href = '<?= $current_page_link ?>/delete/' + id;
        }
    }
</script>