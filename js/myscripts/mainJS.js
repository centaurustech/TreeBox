$(document).ready(function() {
	/*************************navbar*********************/
	$("#navbar_menu").navbar({ //see custom plugin ("js/jquery.navbar.js")
		fontFamily: 'PT Sans, sans-serif', //supports font-weight: 400 (normal) or font-weight: 700 (bold)
		fontWeight: '700',
		fontSize: '.9em',
		letterSpacing: '.04em',
		bgColor : '#005994',
		color : 'white',
		border: '1px solid #009AFF',
		hoverBgColor : '#0071BD',
		hoverColor : 'white',
		hoverBorder: '1px solid white',
		borderRadius: '.3em', //borderRadius only does the border of the whole list, not each link
		linkWidth : 'auto',
		padding : '.7em'
	});
}); //end document.ready()
