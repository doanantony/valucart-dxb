
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo base_url(); ?>assets/images/profile avator.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
               <?php 
                $session_data = $this->session->userdata('logged_in');
                $user_type_id = $session_data['user_type_id'];
                $user_id = $session_data['user_id'];
               ?>
               

                            <?php 
          $session_data = $this->session->userdata('logged_in');
          if($session_data['user_type_id'] == 5){
          ?>
                         <p><?php echo $session_data['username']; ?></p>

          
                              <?php
                                 }
                                 else
                                 {
                                 ?>
                              <p><?php echo get_admin_name($user_id, $user_type_id); ?></p>



                              <?php
                                 }
                                 ?>



              <!-- <p>Admin</p> -->
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          
        

            <ul class="sidebar-menu">
            <li class="header">LEFT NAVIGATION</li>
           <!-- <?php //print_r($perm);?> -->
           <!-- <?php
            //$role_id = $this->session->userdata('user_type_id');
            //$perm = get_user_permit($role_id);
            ?> -->
            <?php if(isset($perm)){?>
            <?php
              foreach ($perm as $rs) { 
                 

                $num = count($rs->sub);
                if($num>1){ ?>

                   <li class="treeview <?php echo side_nav($rs->module_menu,$main);?>">
                      <a href="#">
                        <i class="fa <?php echo $rs->module_class; ?>"></i> <span><?php echo $rs->module_name; ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                      </a>
                      <ul class="treeview-menu">
                      <?php foreach($rs->sub as $row) {
                        ?>
                        <li class="<?php echo sub_nav($row->function_menu,$sub);?>">
                        <?php if($row->function_path!='index'){
                          $control_path = $rs->module_control."/".$row->function_path;
                         
                          
                        } else {
                            $control_path = $rs->module_control;

                        } ?>

                          <a href="<?php echo base_url($control_path); ?>">
                            <i class="fa <?php echo $row->function_class; ?>"></i> <span><?php echo $row->function_name; ?></span>
                          </a>
                        </li>
                        <?php } ?>                         
                           
                      </ul>
                    </li>

                <?php  } else {
                 
                  if($rs->sub[0]->parent==1){ ?>
                  <li class="treeview">
                    <li class="<?php echo side_nav($rs->module_menu,$main);?>"><a href="<?php echo base_url($rs->module_control); ?>">
                      <i class="fa <?php echo $rs->module_class; ?>" ></i> <span><?php echo $rs->module_name; ?></span> 
                    </a></li>
                  </li> 

                  <?php } else { ?>

                    <li class="treeview <?php echo side_nav($rs->module_menu,$main);?>">
                      <a href="#">
                        <i class="fa <?php echo $rs->module_class; ?>"></i> <span><?php echo $rs->module_name; ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                      </a>
                      <ul class="treeview-menu">
                      <?php foreach($rs->sub as $row) {?>
                        <li class="<?php echo sub_nav($row->function_menu,$sub);?>">
                        <?php if($row->function_path!='index'){
                          $control_path = $rs->module_control."/".$row->function_path;
                        } else {
                            $control_path = $rs->module_control;
                        } ?>

                          <a href="<?php echo base_url($control_path); ?>">
                            <i class="fa <?php echo $row->function_class; ?>"></i> <span><?php echo $row->function_name; ?></span>
                          </a>
                        </li>
                        <?php } ?>                         
                           
                      </ul>
                    </li>

                 <?php  }
                }

              }
              
            ?>

                       <?php } ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
