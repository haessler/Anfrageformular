if (window.location.search && window.location.search == '?danke') {
	if (document.getElementById('formreply')) {
		document.getElementById('formreply').setAttribute('style','width:100%;box-sizing:padding-box;padding:1em;text-align:center;background:#F5FFEA;border-bottom:1px solid green;border-top:1px solid green;margin-bottom:1em;');
		(document.getElementById('formreply')).innerHTML = 'Vielen Dank für Ihr Interesse. Ihre Nachricht wurde versendet und wir werden zeitnah antworten.';
	}
}
if (window.location.search && window.location.search == '?missing') {
	if (document.getElementById('formreply')) {
		document.getElementById('formreply').setAttribute('style','width:100%;padding:1em;box-sizing:padding-box;text-align:center;background:#E8B6B0;border-bottom:1px solid firebrick;1px solid firebrick;margin-bottom:1em;');
		(document.getElementById('formreply')).innerHTML = 'Bitte prüfen Sie ihre Angaben. Wir brauchen Ihren Namen und ihre Emailadresse für eine Antwort.';
	}
}
