<header class="header fixed-top clearfix">
    <!--logo start-->
    <div class="brand"> <a href="<?=SAURL?>dash" class="logo"> <img src="<?=SURL?>img/logo-invert.png" width="190" alt="Stoplisting - Home"/> </a>
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
      </div>
    </div>
    <!--logo end-->
    <div class="top-nav clearfix">
      <!--search & user info start-->
      <ul class="nav pull-right top-menu">
        <li>
        </li>
        <!-- user login dropdown start-->
        <li class="dropdown"> <a data-toggle="dropdown" class="dropdown-toggle" href="#"> <img id="user_avatar" src="<?=SURL?>img/avatar/user_img.jpg" alt="user_avatar"/><span class="username"><?php echo $_SESSION['admin_name'];?></span> <b class="caret"></b> </a>
          <ul class="dropdown-menu extended logout">
            <li><a href="<?=SAURL?>a_set"><i class="fa fa-cog"></i> Settings</a></li>
            <li><a href="<?=SAURL?>logout"><i class="fa fa-key"></i> Log Out</a></li>
			<!--index.php?p=-->
          </ul>
        </li>
        <!-- user login dropdown end -->
      </ul>
      <!--search & user info end-->
    </div>
  </header>