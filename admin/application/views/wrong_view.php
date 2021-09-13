<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <?php echo $title; ?>
         <small><?php echo $small; ?></small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i> Permission</a></li>
         <li><a href="#">Valucart General Trading</a></li>
         <li class="active">403 error</li>
      </ol>
   </section>
   <section class="content">
      <div class="error-page">
         <h2 class="headline text-yellow"> 403</h2>
         <div class="error-content">
            <h3><i class="fa fa-lock text-yellow"></i> Oops! No Permission.</h3>
            <p>
               We could not find access permission to view this page.
               Meanwhile, you may <a href="#" onclick="window.history.back();">return to Previous Page</a> or try using the search form.
            </p>
            <form class="search-form">
               <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Search" id="search_val">
                  <div class="input-group-btn">
                     <button type="button" name="submit" class="btn btn-warning btn-flat" id="search"><i class="fa fa-search"></i></button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </section>
</div>
<script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.4.min.js'); ?>"></script>
  <script type="text/javascript">
        $("#search").on('click',function(){
          var search = $("#search_val").val();
          window.location.href = base_url+search;
        })
  </script>