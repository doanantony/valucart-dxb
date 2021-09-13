<link rel="stylesheet" href="<?php echo base_url('assets/css/morris.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/daterangepicker.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('assets/js/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/daterangepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/morris.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/raphael.min.js'); ?>"></script>
<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1><?php echo $page_data->function_title; ?>
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
      <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Card Management</a></li>
            <li class="active">Card details</li>
      </ol>
   </section>

   <section class="content">
     <div class="row">
         <div class="col-xs-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title">Settlememnts Report</h3>
              </div>
              <div class="box-body">
                <div class="col-xs-12">
                  <div class="row">
                    <div class="col-xs-4">
                      <div class="row">
                        <div class="col-xs-12">
                          <select name="company_id" id="company_id" class="form-control select2">
                    
                                <option value="cash,card">All Orders</option>
                                <option value="cod">Cash Payments</option>
                                <option value="card">Card Payments</option>                              
                          </select>
                          <input type="hidden" id="vendor_id" value="<?php echo $vendor_id; ?>">
                        </div>                        
                      </div>
                    </div>
                    <div class="col-xs-5">
                      <div class="row">
                        <div class="col-xs-8">
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right trans_date" id="trans_date">
                          </div>
                          </div> 
                          <div class="col-xs-4"><button class="btn btn-success" onclick="filer_result()"> Apply</button>&nbsp;<button class="btn btn-info" onclick="reset_result()"> Reset</button></div>                       
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xs-12" id="summery_output">
                <div class="row">
                <section class="invoice" style="clear:both;">
                  <div class="row">
                    <div class="col-xs-12" style="color: #6610f2">
                      <h2 class="page-header">
                        <i class="fa fa-exchange fa-clr"></i>&nbsp;<span class="company_info"> Finance Settlements</span><small class="pull-right" style="color: #6610f2">Date: <?php echo date("d/M/Y"); ?></small>
                      </h2>
                    </div>
                  </div> 
                  <div class="row">
                    <div class="col-sm-6 invoice-col" style="color: #6610f2">
                          <b></b><br><br>
                          <b>Supplier:</b>&nbsp;<span class="company_info"><?php echo $vendor_profile->name; ?></span><br><hr/>
                          <b>Commission Rate (Cash Orders):</b>&nbsp;<span> <?php echo $data->cash_commission; ?>%</span><br><hr/>
                          <b>Commission Rate (Card Orders):</b>&nbsp;<span> <?php echo $data->card_commission; ?>%</span><br><hr/>      
                    </div>
                    <div class="col-xs-6">
                    </div>
                    <div class="col-xs-6" style="color: #6610f2">
                      <p class="lead">Period:&nbsp;<span id="period"></span> </p>
                        <div class="table-responsive">
                          <table class="table">
                            <tbody>
                            <tr>
                              <th style="width:50%">Total Sales:</th>
                              <td id="count">255</td>
                            </tr>
                            <tr>
                              <th style="width:50%">Total Amount:</th>
                              <td id="total_amount">AED 20</td>
                            </tr>
                            <tr>
                              <th style="width:50%">Net Payable to Valucart:</th>
                              <td id="commission_amount">AED 20.56</td>
                            </tr>
                          
                            <tr>
                              <th>Net Payable from Valucart:</th>
                              <td id="vendor_payback_amount">242.25</td>
                            </tr>
                          </tbody></table>
                        </div>
                    </div>
                  </div>
                  <div class="row no-print">
                    <div class="col-xs-12">
                    </div>
                  </div>
              </section>
            </div>

          </div>
          <br/>
          <br/>
          <div class="error_div" style="text-align: center;"><br/><h4 style="color: red">No result found for particular date!</h4></div>      
                 
              </div>
            </div>
       </div>

   </section>



</div>

