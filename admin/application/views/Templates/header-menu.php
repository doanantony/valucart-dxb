<header class="main-header">
   <?php 
      $session_data = $this->session->userdata('logged_in');
      if($session_data['user_type_id'] == 5){
      ?>
   <a href="<?php echo base_url().'vendorhome' ?>" class="logo">
   <?php
      }
      else
      {
      ?>
   <a href="<?php echo base_url().'home' ?>" class="logo">
   <?php
      }
      ?>
   <span class="logo-mini"><img src="<?php echo base_url('assets/images/profile avator.png') ;?>" width="30px"></span>
   <span class="logo-lg"><b>Valucart</b></span>
   </a>
   <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
         <ul class="nav navbar-nav">
            <?php 
               $session_data = $this->session->userdata('logged_in');
               $user_type_id = $session_data['user_type_id'];
               $user_id = $session_data['user_id'];
               ?>
            <li class="dropdown messages-menu">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               <b> Quick Links</b>
               </a>
               <ul class="dropdown-menu">
                  <li>
                     <ul class="menu">
                        <li>
                           <a href="<?php echo base_url(); ?>Admincart">
                              <div class="pull-left">
                              </div>
                              <h4> <i class="glyphicon glyphicon-shopping-cart icon-white"></i>
                                 <b>Admincart</b>
                              </h4>
                           </a>
                        </li>
                        <li>
                           <a href="<?php echo base_url(); ?>Allorders">
                              <div class="pull-left">
                              </div>
                              <h4><i class="glyphicon glyphicon-list icon-white"></i>
                                 <b>Orders</b>
                              </h4>
                           </a>
                        </li>
                        <li>
                           <a href="<?php echo base_url(); ?>Products">
                              <div class="pull-left">
                              </div>
                              <h4><i class="glyphicon glyphicon-briefcase icon-white"></i>
                                 <b>Products</b>
                              </h4>
                           </a>
                        </li>
                     </ul>
                  </li>
               </ul>
            </li>
            <li class="dropdown user user-menu">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               <img src="<?php echo base_url(); ?>assets/images/profile avator.png" class="user-image" alt="User Image">
               <?php 
                  $session_data = $this->session->userdata('logged_in');
                  if($session_data['user_type_id'] == 5){
                  ?>
               <b><span class="hidden-xs"><?php echo $session_data['username']; ?>
               <?php
                  }
                  else
                  {
                  ?>
               <b><span class="hidden-xs"><?php echo get_admin_name($user_id, $user_type_id); ?>
               <?php
                  }
                  ?>
               </span></b>
               </a>
               <ul class="dropdown-menu">
                  <li class="user-header">
                     <img src="<?php echo base_url(); ?>assets/images/profile avator.png" class="img-circle" alt="User Image">
                  </li>
                  <li class="user-footer">
                     <div class="pull-left">
                        <a href="<?php echo base_url(); ?>profile"" class="btn btn-default btn-flat">Profile</a>
                     </div>
                     <div class="pull-right">
                        <a href="<?php echo base_url(); ?>logout" class="btn btn-default btn-flat">Sign out</a>
                     </div>
                  </li>
               </ul>
            </li>
         </ul>
      </div>
   </nav>
</header>