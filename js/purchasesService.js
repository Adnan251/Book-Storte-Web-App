var purchasesService = {
    init: function(){
      purchasesService.list();
    },

    parseJWT: function(token){
      var base64Url = token.split('.')[1];
      var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
      var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
          return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));
      return JSON.parse(jsonPayload);
    },

    list: function(){
        $.ajax({
            url:"rest/purchases",
            type:"GET",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
            },
            success: function(data){
                SPApp.handleSectionVisibility("#view_purchases");
                var html=`<table class="table table-striped table-dark">
                <thead>
                  <tr>
                    <th scope="col">id</th>
                    <th scope="col">Book</th>
                    <th scope="col">Purchase amount</th>
                    <th scope="col">Time of purchase</th>
                    <th scope="col">Date of purchase</th>
                    <th scope="col">Sold by</th>
                  </tr>
                </thead>
                <tbody>`;
                for(let i=0;i<data.length;i++){
                    html+=`<tr>
                    <th scope="row">`+data[i].id+`</th>
                    <td>`+data[i].Book_Name+`</td>
                    <td>`+data[i].Book_price+`KM</td>
                    <td>`+data[i].Time_of_Purchase+`</td>
                    <td>`+data[i].Date_of_Purchase+`</td>
                    <td>`+data[i].User_Name+` `+data[i].User_Last_Name+`</td>
                  </tr>`
                }
                html+=`</tbody>
                        </table>`;
              $("#view_purchases").html(html);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest.responseJSON.message);
                usersService.logout();
              }
        })
    },

    sell: function(id){
      var purchase = {
        "BookID":id,
        "User_Name":purchasesService.parseJWT(localStorage.getItem('token')).User_Name,
        "User_Last_Name":purchasesService.parseJWT(localStorage.getItem('token')).User_Last_Name
      }
        $("#addPurchase").modal("show");
        $("#purchaseYes").click(function(){
          $.ajax({
            url:'rest/purchases',
            type:'POST',
            data:JSON.stringify(purchase),
            contentType:'application/json',
            dataType:'json',
            beforeSend: function(xhr){
              xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
              console.log(xhr.getAllResponseHeaders());
            },
            success: function(result){
              $("#addPurchase").modal("hide");
              if(result.error!=null){
                toastr.error(result.error);
              }
              if(result.message!=null){
                toastr.success(result.message);
                $('#book-list').html(`<div id="book-list" class="row">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                          <span class="sr-only"></span>
                        </div>
                    </div>
                </div>`);
                BookService.list();
              }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              toastr.error(XMLHttpRequest.responseJSON.message);
            }
          })
        });
    }

}