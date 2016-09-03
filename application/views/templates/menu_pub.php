<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="brand" href="<?php echo site_url('/reports/dashboard')?>"><?php echo $_title?> - PUB</a>
      <div class="nav-collapse collapse">
        <ul class="nav pull-left">
          <li <?php if ($_location == 'campaigns') { ?>class="active"<?php } ?>>
            <a href="<?php echo site_url('/campaigns/my_list')?>"><?php l('campaigns')?></a>
          </li>
          <li <?php if ($_location == 'advertiser') { ?>class="active"<?php } ?>>
            <a href="<?php echo site_url('/advertiser/listing')?>"><?php l('advertisers')?></a>
          </li>
          <li <?php if ($_location == 'publisher') { ?>class="active"<?php } ?>>
            <a href="<?php echo site_url('/publisher/sections')?>"><?php l('sites')?></a>
          </li>
          <li <?php if ($_location == 'approver') { ?>class="active"<?php } ?>>
            <a href="<?php echo site_url('/campaigns/approver')?>">
              <?php l('approver')?>
              <?php if ($_nr_of_unapproved_campaigns): ?>
              <span class="badge badge-info"><?php echo $_nr_of_unapproved_campaigns ?></span>
              <?php endif ?>
            </a>
            
          </li>
          <li <?php if ($_location == 'finance') { ?>class="active"<?php } ?>>
            <a href="<?php echo site_url('/publisher/finance')?>"><?php l('finances')?></a>
          </li>
          <li <?php if ($_location == 'settings') { ?>class="active"<?php } ?>>
            <a href="<?php echo site_url('/publisher/settings')?>"><?php l('settings')?></a>
          </li>
          <li <?php if ($_location == 'plan') { ?>class="active"<?php } ?>>
            <a href="<?php echo site_url('/publisher/plans')?>"><?php l('budget plans')?></a>
          </li>
        </ul>
        <ul class="nav pull-right">
          <li <?php if ($_location == 'account') { ?>class="active"<?php } ?>>
              <a href='<?php echo site_url('/account/change_password')?>'><?php echo $_my_name?></a>
          </li>
          <li>
              <a href='<?php echo site_url('/account/logout')?>'><?php l('logout')?></a>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>