<div class="nav-title">
	<span class="back-link" onclick="closeTab()"><i class="fa fa-angle-left fa-lg"></i> ย้อนกลับ</span>
	<span class="font-size-14 text-left">LNW SHOP API Setting</span>
</div>

<form id="lnwForm" class="margin-top-30" method="post" action="<?php echo $this->home; ?>/update_config">
	<div class="row">
		<div class="col-xs-8 padding-top-5">LNW SHP API</div>
		<div class="col-xs-4 text-right">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="LNW_SHOP_API" type="checkbox" value="1" <?php echo is_checked($LNW_SHOP_API , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="LNW_SHOP_API" value="<?php echo $LNW_SHOP_API; ?>"/>
		</div>
		<div class="divider"></div>

		<div class="col-xs-12">API Endpoint</div>
		<div class="col-xs-12 padding-top-5">
			<input type="text" class="width-100" name="LNW_SHOP_HOST"  value="<?php echo $LNW_SHOP_HOST; ?>" />
		</div>
		<div class="divider"></div>

		<div class="col-xs-6 padding-top-5">Shop ID</div>
		<div class="col-xs-6 text-right">
			<input type="text" class="width-100" name="LNW_SHOP_ID"  value="<?php echo $LNW_SHOP_ID; ?>" />
		</div>
		<div class="divider"></div>

		<div class="col-xs-12">API Credential</div>
		<div class="col-xs-12 padding-top-5">
			<textarea class="width-100" rows="3" name="LNW_SHOP_API_CREDENTIAL"><?php echo $LNW_SHOP_API_CREDENTIAL; ?></textarea>
		</div>
		<div class="divider"></div>

		<div class="col-xs-12">LNW SHOP Warehouse</div>
		<div class="col-xs-12 padding-top-5">
			<select class="width-100" id="lnw-shop-warehouse" name="LNW_SHOP_WAREHOUSE">
				<option value="">เลือกคลัง</option>
				<?php echo select_sell_warehouse($LNW_SHOP_WAREHOUSE); ?>
			</select>
		</div>
		<div class="divider"></div>

		<div class="col-xs-6 padding-top-5">LNW SHOP Zone</div>
		<div class="col-xs-6 text-right">
			<input type="text" class="width-100 text-center" id="lnw-shop-zone" name="LNW_SHOP_ZONE" value="<?php echo $LNW_SHOP_ZONE; ?>"/>
		</div>
		<div class="divider"></div>

		<div class="col-xs-8 padding-top-5">SYNC API STOCK</div>
		<div class="col-xs-4 text-right">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="SYNC_LNW_SHOP_STOCK" type="checkbox" value="1" <?php echo is_checked($SYNC_LNW_SHOP_STOCK , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="SYNC_LNW_SHOP_STOCK" value="<?php echo $SYNC_LNW_SHOP_STOCK; ?>"/>
		</div>
		<div class="divider"></div>

		<div class="col-xs-8 padding-top-5">Logs Json</div>
		<div class="col-xs-4 text-right">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="LNW_SHOP_LOG_JSON" type="checkbox" value="1" <?php echo is_checked($LNW_SHOP_LOG_JSON , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="LNW_SHOP_LOG_JSON" value="<?php echo $LNW_SHOP_LOG_JSON; ?>"/>
		</div>
		<div class="divider"></div>

		<div class="col-xs-8 padding-top-5">Test Mode</div>
		<div class="col-xs-4 text-right">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="LNW_SHOP_TEST" type="checkbox" value="1" <?php echo is_checked($LNW_SHOP_TEST , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="LNW_SHOP_TEST" value="<?php echo $LNW_SHOP_TEST; ?>"/>
		</div>
		<div class="divider"></div>
		<div class="divider-hidden"></div>
		<div class="divider-hidden"></div>

		<div class="col-xs-12">
			<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
				<button type="button" class="btn btn-sm btn-success btn-block" onClick="updateConfig('lnwForm')">SAVE</button>
			<?php endif; ?>
		</div>
		<div class="divider-hidden"></div>

	</div><!--/ row -->
</form>
