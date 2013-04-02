function Reports_AddDiagnosis(disorder_id, name) {
	$('#Reports_diagnoses').append('<tr><td>'+name+'</td><td><input type="checkbox" class="principalCheckbox" name="principal[]" value="'+disorder_id+'" /></td><td><a href="#" class="small removeDiagnosis" rel="'+disorder_id+'"><strong>Remove</strong></a></td></tr>');
	$('#selected_diagnoses').append('<input type="hidden" name="secondary[]" value="'+disorder_id+'" />');
}

function Reports_AddProcedure(procedure_id, name) {
	$('#Reports_procedures').append('<tr><td>'+name+'</td><td><a href="#" class="small removeProcedure" rel="'+procedure_id+'"><strong>Remove</strong></a></td></tr>');
	$('#selected_procedures').append('<input type="hidden" name="procedure[]" value="'+procedure_id+'" />');
}

function selectSort(a, b) {
	if (a.innerHTML == rootItem) {
		return -1;
	}
	else if (b.innerHTML == rootItem) {
		return 1;
	}
	return (a.innerHTML > b.innerHTML) ? 1 : -1;
};

var rootItem = null;

function sort_selectbox(element) {
	rootItem = element.children('option:first').text();
	element.append(element.children('option').sort(selectSort));
}

$(document).ready(function() {
	$('input.principalCheckbox').die('click').live('click',function() {
		var v = $(this).val();
		var checked = $(this).is(':checked');

		$('#selected_diagnoses').children('input').map(function() {
			if ($(this).val() == v) {
				if (checked) {
					$(this).attr('disabled','disabled');
				} else {
					$(this).removeAttr('disabled');
				}
			}
		});
	});

	$('a.removeDiagnosis').die('click').live('click',function() {
		var disorder_id = $(this).attr('rel');

		$('#selected_diagnoses').children('input').map(function() {
			if ($(this).val() == disorder_id) {
				$(this).remove();
			}
		});

		$(this).parent().parent().remove();

		$.ajax({
			'type': 'GET',
			'url': baseUrl+'/disorder/iscommonophthalmic/'+disorder_id,
			'success': function(html) {
				if (html.length >0) {
					$('#DiagnosisSelection_disorder_id').append(html);
					sort_selectbox($('#DiagnosisSelection_disorder_id'));
				}
			}
		});

		return false;
	});

	$('a.removeProcedure').die('click').live('click',function() {
		var procedure_id = $(this).attr('rel');

		$('#selected_procedures').children('input').map(function() {
			if ($(this).val() == procedure_id) {
				$(this).remove();
			}
		});

		$(this).parent().parent().remove();

		$.ajax({
			'type': 'GET',
			'url': baseUrl+'/procedure/iscommonophthalmic/'+procedure_id,
			'success': function(html) {
				if (html.length >0) {
					$('#ProcedureSelection_procedure_id').append(html);
					sort_selectbox($('#ProcedureSelection_procedure_id'));
				}
			}
		});

		return false;
	});

	$('div.multiStringAdd button').click(function() {
		var container = $(this).parent('div').prev('div');
		container.append('<div class="stringInput"><input type="text" name="phrases[]" id="phrases" /> <a class="removeString" href="#">Remove</a></div>');
	});

	$('a.removeString').die('click').live('click',function() {
		$(this).parent('div').remove();
		return false;
	});
});
