<div class="row">
	<div class="col-lg-11 col-md-10 col-sm-10 col-xs-8 margin-top-5 margin-bottom-5 padding-5">
		<?php if(!$order->is_expired ) : ?>
			<?php if($allowEditPrice) : ?>
				<button type="button" class="btn btn-sm btn-default" id="btn-edit-price" onClick="showPriceBox()">แก้ไขราคา</button>
				<button type="button" class="btn btn-sm btn-primary hide" id="btn-update-price" onClick="getApprove('price')">บันทึกราคา</button>
			<?php endif; ?>
		<?php endif; ?>
	</div>	
</div>
<hr/>


<?php $this->load->view('validate_credentials'); ?>

<script src="<?php echo base_url(); ?>scripts/orders/order_discount.js"></script>
