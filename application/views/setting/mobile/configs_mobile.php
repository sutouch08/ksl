<?php $this->load->view('include/header'); ?>
<?php $this->load->view('setting/mobile/style'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    	<h4 class="title"><?php echo $this->title; ?> Mobile</h4>
	</div>
</div>

<div class="row">
	<div class="setting-menu">
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('general')">General <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('system')">System <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('inventory')">Inventory <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('order')">Orders <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('bookcode')">Book code <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('document')">Documents <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('SAP')">SAP <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('ix')">IX API <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('wrx')">WRX API <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
		<div class="menu-block">
			<a class="menu-link" href="javascript:goSetting('lnw')">LNW SHOP API <i class="fa fa-angle-right pull-right font-size-20"></i></a>
		</div>
	</div>
</div><!--/ row  -->
<div class="setting-panel move-out" id="general">
	<?php $this->load->view('setting/mobile/setting_company_mobile'); ?>
</div>

<div class="setting-panel move-out" id="system">
	<?php $this->load->view('setting/mobile/setting_system_mobile'); ?>
</div>

<div class="setting-panel move-out" id="order">
	<?php $this->load->view('setting/mobile/setting_order_mobile'); ?>
</div>


<script src="<?php echo base_url(); ?>scripts/setting/setting_mobile.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/setting/setting_document.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
