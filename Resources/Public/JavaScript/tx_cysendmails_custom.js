CKEDITOR.replace('message', {
	language: 'de',
	toolbarGroups: [
		{ name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
		{ name: 'colors', groups: ['colors'] },
		{ name: 'links', groups: ['links'] },
		{ name: 'insert', groups: ['insert'] },
		{ name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'paragraph'] },
		{ name: 'clipboard', groups: ['clipboard', 'undo'] },
		{ name: 'tools', groups: ['tools'] },
		{ name: 'styles', groups: ['styles'] },
		{ name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
		{ name: 'document', groups: ['mode', 'document', 'doctools'] },
		{ name: 'others', groups: ['others'] }
	],
	removeButtons: 'Save,NewPage,Preview,Print,Templates,Form,Radio,Textarea,Select,Button,ImageButton,HiddenField,TextField,CreateDiv,BidiLtr,BidiRtl,Language,Find,Replace,SelectAll,Checkbox,Image,Table,PageBreak,Iframe,ShowBlocks,About,Anchor,Smiley',
	extraAllowedContent: 's',


});


$('#receivers').inputosaurus({
	width: '100%',
	autoCompleteSource: receivers,
	activateFinalResult: true,
	allowDuplicates: false,
	inputDelimiters: [','],
	classHook: function(val) {
		let value = $.trim(val)
		return value.startsWith('#') ? 'group' : (value.startWith('-') ? 'excludedPerson' : 'person'
	}
});

$('#resetButton').click(function() {
	$("#receivers").val('')
	$("#receivers").data('uiInputosaurus').refresh()
	CKEDITOR.instances.message.setData('', function() { this.updateElement(); })
})

$(document).ready(
	function() {
		$(".tx_sendMessage button[type=submit]").each(function() {
			$(this).mouseup(function() {
				$(this).prop('disabled', true)
				$('#waitMessageContainer').append('<div class="waitMessage" >Nachricht wird geprüft und versandt... ⌛</div>')
/*		  		setTimeout(function(){
								$(".tx_sendMessage button[type=submit]").each(function() {
									$(this).prop('disabled', false)
									$('.waitMessage').remove()
							});
				},
				 45000)*/
				$(this).parent('form').submit()
			})
		});
	});

