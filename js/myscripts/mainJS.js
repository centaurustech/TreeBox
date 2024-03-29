function toggleUserProfileOptions(){
	$("div#user_options_menu_div").toggle();
	$("div#user_profile").toggleClass("user_profile_selected");

	//var url = $('img#show_more_icon')[0].src;
	$("div#user_profile").toggleClass("fb_user_button_selected");

	$("img#show_more_icon").toggle();
	$("img#show_more_icon_selected").toggle();
}
function stickIt() { //sticky header bar
  	var orgElementPos = $('.original').offset();
  	orgElementTop = orgElementPos.top;               

  	if($(window).scrollTop() >= (orgElementTop)) {
	    // scrolled past the original position; now only show the cloned, sticky element.

	    // Cloned element should always have same left position and width as original element.     
	    orgElement = $('.original');
	    coordsOrgElement = orgElement.offset();
	    leftOrgElement = coordsOrgElement.left;  
	    widthOrgElement = orgElement.css('width');

	    $('.cloned').css('left', leftOrgElement + 'px').css('top', 0).css('width', widthOrgElement + 'px').show();
	    $('.original').css('visibility', 'hidden');
  	} else{
	    // not scrolled past the menu; only show the original menu.
	    $('.cloned').hide();
	    $('.original').css('visibility', 'visible');
  	}
}
function checkNotificationsForUser() {
	$('div#notifications_div').toggle();
	$("div#notifications").toggleClass("notifications_selected");
	$("div#notifications").toggleClass("fb_user_button_selected");
}
$(document).ready(function() {
	// Sticky header bar (see http://codepen.io/senff/pen/ayGvD for explanation)
	$('div#header_bar').addClass('original').clone().insertAfter('div#header_bar').addClass('cloned').css('position', 'fixed').css('top', '0').css('margin-top', '0').css('z-index', '500').removeClass('original').hide();
	scrollIntervalID = setInterval(stickIt, 10);

	/*************************navbar*********************/
	$("ul#navbar_menu").navbar({ //see custom plugin ("js/jquery.navbar.js")
		fontFamily: 'Arial, sans-serif', //supports font-weight: 400 (normal) or font-weight: 700 (bold)
		fontWeight: '700',
		fontSize: '.9em',
		letterSpacing: '.04em',
		bgColor : '#005994',
		color : 'white',
		//border: '1px solid #009AFF',
		hoverBgColor : '#0071BD',
		hoverColor : 'white',
		//hoverBorder: '1px solid white',
		//borderRadius: '.3em', //borderRadius only does the border of the whole list, not each link
		linkWidth : 'auto',
		padding : '.7em'
	});

	//adjust header bar height to include all elements
	$("div#header_bar").height($("#navbar_menu").outerHeight(true));

	//Drop down user option menu
	var offset = ($("div#header_bar").height() - $("div#user_profile").outerHeight(true)) / 2;
	$("div#user_options_menu_div").css("bottom", -1 * ($("div#user_options_menu_div").outerHeight(true) + (offset * 10 / 9))); //1.2 for adjustment
	$("div#user_profile").click(function(){

		$(document).click(function(event){ 
            if(!$(event.target).closest('div#user_profile').length) { //make sure the element clicked is not an ancestor of the user_profile div
                $("div#user_options_menu_div").hide();
                
                $("div#user_profile").removeClass("user_profile_selected");
                $("div#user_profile").removeClass("fb_user_button_selected");
                $("img#show_more_icon").show();
				$("img#show_more_icon_selected").hide();
            } 
        });

        toggleUserProfileOptions();
	});

	//notifications bar
	var offset = ($("div#header_bar").height() - $("div#notifications").outerHeight(true)) / 2;
	$("div#notifications_div").css("bottom", -1 * ($("div#notifications_div").outerHeight(true) + (offset * 11/9))); //sets the position of the notifications div
	$("div#notifications").click(function(){
		$(document).click(function(event){ 
            if(!$(event.target).closest('div#notifications').length) { //if user clicks outside of the notifications div
                $("div#notifications_div").hide();

                $("div#notifications").removeClass("notifications_selected");
                $("div#notifications").removeClass("fb_user_button_selected");
            } 
        });

        checkNotificationsForUser();
	});

	//this will apply to the menu lists from the user options and notifications 
	/*make sure text changes color when hovering (it misses if the cursor is just on the border of the list item)*/
	$("li.action").hover(
		function(){ //hoverIn
			$(this).find($("a.action")).css("color", "white"); //changes child a to white
		},
		function(){ //hoverOut
			$(this).find($("a.action")).css("color", "#232937");
		}
	);
}); //end document.ready()
$(window).load(function(){ //make sure that ALL items on page TRULY load 
	$("div#header_bar").height($("#navbar_menu").outerHeight(true));
});


