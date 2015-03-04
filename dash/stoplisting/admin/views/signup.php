<form  action="" class="cmxform form-signin" id="signupForm" method="post">
  <input type="hidden" name="action" value="adminsignup" />
  <h2 class="form-signin-heading">registration now</h2>
  <div class="login-wrap">
    <p>Enter your personal details below</p>
    <div class="col-lg-13" style="margin-bottom:15px;">
      <input type="text" class="form-control" name="firstname" placeholder="Full Name" style="margin-bottom:0px;" autofocus>
    </div>
    <div class="col-lg-13" style="margin-bottom:15px;">
      <input class="form-control " name="email" type="text" style="margin-bottom:0px;" placeholder="Email" autofocus>
    </div>
    <div class="col-lg-13" style="margin-bottom:15px;">
      <input type="text" class="form-control" name="site_url" style="margin-bottom:0px;" placeholder="Site URL" autofocus>
    </div>
    <p> Enter your account details belowsssssss</p>
    <div class="col-lg-13" style="margin-bottom:15px;">
      <input class="form-control " name="username" style="margin-bottom:0px;" type="text" placeholder="User Name" autofocus>
    </div>
    <div class="col-lg-13" style="margin-bottom:15px;">
      <input class="form-control " id="password" name="password" style="margin-bottom:0px;" type="password" placeholder="Password">
    </div>
    <div class="col-lg-13" style="margin-bottom:15px;">
      <input class="form-control " style="margin-bottom:0px;" id="confirm_password" name="confirm_password" type="password" placeholder="Re-type Password">
    </div>
    <label class="checkbox">
    <input  type="checkbox" style="margin-bottom:0px;width: 35px;" class="checkbox form-control" id="agree" name="agree" />
    I agree to the Terms of Service and Privacy Policy </label>
    <button class="btn btn-lg btn-login btn-block" type="submit">Submit</button>
    <div class="registration"> Already Registered. <a class="" href="<?=SAURL?>index.php?p=login"> Login </a> </div>
  </div>
</form>
