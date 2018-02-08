$(document).ready(function(){
    if(previous.hackathonMatchmaking != "false"){
        $('#matchmaking').prop("checked", true);
    }
});

function genToken(){
    $.ajax({
        url: "/hackathon/team/manage/generateToken.php",
		type: 'post',
		data: {},
		dataType: 'json',
		success: function(result){
            if(result.status){
                location.reload();
            } 
        }
    });
}

function copyLink(text) {
    var textArea = document.createElement("textarea");
  
    //
    // *** This styling is an extra step which is likely not required. ***
    //
    // Why is it here? To ensure:
    // 1. the element is able to have focus and selection.
    // 2. if element was to flash render it has minimal visual impact.
    // 3. less flakyness with selection and copying which **might** occur if
    //    the textarea element is not visible.
    //
    // The likelihood is the element won't even render, not even a flash,
    // so some of these are just precautions. However in IE the element
    // is visible whilst the popup box asking the user for permission for
    // the web page to copy to the clipboard.
    //
  
    // Place in top-left corner of screen regardless of scroll position.
    textArea.style.position = 'fixed';
    textArea.style.top = 0;
    textArea.style.left = 0;
  
    // Ensure it has a small width and height. Setting to 1px / 1em
    // doesn't work as this gives a negative w/h on some browsers.
    textArea.style.width = '2em';
    textArea.style.height = '2em';
  
    // We don't need padding, reducing the size if it does flash render.
    textArea.style.padding = 0;
  
    // Clean up any borders.
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
  
    // Avoid flash of white box if rendered for any reason.
    textArea.style.background = 'transparent';
  
  
    textArea.value = text;
  
    document.body.appendChild(textArea);
  
    textArea.select();
  
    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Copying text command was ' + msg);
    } catch (err) {
      console.log('Oops, unable to copy');
    }
  
    document.body.removeChild(textArea);
  }
  
  
  var copyBobBtn = document.querySelector('.js-copy-bob-btn'),
    copyJaneBtn = document.querySelector('.js-copy-jane-btn');
  
  copyBobBtn.addEventListener('click', function(event) {
    copyTextToClipboard('Bob');
  });
  
  
  copyJaneBtn.addEventListener('click', function(event) {
    copyTextToClipboard('Jane');
  });

function leaveTeam(){
    if(!confirm("Are you sure you want to leave this team?")){
        return;
    }
    $.ajax({
        url: "/hackathon/team/manage/leaveTeam.php",
		type: 'post',
		data: {},
		dataType: 'json',
		success: function(result){
            if(result.status){
                location = "/hackathon";
            } 
        }
    });
}

function disbandTeam(){
    if(!confirm("Are you sure you want to disband this team?")){
        return;
    }
    $.ajax({
        url: "/hackathon/submit/delete.php",
		type: 'post',
		data: {
            type: 'team'
        },
		dataType: 'json',
		success: function(result){
            if(result.status){
                location = "/hackathon";
            } 
        }
    });
}

function fail(failedElement, failure){
    if(failedElement == 'submit'){
        $('#' + failedElement).append("<label class='error'>" + failure + "</label>");
    } else {
        $('#' + failedElement).parent().append("<label class='error'>" + failure + "</label>");
    }
}

function expire(hashID){
    $.ajax({
        url: "/hackathon/team/manage/expire.php",
		type: 'post',
		data: {
            hashID : hashID
        },
		dataType: 'json',
		success: function(result){
            if(result.status){
                location.reload();
            }
        }
    });
}

function kick(email){
    $.ajax({
        url: "/hackathon/team/manage/kick.php",
		type: 'post',
		data: {
           email : email
        },
		dataType: 'json',
		success: function(result){
            if(result.status){
                location.reload();
            }
        }
    });
}

function update(){
    $('.error').remove();

    var changed = false;
    var sending = {};

    if($('#matchmaking').is(":checked") && previous.hackathonMatchmaking == 'false'){
        sending.hackathonMatchmaking = $('#matchmaking').is(":checked");
        changed = true;
    }

    if(!$('#matchmaking').is(":checked") && previous.hackathonMatchmaking == 'true'){
        sending.hackathonMatchmaking = $('#matchmaking').is(":checked");
        changed = true;
    }

    if($('#name').val() != previous.hackathonName){
        sending.hackathonName = $('#name').val();
        changed = true;
    }

    if(!changed){
        fail('submit', 'no changes made');
        return;
    }

    if($('#name').val() == ''){
        fail('name', 'cannot have an empty name');
        return;
    }

    $.ajax({
        url: "/hackathon/submit/change.php",
		type: 'post',
		data: {
            type: 'team',
            params: sending
        },
		dataType: 'json',
		success: function(result){
            if(!result.status){
                return false;
            }
            var nextsteps = "<p>" + result.message + "</p>";
            nextsteps += '<button onclick=\'window.location.href="/hackathon"\'>Hackathon Home</button>';
            nextsteps += '<button onclick="update()">Update</button>';
            nextsteps += "<button onclick='genToken()'>Generate Token</button>";
            $('#submit').html(nextsteps);
        }
    });
}