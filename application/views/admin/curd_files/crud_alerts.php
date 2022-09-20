<?php
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'update') {
        ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Updated Successfully..!
        </div>
    <?php } ?>	
    <?php if ($_GET['msg'] == 'add') { ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Added Successfully..!
        </div>
    <?php } ?>
    <?php if ($_GET['msg'] == 'delete') { ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Deleted Successfully..!
        </div>
    <?php
    }
}?>