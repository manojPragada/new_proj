<div class="wrapper wrapper-content animated fadeInLeft">
<div class="row  border-bottom white-bg dashboard-header">
		<div class="col-md-3">
			<h3>Welcome to <?=SITE_TITLE?></h3>
		</div>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            
            <!-- <div class="col-lg-3" onclick="location.href='<?=base_url()?>admin/customers'" style="cursor:pointer">
                <div class="widget style1 navy-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> Registered Users </span>
                            <h2 class="font-bold">
							<?php
							//$this->db->where("status", 1);
							//echo $this->db->count_all("customers");
							?>
							</h2>
                        </div>
                    </div>
                </div>
            </div> -->

		</div>
		<div class="row">
		</div>
</div>
<script>
        $(document).ready(function() {
            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 4000
                };
                toastr.success('Monitor every thing...', 'Welcome to <?=SITE_TITLE?>');

            }, 1300);
        });
    </script>