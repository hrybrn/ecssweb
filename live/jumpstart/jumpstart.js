function scrollToCityChallenge(e) {
    e.preventDefault();
    var offset = $('#cityChallenge').offset();
    $('html, body').animate({
        scrollTop: offset.top - 15,
        scrollLeft: offset.left - 15
    });
    window.location.hash = 'cityChallenge';
}


function toggleCalendar(e){
	var currentid = e.path[0].id + 'Calendar';	
	$('.centerDiv').hide();
	if($('#' + currentid).length == 0){
		addCalendar(currentid);
	} else {
		$('#' + currentid).show();
	}
}

function addCalendar(id){
	if(id == "mscTimetableCalendar"){
		$('body').append('<div id="' + id + '" class="centerDiv"><iframe src="https://calendar.google.com/calendar/embed?mode=WEEK&amp;height=600&amp;wkst=2&amp;bgcolor=%23FFFFFF&amp;src=0s8rbd2g07uf6uqil2fir8decg%40group.calendar.google.com&amp;color=%235229A3&amp;ctz=Europe%2FLondon&amp;dates=20170925%2F20171001" style="border-width:0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>');
	}
	if(id == "ugTimetableCalendar"){
		$('body').append('<div id="' + id + '" class="centerDiv"><iframe src="https://calendar.google.com/calendar/embed?mode=WEEK&amp;height=600&amp;wkst=2&amp;bgcolor=%23FFFFFF&amp;src=gvo3td8eik1aclq6hvj554c848%40group.calendar.google.com&amp;color=%2329527A&amp;ctz=Europe%2FLondon&amp;dates=20170925%2F20171001" style="border-width:0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>');
	}
}