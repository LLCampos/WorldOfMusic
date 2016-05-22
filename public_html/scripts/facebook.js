function loginStatusConnected() {
  FB.api('/me?fields=id,email,first_name, last_name, age_range, link, gender, locale, picture, timezone, updated_time', function(response) {
    insertUserPictureOnHeader(response);
    checkIfUserIsAlreadyInDatabase(response);
  });
}

function addUserToDatabase(response) {
  var id = response.id;
  var email = response.email;
  var first_name = response.first_name;
  var last_name = response.last_name;
  var age_range = response.age_range.min;
  var gender = response.gender;
  var locale = response.locale;
  var timezone = response.timezone;
  var updated_time = response.updated_time;
  var access_token = FB.getAuthResponse().accessToken;

  $.ajax({
    url: "http://appserver.di.fc.ul.pt/~aw008/webservices/user",
    method: "POST",
    data: {id: id, email: email, first_name: first_name , last_name: last_name, age_range: age_range, gender: gender, locale: locale, timezone: timezone, updated_time: updated_time,
           access_token: access_token},

    success: function() {
      console.log('yey');
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(errorThrown);
    }


  });
}

function checkIfUserIsAlreadyInDatabase(response) {

  var user_id = response.id;

  $.ajax({
    url: "http://appserver.di.fc.ul.pt/~aw008/webservices/user/" + user_id,

    success: function(response) {
    },
    error: function(jqXHR, textStatus, errorThrown) {
      if (jqXHR.status == '404') {
        addUserToDatabase(response);
      }
    }
  });

}

function insertUserPictureOnHeader(user_info) {
  var picture_url = user_info.picture.data.url;
  var html_img = "<img src=" + picture_url + ">";

  document.getElementById('header-image-user-facebook').innerHTML = html_img;
}

function removeUserPictureOnHeader() {
   var element = document.getElementById('header-image-user-facebook');
   while (element.firstChild) element.removeChild(element.firstChild);
}


function statusChangeCallback(response) {
     if (response.status === 'connected') {
        loginStatusConnected();
     } else if (response.status === 'not_authorized') {
        removeUserPictureOnHeader();
     } else {
        removeUserPictureOnHeader();
     }
}

function checkLoginState () {
     FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
     });
}



window.fbAsyncInit = function() {
    FB.init({
      appId      : '541049339416374',
      xfbml      : true,
      cookie     : true,
      version    : 'v2.5'
    });

    checkLoginState();
};

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_PT/sdk.js#xfbml=1&version=v2.6&appId=541049339416374";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

