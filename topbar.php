<style>
	.logo {
    margin: auto;
    font-size: 20px;
    background: white;
    padding: 5px 13px;
    border-radius: 50% 50%;
    color: #000000b3;
}
</style>

<nav class="navbar navbar-expand-md navbar-light fixed-top" style="padding-top:0%; background-color:#DFF6FF;">
  <div class="container-fluid" style="padding:0px; height: 50px;">
        <a href="#" style="height: 47px; position:absolute-flex;">
          <img src="assets/img/logo3.png" alt="logo" height="55px" style="padding: 0px; float:inline-start;"> 
        </a>
      <div class="float-right">
  	  	<div class=" dropdown mr-2">

            <a href="#" style="color: #041520;" class="text-black dropdown-toggle"  id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo "Welcome Back " . $_SESSION['login_name'] ?> </a>
            <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
              <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>
              <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </div>
    </div>
  </div>
</nav>
<script>
  $('#manage_my_account').click(function(){
    uni_modal("Manage Account","manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
  })
</script>