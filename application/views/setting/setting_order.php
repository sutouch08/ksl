<form id="orderForm" method="post" action="<?php echo $this->home; ?>/update_config">
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">อายุของออเดอร์ ( วัน )</span></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
      <input type="text" class="form-control input-sm input-small text-center" name="ORDER_EXPIRATION" required value="<?php echo $ORDER_EXPIRATION; ?>" />
      <span class="help-block">กำหนดวันหมดอายุของออเดอร์ หากออเดอร์อยู่ในสถานะ รอการชำระเงิน, รอจัดสินค้า หรือ ไม่บันทึก เกินกว่าจำนวนวันที่กำหนด</span>
    </div>
    <div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">ประเภทของออเดอร์ที่หมดอายุ</span></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
      <input type="text" class="form-control input-sm input-xlarge" name="ORDER_EXPIRATION_ROLE" required value="<?php echo $ORDER_EXPIRATION_ROLE; ?>" />
      <span class="help-block">
				กำหนดประเภทของออเดอร์ที่ทำให้หมดอายุ <br>
				S = ขาย, C = ฝากขายเทียม, N = ฝากขายแท้, P = สปอนเซอร์, U = อภินันท์, L = ยืม, T = แปรสภาพ(ขาย), Q = แปรสภาพ(สต็อก)
			</span>
    </div>
    <div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">การจำกัดการแสดงผลสต็อก</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<input type="text" class="form-control input-sm input-small text-center" name="STOCK_FILTER" required value="<?php echo $STOCK_FILTER; ?>" />
			<span class="help-block">กำหนดจำนวนสินค้าคงเหลือสูงสุดที่จะแสดงใหเห็น ถ้าไม่ต้องการใช้กำหนดเป็น 0 </span>
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">แสดงยอดรวมสต็อก</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="SHOW_SUM_STOCK" type="checkbox" value="1" <?php echo is_checked($SHOW_SUM_STOCK , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="SHOW_SUM_STOCK" id="show-sum-stock" value="<?php echo $SHOW_SUM_STOCK; ?>" />
			<span class="help-block">แสดงยอดรวมสินค้าคงเหลือในหน้าออเดอร์หรือไม่ (หากเปิดไว้ระบบจะทำงานช้าลง)</span>
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">วันที่ในการบันทึกขายตัดสต็อก</span></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
      <div class="btn-group input-medium">
        <select class="form-control input-sm input-medium" name="ORDER_SOLD_DATE">
					<option value="B" <?php echo is_selected("B", $ORDER_SOLD_DATE); ?>>วันที่เปิดบิล</option>
					<option value="D" <?php echo is_selected("D", $ORDER_SOLD_DATE); ?>>วันที่เอกสาร</option>
				</select>
      </div>
      <span class="help-block">กำหนดประเภทวันที่ที่ใช้ในการบันทึกขายและตัดสต็อกในระบบ SAP</span>
    </div>
    <div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">รหัสลูกค้าเริ่มต้น</span></div>
		<div class="col-lg-2 col-md-2 col-sm-2 padding-5 first">
			<input type="text" class="width-100 text-center" name="DEFAULT_CUSTOMER" id="default-customer-code" value="<?php echo $DEFAULT_CUSTOMER; ?>" />
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 padding-5 last">
			<input type="text" class="width-100" id="default-customer-name" value="<?php echo customer_name($DEFAULT_CUSTOMER); ?>" readonly />
		</div>
		<div class="col-lg-8 col-lg-offset-4 col-md-8 col-md-offset-4 col-sm-8 col-sm-offset-8">
			<span class="help-block">ลูกค้าเริ่มต้นหากไม่มีการกำหนดรหัสลูกค้า</span>
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">ควบคุมเครดิต</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="CONTROL_CREDIT" type="checkbox" value="1" <?php echo is_checked($CONTROL_CREDIT , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="CONTROL_CREDIT" id="control-credit" value="<?php echo $CONTROL_CREDIT; ?>" />
			<span class="help-block">ใช้การควบคุมเครดิตหรือไม่ หากควบคุมจะไม่สามารถเปิดออเดอร์ได้ถ้าเครดิตคงเหลือไม่เพียงพอ</span>
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">วันเพิ่มในการคุมเครดิต</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<input type="number" class="form-control input-sm input-small text-center" name="OVER_DUE_DATE" required value="<?php echo $OVER_DUE_DATE; ?>" />
			<span class="help-block">จำนวนวันเพิ่มจากวันครบกำหนดชำระ เช่น เครดติ 30 วัน เพิ่มอีก 30 วัน</span>
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">มียอดค้างชำระเกินกำหนด</span></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="STRICT_OVER_DUE" type="checkbox" value="1" <?php echo is_checked($STRICT_OVER_DUE , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
      <span class="help-block">ไม่อนุญาติให้ขายสินค้าให้ลูกค้าที่มียอดค้างชำระเกินวันที่กำหนดในการคุมเครดิต</span>
			<input type="hidden" name="STRICT_OVER_DUE" id="strict-over-due" value="<?php echo $STRICT_OVER_DUE; ?>" />
    </div>
    <div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">การแก้ไขส่วนลดในออเดอร์</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="ALLOW_EDIT_DISCOUNT" type="checkbox" value="1" <?php echo is_checked($ALLOW_EDIT_DISCOUNT , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<span class="help-block">กรณีปิดจะไม่สามารแก้ไขส่วนลดในออเดอร์ได้ ส่วนลดจะถูกคำนวณโดยระบบเท่านั้น</span>
			<input type="hidden" name="ALLOW_EDIT_DISCOUNT" id="allow-edit-discount" value="<?php echo $ALLOW_EDIT_DISCOUNT; ?>" />
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">การแก้ไขราคาในออเดอร์</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="ALLOW_EDIT_PRICE" type="checkbox" value="1" <?php echo is_checked($ALLOW_EDIT_PRICE , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<span class="help-block">กรณีปิดจะไม่สามารแก้ไขราคาขายสินค้าในออเดอร์ได้ จะใช้ราคาขายในระบบเท่านั้น</span>
			<input type="hidden" name="ALLOW_EDIT_PRICE" id="allow-edit-price" value="<?php echo $ALLOW_EDIT_PRICE; ?>" />
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">การ Import Order ด้วยไฟล์ Excel</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="ALLOW_UPLOAD_ORDER" type="checkbox" value="1" <?php echo is_checked($ALLOW_UPLOAD_ORDER , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<span class="help-block">กรณีปิดจะไม่สามารถ Import Order ด้วยไฟล์ Excel ได้</span>
			<input type="hidden" name="ALLOW_UPLOAD_ORDER" id="allow-upload-order" value="<?php echo $ALLOW_UPLOAD_ORDER; ?>" />
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">การ Import WC ด้วยไฟล์ Excel</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="ALLOW_IMPORT_WC" type="checkbox" value="1" <?php echo is_checked($ALLOW_IMPORT_WC , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<span class="help-block">กรณีปิดจะไม่สามารถ Import WC ด้วยไฟล์ Excel ได้</span>
			<input type="hidden" name="ALLOW_IMPORT_WC" id="allow-import-wc" value="<?php echo $ALLOW_IMPORT_WC; ?>" />
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">การ Import WT ด้วยไฟล์ Excel</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="ALLOW_IMPORT_WT" type="checkbox" value="1" <?php echo is_checked($ALLOW_IMPORT_WT , '1'); ?> onchange="toggleOption($(this))"/>
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<span class="help-block">กรณีปิดจะไม่สามารถ Import WC ด้วยไฟล์ Excel ได้</span>
			<input type="hidden" name="ALLOW_IMPORT_WT" id="allow-import-wt" value="<?php echo $ALLOW_IMPORT_WT; ?>" />
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">ช่องทางขายเว็บไซต์</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<select class="form-control input-sm input-xlarge" name="WEB_SITE_CHANNELS_CODE" id="web-site-channels-code" >
				<?php echo select_channels($WEB_SITE_CHANNELS_CODE); ?>
			</select>
			<span class="help-block">เลือกรหัสสำหรับการขายบนเว็บไซต์(ใช้ในการ import order)</span>
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">คลังเว็บไซต์</span></div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			<select class="form-control input-sm input-xlarge" name="WEB_SITE_WAREHOUSE_CODE" >
				<option value="">ทั้งหมด</option>
				<?php echo select_sell_warehouse($WEB_SITE_WAREHOUSE_CODE); ?>
			</select>
			<span class="help-block">เลือกคลังสำหรับการขายบนเว็บไซต์(ใช้ในการ import order)</span>
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">รหัสลูกค้าเว็บไซต์ COD</span></div>
		<div class="col-lg-2 col-md-2 col-sm-2 padding-5 first">
			<input type="text" class="width-100 text-center" name="CUSTOMER_CODE_COD" id="cod-customer-code" value="<?php echo $CUSTOMER_CODE_COD; ?>" />
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 padding-5 last">
			<input type="text" class="width-100" id="cod-customer-name" value="<?php echo customer_name($CUSTOMER_CODE_COD); ?>" readonly />
		</div>
		<div class="col-lg-8 col-lg-offset-4 col-md-8 col-md-offset-4 col-sm-8 col-sm-offset-8">
			<span class="help-block">กำหนดรหัสลูกค้า สำหรับช่องทางการชำระเงินแบบ COD บนเว็บไซต์ (ใช้ในการ import order)</span>
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">รหัสลูกค้าเว็บไซต์ 2C2P</span></div>
		<div class="col-lg-2 col-md-2 col-sm-2 padding-5 first">
			<input type="text" class="width-100 text-center" name="CUSTOMER_CODE_2C2P" id="2c2p-customer-code" value="<?php echo $CUSTOMER_CODE_2C2P; ?>" />
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 padding-5 last">
			<input type="text" class="width-100" id="2c2p-customer-name" value="<?php echo customer_name($CUSTOMER_CODE_2C2P); ?>" readonly />
		</div>
		<div class="col-lg-8 col-lg-offset-4 col-md-8 col-md-offset-4 col-sm-8 col-sm-offset-8">
			<span class="help-block">กำหนดรหัสลูกค้า สำหรับช่องทางการชำระเงินแบบ 2C2P บนเว็บไซต์ (ใช้ในการ import order)</span>
		</div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">รหัสนำหน้าเลขที่จัดส่ง</span></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
      <input type="text" class="form-control input-sm input-small text-center" name="PREFIX_SHIPPING_NUMBER" value="<?php echo $PREFIX_SHIPPING_NUMBER; ?>" />
      <span class="help-block">รหัสนำหน้าเลขที่จัดส่ง โดยใช้เลขที่ออเดอร์ของ Warrix12 แล้วเติมรหัสนี้นำหน้าและบันทึกเป็นเลขที่จัดส่งทันที ใช้ในการ import ออเดอร์จากเว็บไซต์</span>
    </div>
		<div class="divider"></div>


		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">รหัสรายได้ค่าจัดส่ง</span></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
      <input type="text" class="form-control input-sm input-large text-center" name="SHIPPING_ITEM_CODE" value="<?php echo $SHIPPING_ITEM_CODE; ?>" />
      <span class="help-block">รหัสสินค้ารายได้ค่าจัดส่ง ที่จะเพิ่มเป็นรายการสินค้าให้ในออเดอร์ที่มีการคิดค่าจัดส่ง</span>
    </div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">น้ำหนักเหมารวม(กรัม)</span></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
      <input type="text" class="form-control input-sm input-small text-center" name="DHL_DEFAULT_WEIGHT" value="<?php echo $DHL_DEFAULT_WEIGHT; ?>" />
      <span class="help-block">น้ำหนักเหมารวมในการจัดส่ง 1 แพ็คเกจ(กรัม)</span>
    </div>
		<div class="divider"></div>

		<div class="col-lg-4 col-md-4 col-sm-4"><span class="form-control left-label">อัพโหลดออเดอร์(รายการ)/ครั้ง</span></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
      <input type="text" class="form-control input-sm input-small text-center" name="IMPORT_ROWS_LIMIT" value="<?php echo $IMPORT_ROWS_LIMIT; ?>" />
      <span class="help-block">จำกัดจำนวนรายการที่ออเดอร์ที่สามารถนำเข้าระบบได้ครั้งละไม่เกินรายการที่กำหนด เพื่อไม่ให้ระบบเกิดข้อผิดพลาด</span>
    </div>
		<div class="divider"></div>
		<div class="divider-hidden"></div>
		<div class="divider-hidden"></div>

    <div class="col-lg-8 col-lg-offset-4 col-md-8 col-md-offset-4 col-sm-8 col-sm-offset-8">
			<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
			<button type="button" class="btn btn-sm btn-success btn-100" onClick="updateConfig('orderForm')">SAVE</button>
			<?php endif; ?>
		</div>
		<div class="divider-hidden"></div>
  </div>
</form>
