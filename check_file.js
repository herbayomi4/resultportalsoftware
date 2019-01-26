var file = document.getElementById('file');
file.onchange = function(e){
	var ext = this.value.match(/\.([^\.]+)$/)[1];
	switch(ext)
	{
		case 'xls':
		case 'xlsx':
		return true;
		break;
		default:
		alert('File type not allowed. Only Excel File please');
		this.value = '';
	}
}