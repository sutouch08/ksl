	<form id="lnwForm" method="post" action="<?php echo $this->home; ?>/update_config">





  	<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				<span class="form-control left-label">LNW SHOP API</span>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				<label style="padding-top:5px; margin-bottom:0px;">
					<input class="ace ace-switch ace-switch-7" data-name="LNW_SHOP_API" type="checkbox" value="1" <?php echo is_checked($LNW_SHOP_API , '1'); ?> onchange="toggleOption($(this))"/>
					<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
				</label>
				<input type="hidden" name="LNW_SHOP_API" value="<?php echo $LNW_SHOP_API; ?>"/>
			</div>
			<div class="divider"></div>

    	<div class="col-lg-4 col-md-4 col-sm-4">
        <span class="form-control left-label">API Endpoint</span>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-8">
        <input type="text" class="form-control input-sm input-xxlarge" name="LNW_SHOP_HOST"  value="<?php echo $LNW_SHOP_HOST; ?>" />
      </div>
      <div class="divider"></div>

      <div class="col-lg-4 col-md-4 col-sm-4">
        <span class="form-control left-label">Shop ID</span>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-8">
        <input type="text" class="form-control input-sm input-xxlarge" name="LNW_SHOP_ID" value="<?php echo $LNW_SHOP_ID; ?>" />
      </div>
      <div class="divider"></div>

      <div class="col-lg-4 col-md-4 col-sm-4">
        <span class="form-control left-label">X-API-KEY</span>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-8">
        <input type="text" class="form-control input-sm input-xxlarge" name="LNW_SHOP_API_CREDENTIAL" value="<?php echo $LNW_SHOP_API_CREDENTIAL; ?>" />
      </div>
      <div class="divider"></div>

      <div class="col-lg-4 col-md-4 col-sm-4">
        <span class="form-control left-label">Default Warshouse</span>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-8">
				<select class="fix-width-350" id="lnw-warehouse" name="LNW_SHOP_WAREHOUSE" onchange="lnwZoneInit()">
					<option value="">เลือกคลัง</option>
					<?php echo select_sell_warehouse($LNW_SHOP_WAREHOUSE); ?>
				</select>
      </div>
      <div class="divider"></div>

			<div class="col-lg-4 col-md-4 col-sm-4">
        <span class="form-control left-label">Default Zone</span>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-8">
        <input type="text" class="form-control input-sm input-large" id="lnw-zone" name="LNW_SHOP_ZONE" value="<?php echo $LNW_SHOP_ZONE; ?>" />
      </div>
      <div class="divider"></div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				<span class="form-control left-label">Sync Stock</span>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				<label style="padding-top:5px; margin-bottom:0px;">
					<input class="ace ace-switch ace-switch-7" data-name="SYNC_LNW_SHOP_STOCK" type="checkbox" value="1" <?php echo is_checked($SYNC_LNW_SHOP_STOCK , '1'); ?> onchange="toggleOption($(this))"/>
					<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
				</label>
				<input type="hidden" name="SYNC_LNW_SHOP_STOCK" value="<?php echo $SYNC_LNW_SHOP_STOCK; ?>"/>
			</div>
			<div class="divider"></div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				<span class="form-control left-label">Logs Json</span>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				<label style="padding-top:5px; margin-bottom:0px;">
					<input class="ace ace-switch ace-switch-7" data-name="LNW_SHOP_LOG_JSON" type="checkbox" value="1" <?php echo is_checked($LNW_SHOP_LOG_JSON , '1'); ?> onchange="toggleOption($(this))"/>
					<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
				</label>
				<input type="hidden" name="LNW_SHOP_LOG_JSON" value="<?php echo $LNW_SHOP_LOG_JSON; ?>"/>
			</div>
			<div class="divider"></div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				<span class="form-control left-label">Test Mode</span>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				<label style="padding-top:5px; margin-bottom:0px;">
					<input class="ace ace-switch ace-switch-7" data-name="LNW_SHOP_TEST" type="checkbox" value="1" <?php echo is_checked($LNW_SHOP_TEST , '1'); ?> onchange="toggleOption($(this))"/>
					<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
				</label>
				<input type="hidden" name="LNW_SHOP_TEST" value="<?php echo $LNW_SHOP_TEST; ?>"/>
			</div>
			<div class="divider"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>

			<div class="col-lg-8 col-lg-offset-4 col-md-8 col-md-offset-4 col-sm-8 col-sm-offset-8">
				<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
        <button type="button" class="btn btn-sm btn-success btn-100" onClick="updateConfig('lnwForm')">SAVE</button>
				<?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
