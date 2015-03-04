<form  action="" class="cmxform form-signin" id="signupForm" method="post">
  <img class="form-signin-heading" src="<?=SURL?>img/logo.png" width="100" alt="Stoplisting - Home"/>
  <div class="login-wrap">
    <div class="user-login-info">
      <input type="text" class="form-control" id="username" name="usrname" placeholder="User Name" autofocus>
      <input type="password" class="form-control" id="password" name="pass" placeholder="Password">
      <input type="hidden" name="action" value="loginadmin" />
    </div>
    <!--<label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>

                </span>
            </label>-->
    <button class="btn btn-lg btn-login btn-block" type="submit">Authenticate</button>
  </div>
  <!-- Modal -->
  <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Forgot Password ?</h4>
        </div>
        <div class="modal-body">
          <p>Enter your e-mail address below to reset your password.</p>
          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
        </div>
        <div class="modal-footer">
          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
          <button class="btn btn-success" type="button">Submit</button>
        </div>
      </div>
    </div>
  </div>
  <!-- modal -->
</form>
<style>
.form-signin-heading {margin:10px 17px;}
.btn-block {background: #333 !important;}
</style>