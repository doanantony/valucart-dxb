<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Profile
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="#">
          <i class="fa fa-dashboard">
          </i> Home
        </a>
      </li>
      <li>
        <a href="#">Customers
        </a>
      </li>
      <li class="active">User profile
      </li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <?php
                  if($this->session->flashdata('message')) {
                    $message = $this->session->flashdata('message');
                    ?>
                    <div class="alert alert-<?php echo $message['class']; ?>">
                      <button class="close" data-dismiss="alert" type="button">×
                      </button>
                      <?php echo $message['message']; ?>
                    </div>
                    <?php
                  }
                  ?>
    <div class="row">
      <div class="col-md-3">
        <!-- Profile Image -->
        <div class="box box-primary">
          <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url(); ?>assets/uploads/customers/user.png" alt="User profile picture">
            <h3 class="profile-username text-center">
              <?php echo $data->name; ?>
            </h3>
            <p class="text-muted text-center"> <b><?php echo $data->email; ?></b>
            </p>
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Orders
                </b> 
                <a class="pull-right">
                  <span class="label label-primary pull-right">
                    <?php echo get_customer_totorders($data->id); ?>
                  </span>
                </a>
              </li>
              <li class="list-group-item">
                <b>Wallet
                </b> 
                <a class="pull-right"> 
                  <span class="label label-success pull-right">AED <?php echo $data->wallet; ?>
                  </span>
                </a>
              </li>
              <li class="list-group-item">
                <b>Items in Cart
                </b> 
                <a class="pull-right">
                  <span class="label label-warning pull-right">
                    <?php echo get_customer_cartitems($data->id); ?>
                  </span>
                </a>
              </li>
            </ul>
            <a  href="<?php echo base_url(); ?>Admincart" class="btn btn-info btn-block">
              <b>Place Order
              </b>
            </a>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <!-- About Me Box -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">More Info
            </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <strong>
              <i class="fa fa-user margin-r-5">
              </i> Member Since
            </strong>
            <p class="text-muted">
              <?php echo $data->created_at; ?>
            </p>
            <hr>
            <strong>
              <i class="fa fa-map-marker margin-r-5">
              </i> Location
            </strong>
            <p class="text-muted">Dubai
            </p>

            <hr>
           <strong>
              <i class="fa fa-envelope margin-r-5">
              </i> Contact Email
            </strong>
            <p class="text-muted">
              <?php echo $data->email; ?>
            </p>

            


          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="nav-tabs-custom">
          <div class="box-header with-border">
          <ul class="nav nav-tabs">

           <li>
              <a href="#topup" data-toggle="tab"><i class='fa fa-fw fa-plus'></i>
                <b>TOP UP
                </b>
              </a>
            </li>

            <li>
              <a href="#wallettrans" data-toggle="tab"><i class='fa fa-fw fa-google-wallet'></i>
                <b>WALLET
                </b>
              </a>
            </li>

            <li class="active">
              <a href="#cart" data-toggle="tab"><i class='fa fa-fw fa-cart-plus'></i>
                <b>CART(<span>
                    <?php echo get_customer_cartitems($data->id); ?>
                  </span>Items)
                </b>
              </a>
            </li>

            <li>
              <a href="#orders" data-toggle="tab"><i class='fa fa-fw fa-reorder'></i>
                <b>ORDERS
                </b>
              </a>
            </li>

            <li>
              <a href="#feedbacks" data-toggle="tab"><i class='fa fa-fw fa-commenting'></i>
                <b>FEEDBACKS
                </b>
              </a>
            </li>

            
          </ul>
          </div>

          <div class="tab-content">
            <div class="tab-pane" id="topup">
              <div class="row">
                <div class="col-xs-12">
                  
                </div>
                <!-- left column -->
                <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="">
                    <form role="form" action="" method="post"  data-parsley-validate="" class="form-horizontal"  enctype="multipart/form-data">
                      <div class="box-body">
                        <div class="form-group has-feedback">
                          <label for="exampleInputEmail1" class="col-sm-2 control-label">Top Up Amount
                          </label>
                          <div class="col-sm-5">
                            <input type="text" class="form-control " name="amount" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter Amount " value="">
                            <span class="glyphicon  form-control-feedback">
                            </span>
                          </div>
                        </div>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <button type="submit" class="btn btn-success">Recharge
                        </button>
                      </div>
                      <!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- /.tab-pane -->
            <div class="tab-pane" id="wallettrans">
              <div class="box-body">
                <table class="table table-bordered table-striped datatable">
                  <thead>
                    <tr>
                      <th class="hidden">ID</th>
                      <th>Description</th>
                      <th>Amount</th>
                      <th>Balance</th>
                      <th>Date</th>
                    </tr>
                  </thead> 
                  <tbody>
                    <?php
                    foreach($wallet_info as $wallet) {
                      ?>
                      <tr>
                        <td class="hidden"><?php echo $wallet->id; ?></td>
                        <td class="center"><?php echo $wallet->description; ?></td>
                        <td class="center">
                          <span class="center label  label label-danger">
                            AED <?php echo $wallet->transact_amt; ?>&nbsp;
                            <?php echo $wallet->type; ?>
                          </span>                                                         
                        </td>
                        <td class="center">
                          <span class="center label  label label-info">
                           AED <?php echo $wallet->amt_left; ?>&nbsp;
                           
                          </span>                                                         
                        </td>
                        <td class="center">
                          <?php echo $wallet->created_at; ?>
                        </td> 
                      </tr>
                      <?php
                    }
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="hidden">ID</th>
                      <th>Description</th>
                      <th>Amount</th>
                      <th>Balance</th>
                      <th>Date</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="active tab-pane" id="cart">

              <div class="box-body">
                <table class="table table-bordered table-striped datatable">
                  <thead>
                    <tr>
                      <th class="hidden">ID</th>
                      <th>Name</th>
                      <th>Qty</th>
                      <th>Type</th>
                      
                    </tr>
                  </thead> 
                  <tbody>
                    <?php
                    foreach($cart as $cartitems) {
                      ?>
                      <tr>
                        <td class="hidden"><?php echo $cartitems->id; ?></td>
                       <td class="center"><?php echo get_itemname_cart($cartitems->item_id,$cartitems->item_type) ?></td>
                        <td class="center"><?php echo $cartitems->quantity; ?></td>
                        <td class="center"><?php echo $cartitems->item_type; ?></td>
                        
                      </tr>
                      <?php
                    }
                    ?>
                  </tbody>
                  
                </table>
              </div>
                          <!-- <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Notify the Customer</h3>
            </div>
            <div class="box-body">
              <form role="form">
                

                  <div class="form-group has-success">
                  <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> Input with success</label>
                  <input type="text" class="form-control" id="inputSuccess" placeholder="Enter ...">
                  <span class="help-block">Help block with success</span>
                </div>
                <div class="form-group has-warning">
                  <label class="control-label" for="inputWarning"><i class="fa fa-bell-o"></i> Input with
                    warning</label>
                  <input type="text" class="form-control" id="inputWarning" placeholder="Enter ...">
                  <span class="help-block">Help block with warning</span>
                </div>
                <div class="form-group has-error">
                  <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with
                    error</label>
                  <input type="text" class="form-control" id="inputError" placeholder="Enter ...">
                  
                </div>

              </form>
            </div>
          </div> -->

            </div>
            <!-- /.tab-pane -->

            <div class="tab-pane" id="orders">
              <div class="row">
                <div class="col-xs-12">
                  
                </div>
               
              </div>
            </div>

            <div class="tab-pane" id="feedback">
              <div class="row">
                <div class="col-xs-12">
                  
                </div>
                <!-- left column -->
                
              </div>
            </div>

          </div>
          <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>