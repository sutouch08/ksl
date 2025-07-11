
<?php
$open     = $CLOSE_SYSTEM == 0 ? 'btn-success' : '';
$close    = $CLOSE_SYSTEM == 1 ? 'btn-danger' : '';
$freze    = $CLOSE_SYSTEM == 2 ? 'btn-warning' : '';
?>
<div class="nav-title">
	<span class="back-link" onclick="closeTab()"><i class="fa fa-angle-left fa-lg"></i> ย้อนกลับ</span>
	<span class="font-size-14 text-left">General setting</span>
</div>

  <form id="systemForm" class="margin-top-30">
    <input type="hidden" name="CLOSE_SYSTEM" id="closed" value="<?php echo $CLOSE_SYSTEM; ?>" />
    <input type="hidden" name="IS_UAT" id="is-uat" value="<?php echo $IS_UAT; ?>" />
    <div class="row">
  	<?php if( $cando === TRUE ): //---- ถ้ามีสิทธิ์ปิดระบบ ---//	?>
    	<div class="col-xs-5 padding-top-5">เปิด/ปิด ระบบ</div>
      <div class="col-xs-7">
      	<div class="btn-group width-100">
        	<button type="button" class="btn btn-xs <?php echo $open; ?>" style="width:30%;" id="btn-open" onClick="openSystem()">เปิด</button>
          <button type="button" class="btn btn-xs <?php echo $close; ?>" style="width:30%;" id="btn-close" onClick="closeSystem()">ปิด</button>
          <button type="button" class="btn btn-xs <?php echo $freze; ?>" style="width:40%" id="btn-freze" onclick="frezeSystem()">ดูอย่างเดียว</button>
        </div>
      </div>
      <div class="col-xs-12 padding-top-5">
        <span class="help-block">กรณีปิดระบบจะไม่สามารถเข้าใช้งานระบบได้ในทุกส่วน โปรดใช้ความระมัดระวังในการกำหนดค่านี้</span>
      </div>
      <div class="divider"></div>

      <div class="col-xs-8 padding-top-5">UAT Environment</div>
      <div class="col-xs-4 text-right">
        <label style="padding-top:5px; margin-bottom:0px;">
  				<input class="ace ace-switch ace-switch-7" data-name="IS_UAT" type="checkbox" value="1" <?php echo is_checked($IS_UAT , '1'); ?> onchange="toggleOption($(this))"/>
  				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
  			</label>
      </div>
      <div class="divider"></div>
    <?php endif; ?>

		<div class="col-xs-8"><span class="form-control left-label">วันที่ในการบันทึกขายตัดสต็อก</span></div>
    <div class="col-xs-4">
      <div class="btn-group input-medium">
        <select class="form-control input-sm input-medium" name="ORDER_SOLD_DATE">
					<option value="B" <?php echo is_selected("B", $ORDER_SOLD_DATE); ?>>วันที่เปิดบิล</option>
					<option value="D" <?php echo is_selected("D", $ORDER_SOLD_DATE); ?>>วันที่เอกสาร</option>
				</select>
      </div>
      <span class="help-block">กำหนดประเภทวันที่ที่ใช้ในการบันทึกขายและตัดสต็อกในระบบ SAP</span>
    </div>
    <div class="divider-hidden"></div>

      <div class="col-xs-9 col-sm-offset-3">
        <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success" onClick="updateConfig('systemForm')"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/row-->
  </form>
