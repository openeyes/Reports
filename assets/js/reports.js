function Reports_AddDiagnosis(disorder_id, name) {
	$('#Reports_diagnoses').append('<tr><td>'+name+'</td><td><input type="checkbox" name="principal[]" value="'+disorder_id+'" /></td><td><a href="#" class="small removeDiagnosis" rel="'+disorder_id+'"><strong>Remove</strong></a></td></tr>');
	$('#selected_diagnoses').append('<input type="hidden" name="selected_diagnoses[]" value="'+disorder_id+'" />');
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
});
