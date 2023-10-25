function doHandleAll()
{
	with (document.frmMain) {
		if(elements['allCheck'].checked == false){
			doUnCheckAll();
		}
		else if(elements['allCheck'].checked == true){
			doCheckAll();
		}
	}
}

function doCheckAll() {
	with (document.frmMain) {
		for (var i=0; i < elements.length; i++) {
			if (elements[i].type == 'checkbox') {
				elements[i].checked = true;
			}
		}
	}
}

function doUnCheckAll()
{
	with (document.frmMain) {
		for (var i=0; i < elements.length; i++) {
			if (elements[i].type == 'checkbox') {
				elements[i].checked = false;
			}
		}
	}
}

/**
 * If at least one day is unchecked, main check box would be unchecked
 */

function unCheckMain() {
	noOfCheckboxes = 0;
	noOfCheckedCheckboxes = 0;

	with (document.getElementById('frmMain')) {
		for (i = 0; i < elements.length; i++) {
			if (elements[i].type == 'checkbox' && elements[i].name != 'allCheck') {
				noOfCheckboxes++;
				if (elements[i].checked == true) {
					noOfCheckedCheckboxes++;
				}

			}
		}
	}

	document.getElementById('allCheck').checked = (noOfCheckboxes == noOfCheckedCheckboxes);
}

function atLeastOneIsChecked () {
	with (document.getElementById('frmMain')) {
		for (var i=0; i < elements.length; i++) {
			if (elements[i].type == 'checkbox') {
				if (elements[i].checked == true) {
					return true;
				}
			}
		}
		return false;
	}
}