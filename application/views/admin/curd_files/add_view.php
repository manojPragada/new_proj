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
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Add <?= singular($subject) ?> <?= $this->input->get_post('display_text') ?></h5>
                    <div class="ibox-tools">
                        <?php
                        if (isset($action_buttons["back_action"])) {
                            if ($action_buttons["back_action"] == true) {
                                ?>
                                <?php /* <a class="btn btn-primary btn-xs" href="<?= $current_page_link ?>"> */ ?>
                                <button class="btn btn-primary btn-xs" onclick="window.history.go( - 1)">
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i> Back
                                </button>
                                <?php
                            } else {

                            }
                        } else {
                            ?>
                            <button class="btn btn-primary btn-xs" onclick="window.history.go( - 1)">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i> Back
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="form" class="form-horizontal" enctype="multipart/form-data" action="">
                        <?php
                        foreach ($fileds_info as $field_info) {
                            $column_as = $field_info->column_name;

                            if ($field_info->is_unique_key) {
                                array_push($unique_fileds, $field_info->column_name);
                            }

                            if (array_key_exists($field_info->column_name, $column_name_display_as)) {
                                $label_name = $column_name_display_as[$field_info->column_name];
                            } else {
                                $label_name = humanize_column_name($field_info->column_name);
                            }

                            $validation_msg = 'data-msg-required="This ' . $label_name . ' is required"';
                            if ($field_info->Null == "YES") {
                                $required = "";
                            } else {
                                $required = 'required="required"';
                            }
                            if ($field_info->is_primary_key && $field_info->Extra == "auto_increment") {

                            } else if ($field_info->column_name == "created_at" || $field_info->column_name == "updated_at" || $field_info->column_name == "salt") {

                            } else if (in_array($field_info->column_name, $hide_columns_in_add_view)) {

                            } else if (in_array($field_info->column_name, $readonly_fields)) {
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly <?= $required ?> name="<?= $field_info->column_name ?>" class="form-control"
                                               id="<?= $field_info->column_name ?>" <?= $validation_msg ?> value="<?= $this->input->get_post($field_info->column_name) ?>">
                                    </div>
                                </div>
                            <?php } else if (in_array($field_info->column_name, $email_fields)) {
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <input type="email" <?= $required ?> name="<?= $field_info->column_name ?>" class="form-control"
                                               data-msg-required="Please enter <?= $field_info->column_name ?>"
                                               data-rule-email="true"
                                               data-msg-email="Please enter valid email id" id="<?= $field_info->column_name ?>" <?= $validation_msg ?>>
                                               <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else if ($field_info->column_name == "password") {
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <input type="password" <?= $required ?> name="<?= $field_info->column_name ?>" class="form-control"
                                               data-msg-required="Please enter password"
                                               data-rule-minlength="6" data-rule-maxlength="32"
                                               data-msg-minlength="Password length should be between 6 to 32 characters" id="<?= $field_info->column_name ?>" <?= $validation_msg ?>>
                                               <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else if ((($field_info->data_type == "text" || $field_info->data_type == "longtext" ) && !in_array($field_info->column_name, $image_fields) && !in_array($field_info->column_name, $multiple_selection_of_options)) || (( $field_info->data_type == "varchar" && $field_info->maximum_text_acceptable_length > 100) && !in_array($field_info->column_name, $image_fields) && !in_array($field_info->column_name, $multiple_selection_of_options))) {
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <textarea <?= $required ?> name="<?= $field_info->column_name ?>" class="form-control
                                        <?php
                                        if (isset($exclude_ck_editor_fields)) {
                                            if (!in_array($field_info->column_name, $exclude_ck_editor_fields)) {
                                                echo "ckeditor";
                                            }
                                        } else if ($field_info->data_type == "varchar" && $field_info->maximum_text_acceptable_length > 100) {

                                        } else {
                                            echo "ckeditor";
                                        }
                                        ?>"
                                        <?= $validation_msg ?>
                                                                   id="<?= $field_info->column_name ?>" <?= $validation_msg ?>></textarea>
                                                                   <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                            } else if (array_key_exists($field_info->column_name, $relation_fields)) {
                                $fetch_dependency_data = true;
                                if (isset($ajax_base_relation_result)) {
                                    if (array_key_exists($field_info->column_name, $ajax_base_relation_result)) {
                                        $fetch_dependency_data = false;
                                    }
                                }
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <select <?php if (in_array($field_info->column_name, $multiple_selection_of_options)) echo "multiple" ?> id="<?= $field_info->column_name ?>" data-placeholder="Choose <?= $label_name ?>" style="width:100%;" class="chosen-select" name="<?= $field_info->column_name ?><?php if (in_array($field_info->column_name, $multiple_selection_of_options)) echo "[]" ?>"  <?= $required ?> >
                                            <option value=""></option>
                                            <?php
                                            if ($fetch_dependency_data == true) {
                                                if ($this->db->field_exists('status', $relation_fields[$field_info->column_name]['table_name'])) {
                                                    $item_dependency_results = $this->db->get_where($relation_fields[$field_info->column_name]['table_name'], ["status" => 1])->result();
                                                } else {
                                                    $item_dependency_results = $this->db->get($relation_fields[$field_info->column_name]['table_name'])->result();
                                                }
                                                if (sizeof($relation_fields[$field_info->column_name]["condition"])) {
                                                    if ($this->db->field_exists('status', $relation_fields[$field_info->column_name]['table_name'])) {
                                                        $this->db->where("status", 1);
                                                    }
                                                    $this->db->where($relation_fields[$field_info->column_name]["condition"]);
                                                    $item_dependency_results = $this->db->get($relation_fields[$field_info->column_name]['table_name'])->result();
                                                } else {
                                                    $item_dependency_results = $this->db->get($relation_fields[$field_info->column_name]['table_name'])->result();
                                                }
                                                foreach ($item_dependency_results as $i_d_r) {
                                                    $t_d_c = explode(",", $relation_fields[$field_info->column_name]['display_column_name']);
                                                    foreach ($t_d_c as $tdc) {
                                                        $tdc = trim($tdc);
                                                    }

                                                    $d_c_n = "";
                                                    foreach ($t_d_c as $tdc) {
                                                        if (isset($relation_fields[$field_info->column_name]['parent_relation'])) {
                                                            if (isset($relation_fields[$field_info->column_name]['parent_relation'][array_keys($relation_fields[$field_info->column_name]['parent_relation'])[0]])) {
                                                                $parent_column_name = array_keys($relation_fields[$field_info->column_name]['parent_relation'])[0];
                                                                $parent_relation_table_name = $relation_fields[$field_info->column_name]['parent_relation'][$parent_column_name]['table_name'];
                                                                $parent_relation_display_coloumn_name = $relation_fields[$field_info->column_name]['parent_relation'][$parent_column_name]['display_column_name'];
                                                                $parent_relation_display_coloumn_name_value = $this->db->get_where($parent_relation_table_name, [$parent_column_name => 1, $parent_column_name => $i_d_r->$tdc])->row()->$parent_relation_display_coloumn_name;
                                                            }
                                                        }

                                                        if ($parent_relation_display_coloumn_name_value) {
                                                            $d_c_n .= $parent_relation_display_coloumn_name_value . " - ";
                                                        } else {
                                                            $d_c_n .= $i_d_r->$tdc . " - ";
                                                        }
                                                    }
                                                    $d_c_n = substr($d_c_n, 0, -3);
                                                    ?>
                                                    <option value="<?= $i_d_r->id ?>" <?= $field_info->Default == $i_d_r->id ? "selected" : '' ?> <?= $this->input->get_post($field_info->column_name) == $i_d_r->id ? "selected" : "" ?>><?= $d_c_n ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>
                                    </div>
                                </div>

                            <?php } else if (in_array($field_info->column_name, $signle_file_browse_fileds)) { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <span>
                                            <input type="file" name="<?= $field_info->column_name ?>__p" class="form-control" <?= $required ?> <?= $validation_msg ?>>
                                            <?php if ($field_info->Comment != "") { ?>
                                                <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                            <?php } ?>
                                            <button class="btnRmv" type="button">Remove</button>
                                        </span>
                                    </div>
                                </div>
                            <?php } else if ($field_info->column_name == "image" || in_array($field_info->column_name, $image_fields)) { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <span>
                                            <input type="file" accept="image/*" name="<?= $field_info->column_name ?>__i" class="form-control show_selected_image_preview" <?= $required ?> <?= $validation_msg ?>>
                                            <?php if ($field_info->Comment != "") { ?>
                                                <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                            <?php } ?>
                                            <a href="" target="_blank" >
                                                <img class="campaign_image1 img-responsive" src="#"
                                                     style="margin-bottom: 10px; display:none; width:100px; height:100px"
                                                     />
                                            </a>
                                            <button class="btnRmv" type="button">Remove</button>
                                            <?php /* if (!$field_info->column_name) { ?>
                                              Previous Image : <img src="<?= base_url() ?>uploads/<?= $field_info->column_name ?>" style="width:100px; height:100px"/>
                                              <?php } */ ?>
                                        </span>
                                    </div>
                                </div>
                            <?php } else if ($field_info->data_type == "int" || $field_info->data_type == "bigint" || $field_info->data_type == "float" || $field_info->data_type == "double") {
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <input type="number" id="<?= $field_info->column_name ?>" maxlength="<?= $field_info->maximum_text_acceptable_length ?>" <?= $required ?> <?= $validation_msg ?> name="<?= $field_info->column_name ?>" class="form-control" value="<?= $field_info->Default ?>" <?= strpos($field_info->Type, "unsigned") ? "min='0'" : "" ?>>
                                        <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else if ($field_info->column_name == "keywords" || $field_info->column_name == "meta_keywords") { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="<?= $field_info->column_name ?>" <?= $required ?> name="<?= $field_info->column_name ?>" class="form-control tagsinput">
                                        <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>

                                    </div>
                                </div>
                            <?php } else if (($field_info->data_type == "varchar" || $field_info->data_type == "char") && $field_info->maximum_text_acceptable_length <= 100) { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="<?= $field_info->column_name ?>" maxlength="<?= $field_info->maximum_text_acceptable_length ?>" <?= $required ?> <?= $validation_msg ?> name="<?= $field_info->column_name ?>" class="form-control">
                                        <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else if ($field_info->data_type == "date") { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" id="<?= $field_info->column_name ?>" <?= $required ?> name="<?= $field_info->column_name ?>" class="form-control datepicker">
                                        <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>

                                    </div>
                                </div>
                            <?php } else if ($field_info->data_type == "time") { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <input type="time" id="<?= $field_info->column_name ?>" <?= $required ?> name="<?= $field_info->column_name ?>" class="form-control timepicker" min="00:00" max="23:59">
                                        <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>

                                    </div>
                                </div>
                            <?php } else if ($field_info->data_type == "tinyint") { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <div class="col-sm-10">
                                        <label class='text-success'>
                                            <input type="radio"
                                                   name="<?= $field_info->Field ?>"
                                                   id="<?= $field_info->Field ?><?= rand(5, 10) ?>"
                                                   <?= $required ?>
                                                   <?= $validation_msg ?> value="1" <?= $field_info->Default == 1 ? "checked" : "" ?>
                                                   /> Yes
                                        </label> &nbsp;&nbsp;
                                        <label class='text-danger'>
                                            <input type="radio"
                                                   name="<?= $field_info->Field ?>"
                                                   id="<?= $field_info->Field ?><?= rand(5, 10) ?>"
                                                   <?= $required ?>
                                                   <?= $validation_msg ?> value="0" <?= $field_info->Default == 0 ? "checked" : "" ?>
                                                   /> No
                                        </label>
                                        <?php if ($field_info->Comment != "") { ?>
                                            <span class="help-block m-b-none"><?= $field_info->Comment ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                            } else if ($field_info->data_type == "enum") {
                                $enum_array = get_enum_values($field_info->Type);
                                if ($enum_array) {
                                    ?>
                                    <div class="form-group"><label class="col-sm-2 control-label"> <?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?><br/>
                                            <?php if ($field_info->Comment != "") { ?>
                                                <small class="text-navy"><?= $field_info->Comment ?></small>
                                            <?php } ?></label>
                                        <div class="col-sm-10">
                                            <?php foreach ($enum_array as $ea) { ?>
                                                <div>
                                                    <label>
                                                        <input type="radio" <?= $required ?> <?= $field_info->Default == $ea ? "checked" : '' ?> value="<?= $ea ?>" id="<?= $field_info->column_name ?><?= rand(5, 10) ?>" name="<?= $field_info->column_name ?>">
                                                        <span><?= $ea ?></span>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else if ($field_info->data_type == "set") {
                                $enum_array = get_set_values($field_info->Type);
                                if ($enum_array) {
                                    ?>
                                    <div class="form-group"><label class="col-sm-2 control-label"> <?= $label_name ?> <?= $required ? '<span class="text-danger">*</span>' : '' ?><br/>
                                            <?php if ($field_info->Comment != "") { ?>
                                                <small class="text-navy"><?= $field_info->Comment ?></small>
                                            <?php } ?></label>
                                        <div class="col-sm-10">
                                            <?php if (sizeof($enum_array) < 5) { ?>
                                                <?php foreach ($enum_array as $ea) { ?>
                                                    <div>
                                                        <label>
                                                            <input type="checkbox" <?= $required ?> <?= $field_info->Default == $ea ? "checked" : '' ?> <?= $field_info->Default == $ea ? "checked" : '' ?> value="<?= $ea ?>" name="<?= $field_info->column_name ?>[]">
                                                            <?= $ea ?>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <select <?= $required ?> data-placeholder="Choose <?= $label_name ?>" style="width:100%;" multiple class="chosen-select" name="<?= $field_info->column_name ?>[]">
                                                    <option value=""></option>
                                                    <?php foreach ($enum_array as $ea) { ?>
                                                        <option value="<?= $ea ?>" <?= $field_info->Default == $ea ? "selected" : '' ?> <?= $this->input->get_post($field_info->column_name) == $ea ? "selected" : "" ?>><?= $ea ?></option>
                                                    <?php } ?>
                                                </select>

                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <!--<button class="btn btn-white" type="submit">Cancel</button>-->
                                <button class="btn btn-primary" type="submit" name="submit" value="insert">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
<?php if ($this->uri->segment(2) == "coupons") { ?>
        $(function(){

        $("input[name='store_discount_value']").parents(".form-group").hide();
        $("input[name='store_maximum_discount_amount']").parents(".form-group").hide();
        $("[name='coupon_promotion_by']").eq(1).parent("label").find("span").text("Store")


                if ($("input[name='coupon_promotion_by']").val() == "Both"){
        $("input[name='store_discount_value']").parents(".form-group").show();
        $("input[name='store_maximum_discount_amount']").parents(".form-group").show();
        $("input[name='admin_discount_value']").parents(".form-group").show();
        $("input[name='admin_maximum_discount_amount']").parents(".form-group").show();
        }

        $(document).on("change", "[name='coupon_promotion_by']", function(){
        if ($(this).val() == "Both"){

        $("input[name='store_discount_value']").parents(".form-group").show();
        $("input[name='store_maximum_discount_amount']").parents(".form-group").show();
        $("input[name='admin_discount_value']").parents(".form-group").show();
        $("input[name='admin_maximum_discount_amount']").parents(".form-group").show();
        } else{

        $("input[name='store_discount_value']").parents(".form-group").hide();
        $("input[name='store_maximum_discount_amount']").parents(".form-group").hide();
        $("input[name='admin_discount_value']").parents(".form-group").hide();
        $("input[name='admin_maximum_discount_amount']").parents(".form-group").hide();
        }
        })

        });
<?php } ?>
</script>

<script type="text/javascript">
    $(function(){
    $.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" }) //for all select having class .chosen-select
            $('.show_selected_image_preview').on('change', function() {
    myfile = $(this).val();
    var ext = myfile.split('.').pop(); ext = ext.toLowerCase();
    if (ext == "pdf" || ext == "docx" || ext == "doc" || ext == "jpeg" || ext == "jpg" || ext == "png"){
    //file allowed
    } else{
    //alert(ext);
    $(this).val("");
    //alert("File Not Allowed", "This file type does not allowed to upload", "error")
    alert("File Not Allowed, This file type does not allowed to upload")
            return false;
    }
    var max_file_upload_size = 1000000; //1mb
    if (max_file_upload_size < this.files[0].size){
    alert("Allowed file size exceeded. (Max. 1 MB)")
            $(this).val("");
    return false;
    }
    });
    $("#form").validate({
<?php
if ($unique_fileds) {
    $unique_fileds = array_unique($unique_fileds)
    ?>
        rules: {
    <?php
    foreach ($unique_fileds as $item) {
        if (!in_array($item, $hide_columns_in_add_view)) {
            ?>
            <?= $item ?>: {
                required: true,
                        remote: {
                        url: "<?= $current_page_link ?><?php if (isset($_GET['id'])) { ?>?existed_value=" + $('#<?= $item ?>').val()<?php } else { ?>"<?php } ?>,
                                type: "post",
                                data: {
            <?= $item ?>: function() {
                                return $("#<?= $item ?>").val();
                                }
                                }
                        },
            <?php
            if (isset($extra_jquery_validation)) {
                if (sizeof($extra_jquery_validation) > 2) {
                    ?>
                        },
                    <?php
                }
            }
            ?>

            <?php
            if (isset($extra_jquery_validation)) {
                foreach ($extra_jquery_validation as $key => $item_rule) {
                    if (in_array($key, $unique_fileds) && $item == $key) {
                        foreach ($item_rule as $item_rule) {
                            ?>
                            <?= $item_rule['rule'] ?> : <?= $item_rule['rule_value'] ?>,
                        <?php } ?>

                        <?php
                        if (isset($extra_jquery_validation)) {
                            if (sizeof($extra_jquery_validation) == 2) {
                                ?>
                                    },
                                <?php
                            }
                        }
                        ?>

                    <?php } ?>

                    <?php
                }
            } else {
                ?>
                    },
            <?php } ?>
            <?php
        }
    }
    ?>
    <?php
    if (isset($extra_jquery_validation)) {
        foreach ($extra_jquery_validation as $key => $item) {
            if (!in_array($key, $unique_fileds)) {

                if (!in_array($key, $hide_columns_in_add_view)) {
                    ?>
                    <?= $key ?> : {
                    <?php foreach ($item as $item_rule) { ?>
                        <?= $item_rule['rule'] ?> : <?= $item_rule['rule_value'] ?>,
                    <?php } ?>
                        },
                    <?php
                }
            }
        }
    }
    ?>
    <?php if (isset($extra_jquery_validation)) if (sizeof($extra_jquery_validation) == 1) { ?>},<?php } ?>

        image :{
        required : true,
                accept: "png,jpg,jpeg,gif"
        }
        }, messages:{
    <?php
    foreach ($unique_fileds as $item) {
        if (!in_array($item, $hide_columns_in_add_view)) {
            ?>
            <?= $item ?>:{
                remote : jQuery.validator.format("The entered value is already existed.")
                },
            <?php
        }
    }
    ?>
        image:{
        accept : 'Please upload PNG, JPG, or GIF only'
        }
        }

<?php } ?>
    });
    });
</script>