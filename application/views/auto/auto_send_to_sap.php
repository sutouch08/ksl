<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 padding-top-5">
		<h4 class="title">รอดำเนินการ <?php echo $count; ?> ออเดอร์ จากทั้งหมด <?php echo number($all); ?></h4>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-white btn-info top-btn" onclick="refresh()"><i class="fa fa-refresh"></i> Refresh</button>
		<button type="button" class="btn btn-white btn-success btn-50 top-btn" onclick="startExport()"><i class="fa fa-bolt"></i> Start Export</button>
	</div>
</div>
<hr />
<div class="row">
	<form id="searchForm" method="post" action="<?php echo $this->home; ?>">
		<div class="col-lg-2-harf col-md-2-harf col-sm-3-harf col-xs-4 padding-5">
			<div class="input-group">
				<span class="input-group-addon">รหัส</span>
				<input type="text" class="form-control input-sm" id="code" name="code" placeholder="Order Code" value="<?php echo $code; ?>" />
			</div>
		</div>
		<div class="col-lg-2 col-md-2-harf col-sm-3-harf col-xs-4 padding-5">
			<div class="input-group">
				<span class="input-group-addon">สถานะ</span>
				<select class="form-control input-sm" id="status" name="status">
					<option value="0" <?php echo is_selected($status, '0'); ?>>Pending</option>
					<option value="1" <?php echo is_selected($status, '1'); ?>>Success</option>
					<option value="3" <?php echo is_selected($status, '3'); ?>>Error</option>
					<option value="all" <?php echo is_selected($status, 'all'); ?>>All</option>
				</select>
			</div>
		</div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
			<button type="submit" class="btn btn-xs btn-primary btn-block" style="height:30px;">Search</button>
		</div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearOption()" style="height:30px;">Clear</button>
		</div>
	</form>
</div>
<hr />
<div class="row" id="result">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped border-1" style="min-width:500px;">
			<thead>
				<tr>
					<th class="fix-width-40 text-center">#</th>
					<th class="fix-width-150">Order</th>
					<th class="fix-width-100">Status</th>
					<th class="min-width-100">message</th>
				</tr>
			</thead>
			<tbody>
				<?php if (! empty($data)) : ?>
					<?php $no = 1; ?>
					<?php foreach ($data as $rs) : ?>
						<tr>
							<td class="text-center"><?php echo $no; ?></td>
							<td>
								<?php echo $rs->code; ?>
								<input type="hidden" class="order" data-id="<?php echo $rs->id; ?>" data-no="<?php echo $no; ?>" id="code-<?php echo $rs->id; ?>" value="<?php echo $rs->code; ?>" />
							</td>
							<td id="status-<?php echo $rs->id; ?>"><?php echo $rs->status == 0 ? "Pending" : ($rs->status == 1 ? "Success" : "Error"); ?></td>
							<td id="msg-<?php echo $rs->id; ?>"><?php echo $rs->message; ?></td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="4" class="text-center">---- No Order ----</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<input type="hidden" id="count" value="<?php echo $count; ?>" />
	</div>
</div>

<script>
	var HOME = BASE_URL + 'auto/auto_send_delivery';

	var finished = false;
	var max = 0;
	var orders = [];

	function startExport() {
		load_in();

		max = parseDefault(parseInt($('#count').val()), 0);

		$('.order').each(function() {
			let code = $(this).val();
			let id = $(this).data('id');
			orders.push({
				'code': code,
				'id': id
			});
		});

		if (orders.length > 0 && max > 0) {
			do_export(0);
		}
		else {
			load_out();
		}
	}


	function do_export(no) {
		let order = orders[no];
		let code = order.code;
		let id = order.id;

		if (finished == false) {
			if (code != null && code != "" && code != undefined) {
				$.ajax({
					url: `${BASE_URL}inventory/delivery_order/manual_export/${code}`,
					type: 'GET',
					cache: false,
					success: function(rs) {

						if (rs.trim() == 'success') {
							$('#status-' + id).text('Success');
							no++;
							if (no == max) {
								update_status(code, 1, rs);
								finished = true;
								load_out();
							} else {
								update_status(code, 1, rs);

								do_export(no);
							}
						} else {
							$('#status-' + id).text('Error');
							$('#msg-' + id).text(rs);
							no++;
							if (no == max) {
								update_status(code, 3, rs);
								finished = true;
								load_out();
							} else {
								update_status(code, 3, rs);
								do_export(no);
							}
						}
					}
				})
			}
		}
	}


	function update_status(code, status, message) {
		$.ajax({
			url: BASE_URL + 'auto/auto_send_delivery/update_status',
			type: 'POST',
			cache: false,
			data: {
				'code': code,
				'status': status,
				'message': message
			},
			success: function(rs) {
				console.log(rs);
			}
		})
	}

	function clearOption() {
		$('#code').val('');
		$('#status').val('0');
		$('#searchForm').submit();
	}
</script>

<?php $this->load->view('include/footer'); ?>