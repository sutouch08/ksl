window.addEventListener('load', () => {
	defaultZoneInit();
	ixZoneInit();
	ixReturnZoneInit();
	lnwZoneInit();
	defaultCustomerInit();
	codCustomerInit();
	customer2c2pInit();
})

function toggleOption(el) {
	let name = el.data('name');
	let option = el.is(':checked') ? 1 : 0;
	$("input[name='"+name+"']").val(option);
	console.log(name+' : ' + $("input[name='"+name+"']").val());
}


function defaultZoneInit() {
	let whs_code = $('#default-warehouse').val();

	$('#default-zone').autocomplete({
		source: BASE_URL + 'auto_complete/get_zone_code_and_name/'+ whs_code,
		autoFocus:true,
		close:function(){
			let arr = $(this).val().split(' | ');

			if(arr.length == 2) {
				$(this).val(arr[0]);
			}
			else {
				$(this).val('');
			}
		}
	})
}


function ixZoneInit() {
	let whs_code = $('#ix-warehouse').val();

	$('#ix-zone').autocomplete({
		source: BASE_URL + 'auto_complete/get_zone_code_and_name/'+ whs_code,
		autoFocus:true,
		close:function(){
			let arr = $(this).val().split(' | ');

			if(arr.length == 2) {
				$(this).val(arr[0]);
			}
			else {
				$(this).val('');
			}
		}
	})
}


function ixReturnZoneInit() {
	let whs_code = $('#ix-return-warehouse').val();

	$('#ix-return-zone').autocomplete({
		source: BASE_URL + 'auto_complete/get_zone_code_and_name/'+ whs_code,
		autoFocus:true,
		close:function(){
			let arr = $(this).val().split(' | ');

			if(arr.length == 2) {
				$(this).val(arr[0]);
			}
			else {
				$(this).val('');
			}
		}
	})
}


function lnwZoneInit() {
	let whs_code = $('#lnw-warehouse').val();

	$('#lnw-zone').autocomplete({
		source: BASE_URL + 'auto_complete/get_zone_code_and_name/'+ whs_code,
		autoFocus:true,
		close:function(){
			let arr = $(this).val().split(' | ');

			if(arr.length == 2) {
				$(this).val(arr[0]);
			}
			else {
				$(this).val('');
			}
		}
	})
}


function defaultCustomerInit() {
	$('#default-customer-code').autocomplete({
		source: BASE_URL + 'auto_complete/get_customer_code_and_name',
		autoFocus:true,
		close:function() {
			let arr = $(this).val().split(' | ');

			if(arr.length == 2) {
				$('#default-customer-code').val(arr[0]);
				$('#default-customer-name').val(arr[1]);
			}
			else {
				$('#default-customer-code').val('');
				$('#default-customer-name').val('');
			}
		}
	})
}


function codCustomerInit() {
	$('#cod-customer-code').autocomplete({
		source: BASE_URL + 'auto_complete/get_customer_code_and_name',
		autoFocus:true,
		close:function() {
			let arr = $(this).val().split(' | ');

			if(arr.length == 2) {
				$('#cod-customer-code').val(arr[0]);
				$('#cod-customer-name').val(arr[1]);
			}
			else {
				$('#cod-customer-code').val('');
				$('#cod-customer-name').val('');
			}
		}
	})
}


function customer2c2pInit() {
	$('#2c2p-customer-code').autocomplete({
		source: BASE_URL + 'auto_complete/get_customer_code_and_name',
		autoFocus:true,
		close:function() {
			let arr = $(this).val().split(' | ');

			if(arr.length == 2) {
				$('#2c2p-customer-code').val(arr[0]);
				$('#2c2p-customer-name').val(arr[1]);
			}
			else {
				$('#2c2p-customer-code').val('');
				$('#2c2p-customer-name').val('');
			}
		}
	})
}

$('#default-warehouse').select2();
$('#transform-warehouse').select2();
$('#lend-warehouse').select2();
$('#ix-warehouse').select2();
$('#ix-return-warehouse').select2();
$('#lnw-shop-warehouse').select2();

function updateConfig(formName) {
	load_in();
	var formData = $("#"+formName).serialize();
	$.ajax({
		url: BASE_URL + "setting/configs/update_config",
		type:"POST",
    cache:"false",
    data: formData,
		success: function(rs) {
			load_out();

      rs = $.trim(rs);

      if(rs == 'success'){
        swal({
          title:'Updated',
          type:'success',
          timer:1000
        });
      }
			else {
        showError(rs);
      }
		},
		error:function(rs) {
			showError();
		}
	});
}


function changeURL(tab) {
	var url = BASE_URL + 'setting/configs/index/'+ tab;
	var stObj = { stage: 'stage' };
	window.history.pushState(stObj, 'configs', url);
}


function toggleSystem(option) {
	$('#closed').val(option);
	$('#btn-open').removeClass('btn-success');
	$('#btn-close').removeClass('btn-danger');
	$('#btn-freze').removeClass('btn-warning');

	if(option == 0) {
		$("#btn-open").addClass('btn-success');
	}

	if(option == 1) {
		$("#btn-close").addClass('btn-danger');
	}

	if(option == 2) {
		$('#btn-freze').addClass('btn-warning');
	}
}






//--- เปิด/ปิด การ sync ข้อมูลระหว่างเว็บไซต์กับระบบหลัก
function toggleWebApi(option){
	$('#web-api').val(option);
	if(option == 1){
		$('#btn-web-api-on').addClass('btn-primary');
		$('#btn-web-api-off').removeClass('btn-primary');
		return;
	}else if(option == 0){
		$('#btn-web-api-on').removeClass('btn-primary');
		$('#btn-web-api-off').addClass('btn-primary');
		return;
	}
}

//--- เปิด/ปิด การ sync ข้อมูลระหว่างเว็บไซต์กับระบบหลัก
function togglePosApi(option){
	$('#pos-api').val(option);
	if(option == 1){
		$('#btn-pos-api-on').addClass('btn-primary');
		$('#btn-pos-api-off').removeClass('btn-primary');
		return;
	}else if(option == 0){
		$('#btn-pos-api-on').removeClass('btn-primary');
		$('#btn-pos-api-off').addClass('btn-primary');
		return;
	}
}


//--- เปิด/ปิด การ sync ข้อมูลระหว่างเว็บไซต์กับระบบหลัก
function togglePosApiWW(option){
	$('#pos-api-ww').val(option);
	if(option == 1){
		$('#btn-pos-api-ww-on').addClass('btn-primary');
		$('#btn-pos-api-ww-off').removeClass('btn-primary');
		return;
	}else if(option == 0){
		$('#btn-pos-api-ww-on').removeClass('btn-primary');
		$('#btn-pos-api-ww-off').addClass('btn-primary');
		return;
	}
}


//---- ไม่ขายสินค้าให้ลูกค้าที่มียอดค้างเกินกำหนด
function toggleStrictDue(option)
{
	$('#strict-over-due').val(option);
	if(option == 1){
		$('#btn-strict-yes').addClass('btn-success');
		$('#btn-strict-no').removeClass('btn-danger');
		return;
	}
	if(option == 0){
		$('#btn-strict-yes').removeClass('btn-success');
		$('#btn-strict-no').addClass('btn-danger');
		return;
	}
}



//---- ไม่ขายสินค้าให้ลูกค้าที่มียอดค้างเกินกำหนด
function toggleAuz(option)
{
	$('#allow-under-zero').val(option);
	if(option == 1){
		$('#btn-auz-yes').addClass('btn-danger');
		$('#btn-auz-no').removeClass('btn-success');
		return;
	}
	if(option == 0){
		$('#btn-auz-yes').removeClass('btn-danger');
		$('#btn-auz-no').addClass('btn-success');
		return;
	}
}


//---- ไม่ขายสินค้าให้ลูกค้าที่มียอดค้างเกินกำหนด
function toggleOverPo(option)
{
	$('#allow-receive-over-po').val(option);
	if(option == 1){
		$('#btn-ovpo-yes').addClass('btn-success');
		$('#btn-ovpo-no').removeClass('btn-primary');
		return;
	}
	if(option == 0){
		$('#btn-ovpo-yes').removeClass('btn-success');
		$('#btn-ovpo-no').addClass('btn-primary');
		return;
	}
}



function toggleRequest(option)
{
	$('#strict-receive-po').val(option);
	if(option == 1){
		$('#btn-request-yes').addClass('btn-success');
		$('#btn-request-no').removeClass('btn-primary');
		return;
	}
	if(option == 0){
		$('#btn-request-yes').removeClass('btn-success');
		$('#btn-request-no').addClass('btn-primary');
		return;
	}
}


function toggleTransfer(option)
{
	$('#strict-transfer').val(option);

	if(option == 1){
		$('#btn-transfer-yes').addClass('btn-success');
		$('#btn-transfer-no').removeClass('btn-primary');
		return;
	}
	if(option == 0){
		$('#btn-transfer-yes').removeClass('btn-success');
		$('#btn-transfer-no').addClass('btn-primary');
		return;
	}
}


function toggleTransferImport(option)
{
	$('#transfer-imp').val(option);

	if(option == 1){
		$('#btn-imp-yes').addClass('btn-success');
		$('#btn-imp-no').removeClass('btn-primary');
		return;
	}
	if(option == 0){
		$('#btn-imp-yes').removeClass('btn-success');
		$('#btn-imp-no').addClass('btn-primary');
		return;
	}
}


function toggleControlCredit(option)
{
	$('#control-credit').val(option);
	if(option == 1){
		$('#btn-credit-yes').addClass('btn-success');
		$('#btn-credit-no').removeClass('btn-danger');
		return;
	}
	if(option == 0){
		$('#btn-credit-yes').removeClass('btn-success');
		$('#btn-credit-no').addClass('btn-danger');
		return;
	}
}

function toggleImportOrder(option){
	$('#allow-upload-order').val(option);

	if(option == 1){
		$('#btn-import-order-yes').addClass('btn-success');
		$('#btn-import-order-no').removeClass('btn-primary');
		return;
	}

	if(option == 0){
		$('#btn-import-order-yes').removeClass('btn-success');
		$('#btn-import-order-no').addClass('btn-primary');
		return;
	}
}


function toggleImportWC(option){
	$('#allow-import-wc').val(option);

	if(option == 1){
		$('#btn-import-wc-yes').addClass('btn-success');
		$('#btn-import-wc-no').removeClass('btn-primary');
		return;
	}

	if(option == 0){
		$('#btn-import-wc-yes').removeClass('btn-success');
		$('#btn-import-wc-no').addClass('btn-primary');
		return;
	}
}


function toggleImportWT(option){
	$('#allow-import-wt').val(option);

	if(option == 1){
		$('#btn-import-wt-yes').addClass('btn-success');
		$('#btn-import-wt-no').removeClass('btn-primary');
		return;
	}

	if(option == 0){
		$('#btn-import-wt-yes').removeClass('btn-success');
		$('#btn-import-wt-no').addClass('btn-primary');
		return;
	}
}


function toggleImportSM(option){
	$('#allow-import-sm').val(option);

	if(option == 1){
		$('#btn-import-sm-yes').addClass('btn-success');
		$('#btn-import-sm-no').removeClass('btn-primary');
		return;
	}

	if(option == 0){
		$('#btn-import-sm-yes').removeClass('btn-success');
		$('#btn-import-sm-no').addClass('btn-primary');
		return;
	}
}


function toggleShowStock(option)
{
	$('#show-sum-stock').val(option);
	if(option == 1){
		$('#btn-show-stock-yes').addClass('btn-success');
		$('#btn-show-stock-no').removeClass('btn-primary');
		return;
	}
	if(option == 0){
		$('#btn-show-stock-yes').removeClass('btn-success');
		$('#btn-show-stock-no').addClass('btn-primary');
		return;
	}
}



function toggleReceiveDue(option)
{
	$('#receive-over-due').val(option);
	if(option == 1){
		$('#btn-receive-yes').addClass('btn-success');
		$('#btn-receive-no').removeClass('btn-danger');
		return;
	}
	if(option == 0){
		$('#btn-receive-yes').removeClass('btn-success');
		$('#btn-receive-no').addClass('btn-danger');
		return;
	}
}



function toggleEditDiscount(option)
{
	$('#allow-edit-discount').val(option);
	if(option == 1){
		$('#btn-disc-yes').addClass('btn-success');
		$('#btn-disc-no').removeClass('btn-danger');
		return;
	}

	if(option == 0){
		$('#btn-disc-yes').removeClass('btn-success');
		$('#btn-disc-no').addClass('btn-danger');
		return;
	}
}


function toggleEditPrice(option){
	$('#allow-edit-price').val(option);

	if(option == 1){
		$('#btn-price-yes').addClass('btn-success');
		$('#btn-price-no').removeClass('btn-danger');
		return;
	}

	if(option == 0){
		$('#btn-price-yes').removeClass('btn-success');
		$('#btn-price-no').addClass('btn-danger');
		return;
	}
}


function toggleEditCost(option){
	$('#allow-edit-cost').val(option);

	if(option == 1){
		$('#btn-cost-yes').addClass('btn-success');
		$('#btn-cost-no').removeClass('btn-danger');
		return;
	}

	if(option == 0){
		$('#btn-cost-yes').removeClass('btn-success');
		$('#btn-cost-no').addClass('btn-danger');
		return;
	}
}



function toggleAutoClose(option){
	$('#po-auto-close').val(option);

	if(option == 1){
		$('#btn-po-yes').addClass('btn-success');
		$('#btn-po-no').removeClass('btn-danger');
		return;
	}

	if(option == 0){
		$('#btn-po-yes').removeClass('btn-success');
		$('#btn-po-no').addClass('btn-danger');
		return;
	}
}

//--- เปิด/ปิด WMS API
function toggleWmsApi(option){
	$('#wms-api').val(option);
	if(option == 1){
		$('#btn-api-on').addClass('btn-success');
		$('#btn-api-off').removeClass('btn-primary');
		return;
	}else if(option == 0){
		$('#btn-api-on').removeClass('btn-success');
		$('#btn-api-off').addClass('btn-primary');
		return;
	}
}


function toggleFullMode(option) {
	$('#wms-full-mode').val(option);

	if(option == 1) {
		$('#btn-full-off').removeClass('btn-primary');
		$('#btn-full-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-full-on').removeClass('btn-success');
		$('#btn-full-off').addClass('btn-primary');
		return;
	}
}


function toggleExportItem(option) {
	$('#wms-export-item').val(option);

	if(option == 1) {
		$('#btn-item-off').removeClass('btn-primary');
		$('#btn-item-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-item-on').removeClass('btn-success');
		$('#btn-item-off').addClass('btn-primary');
		return;
	}
}


function toggleLogXml(option) {

	$('#log-xml').val(option);

	if(option == 1) {
		$('#btn-xml-off').removeClass('btn-primary');
		$('#btn-xml-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-xml-on').removeClass('btn-success');
		$('#btn-xml-off').addClass('btn-primary');
		return;
	}
}

function toggleTestMode(option) {
	$('#wms-test').val(option);

	if(option == 1) {
		$('#btn-test-off').removeClass('btn-primary');
		$('#btn-test-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-test-on').removeClass('btn-success');
		$('#btn-test-off').addClass('btn-primary');
		return;
	}
}


function toggleFastExport(option) {

	$('#wms-instant-export').val(option);

	if(option == 1) {
		$('#btn-ex-off').removeClass('btn-danger');
		$('#btn-ex-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-ex-on').removeClass('btn-success');
		$('#btn-ex-off').addClass('btn-danger');
		return;
	}
}


function checkCompanySetting(){
	vat = parseFloat($('#VAT').val());
	year = parseInt($('#startYear').val());

	if(isNaN(year)){
		swal('ปีที่เริ่มต้นกิจการไม่ถูกต้อง');
		return false;
	}

	if(year < 1970){
		swal('ปีที่เริ่มต้นกิจการไม่ถูกต้อง');
		return false;
	}

	if(year > 2100){
		year = year - 543;
		$('#startYear').val(year);
	}


	updateConfig('companyForm');
}

$('#default-warehouse').autocomplete({
	source: BASE_URL + 'auto_complete/get_warehouse_by_role/1',
	autoFocus:true,
	close:function(){
		let rs = $(this).val();
		let arr = rs.split(' | ');

		if(arr[0] === 'not found'){
			$(this).val('');
		}else{
			$(this).val(arr[0]);
		}
	}
})





$('#lend-warehouse').autocomplete({
	source: BASE_URL + 'auto_complete/get_warehouse_by_role/8',
	autoFocus:true,
	close:function(){
		let rs = $(this).val();
		let arr = rs.split(' | ');

		if(arr[0] === 'not found'){
			$(this).val('');
		}else{
			$(this).val(arr[0]);
		}
	}
})


$('#transform-warehouse').autocomplete({
	source: BASE_URL + 'auto_complete/get_warehouse_by_role/7',
	autoFocus:true,
	close:function(){
		let rs = $(this).val();
		let arr = rs.split(' | ');

		if(arr[0] === 'not found'){
			$(this).val('');
		}else{
			$(this).val(arr[0]);
		}
	}
})


$('#wms-warehouse').autocomplete({
	source: BASE_URL + 'auto_complete/get_warehouse_by_role/1',
	autoFocus:true,
	close:function(){
		let rs = $(this).val();
		let arr = rs.split(' | ');

		if(arr[0] === 'not found'){
			$(this).val('');
		}else{
			$(this).val(arr[0]);
			set_wms_warehouse(arr[0]);
		}
	}
})


$(document).ready(function(){
	wms_warehouse = $('#wms-warehouse').val();
	sokojung_warehouse = $('#sokojung-warehouse').val();
	set_wms_warehouse(wms_warehouse);
	set_sokojung_warehouse(sokojung_warehouse)
});


function set_wms_warehouse(wms_wh_code) {
	$('#wms-zone').autocomplete({
		source: BASE_URL + 'auto_complete/get_zone_code_and_name/'+ wms_wh_code,
		autoFocus:true,
		close:function() {
			let rs = $(this).val();
			let arr = rs.split(' | ');

			if(arr[0] === 'ไม่พบรายการ') {
				$(this).val('');
			}
			else {
				$(this).val(arr[0]);
			}
		}
	})
}

//=====================================  CHATBOT ==============================================//
//--- เปิด/ปิด การ sync ข้อมูลระหว่างเว็บไซต์กับระบบหลัก
function toggleChatbotApi(option){
	$('#chatbot-api').val(option);

	if(option == 1){
		$('#btn-chatbot-api-on').addClass('btn-success');
		$('#btn-chatbot-api-off').removeClass('btn-danger');
		return;

	}else if(option == 0){
		$('#btn-chatbot-api-on').removeClass('btn-success');
		$('#btn-chatbot-api-off').addClass('btn-danger');
		return;
	}
}


function toggleSyncStock(option) {

	$('#sync-chatbot-stock').val(option);

	if(option == 1) {
		$('#btn-stock-off').removeClass('btn-danger');
		$('#btn-stock-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-stock-on').removeClass('btn-success');
		$('#btn-stock-off').addClass('btn-danger');
		return;
	}
}



function toggleLimitWC(option) {
	$('#limit-consignment').val(option);

	if(option == 1) {
		$('#btn-wc-no').removeClass('btn-danger');
		$('#btn-wc-yes').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-wc-yes').removeClass('btn-success');
		$('#btn-wc-no').addClass('btn-danger');
		return;
	}
}


function toggleLimitWT(option) {
	$('#limit-consign').val(option);

	if(option == 1) {
		$('#btn-wt-no').removeClass('btn-danger');
		$('#btn-wt-yes').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-wt-yes').removeClass('btn-success');
		$('#btn-wt-no').addClass('btn-danger');
		return;
	}
}


function toggleTransferEOM(option) {
	$('#transfer-eom').val(option);

	if(option == 1) {
		$('#btn-eom-yes').addClass('btn-success');
		$('#btn-eom-no').removeClass('btn-primary');
		return;
	}

	if(option == 0) {
		$('#btn-eom-yes').removeClass('btn-success');
		$('#btn-eom-no').addClass('btn-primary');
		return;
	}
}



function toggleLogJson(option) {

	$('#chatbot-log-json').val(option);

	if(option == 1) {
		$('#btn-log-off').removeClass('btn-danger');
		$('#btn-log-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-log-on').removeClass('btn-success');
		$('#btn-log-off').addClass('btn-danger');
		return;
	}
}



function toggleSysBin(option) {
	$('#system-bin-location').val(option);

	if(option == 1) {
		$('#btn-sys-bin-yes').addClass('btn-success');
		$('#btn-sys-bin-no').removeClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-sys-bin-no').addClass('btn-success');
		$('#btn-sys-bin-yes').removeClass('btn-success');
		return;
	}
}



$('#chatbot-warehouse').autocomplete({
	source: BASE_URL + 'auto_complete/get_warehouse_by_role/1',
	autoFocus:true,
	close:function(){
		let rs = $(this).val();
		let arr = rs.split(' | ');

		if(arr[0] === 'not found'){
			$(this).val('');
		}else{
			$(this).val(arr[0]);
		}
	}
})

$('#web-tracking-date').datepicker({
	dateFormat:'yy-mm-dd'
})


//================================================== SOKOJUNG ==========================//

//--- เปิด/ปิด SOKOJUNG API
function toggleSokojungApi(option){
	$('#sokojung-api').val(option);
	if(option == 1){
		$('#btn-sokojung-api-on').addClass('btn-success');
		$('#btn-sokojung-api-off').removeClass('btn-primary');
		return;
	}else if(option == 0){
		$('#btn-sokojung-api-on').removeClass('btn-success');
		$('#btn-sokojung-api-off').addClass('btn-primary');
		return;
	}
}

$('#sokojung-warehouse').autocomplete({
	source: BASE_URL + 'auto_complete/get_warehouse_by_role/1',
	autoFocus:true,
	close:function(){
		let rs = $(this).val();
		let arr = rs.split(' | ');

		if(arr[0] === 'not found'){
			$(this).val('');
		}else{
			$(this).val(arr[0]);
			set_sokojung_warehouse(arr[0]);
		}
	}
})


function set_sokojung_warehouse(wh_code) {
	$('#sokojung-zone').autocomplete({
		source: BASE_URL + 'auto_complete/get_zone_code_and_name/'+ wh_code,
		autoFocus:true,
		close:function() {
			let rs = $(this).val();
			let arr = rs.split(' | ');

			if(arr[0] === 'ไม่พบรายการ') {
				$(this).val('');
			}
			else {
				$(this).val(arr[0]);
			}
		}
	})
}


function toggleSokojungSyncStock(option) {

	$('#sync-sokojung-stock').val(option);

	if(option == 1) {
		$('#btn-soko-stock-off').removeClass('btn-danger');
		$('#btn-soko-stock-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-soko-stock-on').removeClass('btn-success');
		$('#btn-soko-stock-off').addClass('btn-danger');
		return;
	}
}


function toggleSokojungLogJson(option) {

	$('#sokojung-log-json').val(option);

	if(option == 1) {
		$('#btn-soko-log-off').removeClass('btn-danger');
		$('#btn-soko-log-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-soko-log-on').removeClass('btn-success');
		$('#btn-soko-log-off').addClass('btn-danger');
		return;
	}
}

function toggleSokojungTest(option) {
	$('#sokojung-test').val(option);

	if(option == 1) {
		$('#btn-soko-test-off').removeClass('btn-primary');
		$('#btn-soko-test-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-soko-test-on').removeClass('btn-success');
		$('#btn-soko-test-off').addClass('btn-primary');
		return;
	}
}


//================================================== IX API ==========================//

//--- เปิด/ปิด IX API
function toggleIxApi(option){
	$('#ix-api').val(option);
	if(option == 1){
		$('#btn-ix-api-on').addClass('btn-success');
		$('#btn-ix-api-off').removeClass('btn-primary');
		return;
	}else if(option == 0){
		$('#btn-ix-api-on').removeClass('btn-success');
		$('#btn-ix-api-off').addClass('btn-primary');
		return;
	}
}

$('#ix-warehouse').autocomplete({
	source: BASE_URL + 'auto_complete/get_warehouse_by_role/1',
	autoFocus:true,
	close:function(){
		let rs = $(this).val();
		let arr = rs.split(' | ');

		if(arr[0] === 'not found'){
			$(this).val('');
		}else{
			$(this).val(arr[0]);
			set_ix_warehouse(arr[0]);
		}
	}
})


function set_ix_warehouse(wh_code) {
	$('#ix-zone').autocomplete({
		source: BASE_URL + 'auto_complete/get_zone_code_and_name/'+ wh_code,
		autoFocus:true,
		close:function() {
			let rs = $(this).val();
			let arr = rs.split(' | ');

			if(arr[0] === 'ไม่พบรายการ') {
				$(this).val('');
			}
			else {
				$(this).val(arr[0]);
			}
		}
	})
}


$('#ix-return-warehouse').autocomplete({
	source: BASE_URL + 'auto_complete/get_warehouse_by_role/1',
	autoFocus:true,
	close:function(){
		let rs = $(this).val();
		let arr = rs.split(' | ');

		if(arr[0] === 'not found'){
			$(this).val('');
		}else{
			$(this).val(arr[0]);
			set_ix_return_warehouse(arr[0]);
		}
	}
})


function set_ix_return_warehouse(wh_code) {
	$('#ix-return-zone').autocomplete({
		source: BASE_URL + 'auto_complete/get_zone_code_and_name/'+ wh_code,
		autoFocus:true,
		close:function() {
			let rs = $(this).val();
			let arr = rs.split(' | ');

			if(arr[0] === 'ไม่พบรายการ') {
				$(this).val('');
			}
			else {
				$(this).val(arr[0]);
			}
		}
	})
}


function toggleIxSyncStock(option) {

	$('#sync-ix-stock').val(option);

	if(option == 1) {
		$('#btn-ix-stock-off').removeClass('btn-danger');
		$('#btn-ix-stock-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-ix-stock-on').removeClass('btn-success');
		$('#btn-ix-stock-off').addClass('btn-danger');
		return;
	}
}


function toggleIxLogJson(option) {

	$('#ix-log-json').val(option);

	if(option == 1) {
		$('#btn-ix-log-off').removeClass('btn-danger');
		$('#btn-ix-log-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-ix-log-on').removeClass('btn-success');
		$('#btn-ix-log-off').addClass('btn-danger');
		return;
	}
}

function toggleIxTest(option) {
	$('#ix-test').val(option);

	if(option == 1) {
		$('#btn-ix-test-off').removeClass('btn-primary');
		$('#btn-ix-test-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-ix-test-on').removeClass('btn-success');
		$('#btn-ix-test-off').addClass('btn-primary');
		return;
	}
}

//================================================== WRX API ==========================//

//--- เปิด/ปิด SOKOJUNG API
function toggleWrxApi(option){
	$('#wrx-api').val(option);
	if(option == 1){
		$('#btn-wrx-api-on').addClass('btn-success');
		$('#btn-wrx-api-off').removeClass('btn-primary');
		return;
	}
	else if(option == 0){
		$('#btn-wrx-api-on').removeClass('btn-success');
		$('#btn-wrx-api-off').addClass('btn-primary');
		return;
	}
}

function toggleWrxLogJson(option) {

	$('#wrx-log-json').val(option);

	if(option == 1) {
		$('#btn-wrx-log-off').removeClass('btn-primary');
		$('#btn-wrx-log-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-wrx-log-on').removeClass('btn-success');
		$('#btn-wrx-log-off').addClass('btn-primary');
		return;
	}
}

function toggleWrxTest(option) {
	$('#wrx-api-test').val(option);

	if(option == 1) {
		$('#btn-wrx-test-off').removeClass('btn-primary');
		$('#btn-wrx-test-on').addClass('btn-success');
		return;
	}

	if(option == 0) {
		$('#btn-wrx-test-on').removeClass('btn-success');
		$('#btn-wrx-test-off').addClass('btn-primary');
		return;
	}
}
