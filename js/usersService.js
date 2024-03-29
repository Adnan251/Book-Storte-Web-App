var usersService = {

    init: function()
    {
        var token = localStorage.getItem("token");
        if (token){
            window.location.replace("index.html");
        }
        $('#login-form').validate({
            submitHandler : function(form){
            var entity = Object.fromEntries((new FormData(form)).entries());
            console.log(entity);
            usersService.login(entity);
            }
        });
    },
    
    login: function(entity)
    {
        $.ajax({
          url: 'rest/login',
          type: 'POST',
          data: JSON.stringify(entity),
          contentType: "application/json",
          dataType: "json",
          success: function(result) {
            localStorage.setItem("token", result.token);
            window.location.replace("index.html");
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest.responseJSON);
            toastr.error(XMLHttpRequest.responseJSON.message);
          }
        });
    },
    

    logout: function()
    {
        localStorage.clear();
        window.location.replace("login.html");
    }
}