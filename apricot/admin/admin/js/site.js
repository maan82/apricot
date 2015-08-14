// JavaScript Document
function loadMortgagesFrame(destinationID){
	document.getElementById('destinationID').innerHTML = '<iframe src="mortgages.php" height="950" width="490" frameborder="0" scrolling="no" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0"></iframe>';
}
function submitForm(formID){
	document.getElementById(formID).submit();
}
function popPrice(obj,destObjId){
	if(obj.value == '1'){
				document.getElementById(destObjId).innerHTML = '<select name="price" id="price_id"><option value="50000">Upto &pound;50000</option><option value="100000">Upto &pound;100000</option><option value="150000">Upto &pound;150000</option><option value="200000">Upto &pound;200000</option><option value="250000">Upto &pound;250000</option><option value="300000">Upto &pound;300000</option><option value="350000">Upto &pound;350000</option><option value="400000">Upto &pound;400000</option><option value="450000">Upto &pound;450000</option><option value="500000">Upto &pound;500000</option><option value="550000">Upto &pound;550000</option><option value="600000">Upto &pound;600000</option><option value="650000">Upto &pound;650000</option><option value="700000">Upto &pound;700000</option><option value="750000">Upto &pound;750000</option><option value="800000">Upto &pound;800000</option><option value="850000">Upto &pound;850000</option><option value="900000">Upto &pound;900000</option><option value="950000">Upto &pound;950000</option><option value="9999999999999">Any Price</option></select>'
	}
	else if(obj.value == '2'){
		document.getElementById(destObjId).innerHTML = '<select name="price" id="price_id"><option value="300">Upto &pound;300</option><option value="400">Upto &pound;400</option><option value="500">Upto &pound;500</option><option value="600">Upto &pound;600</option><option value="700">Upto &pound;700</option><option value="800">Upto &pound;800</option><option value="900">Upto &pound;900</option><option value="1000">Upto &pound;1000</option><option value="1100">Upto &pound;1100</option><option value="1200">Upto &pound;1200</option><option value="1300">Upto &pound;1300</option><option value="1400">Upto &pound;1400</option><option value="1500">Upto &pound;1500</option><option value="1600">Upto &pound;1600</option><option value="1700">Upto &pound;1700</option><option value="1800">Upto &pound;1800</option><option value="1900">Upto &pound;1900</option><option value="2000">Upto &pound;2000</option><option value="9999999999999">Any Price</option></select>';
	}
}
function showUrl(url,formname){
	if(formname){
		url = url+"?saddr="+document.getElementById('saddr').value+"&daddr="+document.getElementById('daddr').value;
		window.open(url, 'formgo','width=1000,height=600,toolbar=no,location=yes,directories=yes,status=yes,menubar=no,scrollbars=yes,copyhistory=yes,resizable=yes')
	} else {
		window.open(url, 'formgo', 'width=1000,height=600,toolbar=no,location=yes,directories=yes,status=yes,menubar=no,scrollbars=yes,copyhistory=yes,resizable=yes')
	}return false;
}
