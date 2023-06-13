var OrdersService = {
    init: function(){
      $("#addOrderForm").validate({
        submitHandler: function(form){ 
          var orders = Object.fromEntries((new FormData(form)).entries())
          OrdersService.add(orders);
        }
      })
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
            url: "rest/orders",
            type: "GET",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
            },
            success: function(data){
                SPApp.handleSectionVisibility("#view_orders");
                var html=`<div class="book-change" class="row">
                <button class="btn btn btn-warning" data-bs-toggle="modal" data-bs-target="#addOrder" style="margin-bottom: 10px"> Add Order </button>
                </div>
                <table class="table table-striped table-dark">
                <thead>
                  <tr>
                    <th scope="col"></th>
                    <th scope="col">id</th>
                    <th scope="col">Book</th>
                    <th scope="col">Order amount</th>
                    <th scope="col">Order price</th>
                    <th scope="col">Date of order</th>
                    <th scope="col">Date of delivery</th>
                    <th scope="col">Ordered by</th>
                  </tr>
                </thead>
                <tbody>`;
                for(let i=0;i<data.length;i++){
                    html+=`
                    <tr>
                    <th scope="row">
                        <div class="btn-group" role="group">
                        <button type="button" class="btn btn-light btn-sm orders-button" onclick="OrdersService.get(`+data[i].id+`)">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm orders-button" onclick="OrdersService.delete(`+data[i].id+`)">Delete</button>
                        </div>
                    </th>
                    <td>`+data[i].id+`</td>
                    <td>`+data[i].book_name+`</td>
                    <td>`+data[i].Order_Amount+`</td>
                    <td>`+data[i].Order_price+` KM</td>
                    <td>`+data[i].Date_of_Order+`</td>
                    <td>`+data[i].Date_of_Delivery+`</td>
                    <td>`+data[i].User_Name+` `+data[i].User_Last_Name+`</td>
                  </tr>`
                }
                html+=`</tbody>
                        </table>`;
              $("#view_orders").html(html);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              toastr.error(XMLHttpRequest.responseJSON.message);
            }
        })
    },

    add: function(orders){
      orders.User_Name = OrdersService.parseJWT(localStorage.getItem('token')).User_Name;
      orders.User_Last_Name = OrdersService.parseJWT(localStorage.getItem('token')).User_Last_Name;
      console.log(orders);
      $.ajax({
        url: "rest/orders",
        type: "POST",
        data: JSON.stringify(orders),
        contentType: 'application/json',
        dataType:'json',
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        success: function(result){
          console.log(result);
          if(result.error!=null){
            toastr.error(result.error);
          }
          if(result.message!=null){
            toastr.success(result.message);
            $('#orders_list').html(`<div id="orders_list" class="row">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                  <span class="sr-only"></span>
                </div>
            </div>
           </div>`);
            $("#addOrder").modal("hide");
            OrdersService.list();
            console.log(result);
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          toastr.error(XMLHttpRequest.responseJSON.message);
        }
      })
    },

    update: function(id){
        $(".orders-button").attr("disabled",true);
        console.log($("#orderID").val());
        var orders = {};
        orders.id = $("#orderID").val();
        orders.book_name = $("#editBookName").val();
        orders.Order_price = $("#editOrderPrice").val();
        orders.Order_Amount = $("#editOrderAmount").val();
        orders.Date_of_Order = $("#editDateOfOrder").val();
        orders.Date_of_Delivery = $("#editDateOfDelivery").val();
        orders.User_Name = $("#userName").val();
        orders.User_Last_Name = $("#userLastName").val();
        console.log(orders);
        $.ajax({
          url: 'rest/orders/'+$("#orderID").val(),
          type: 'PUT',
          data: JSON.stringify(orders),
          contentType: 'application/json',
          dataType: 'json',
          beforeSend: function(xhr){
            xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
          },
          success: function(result){
            if(result.error!=null){
              toastr.error(result.error);
            }
            if(result.message!=null){
              toastr.success(result.message);
              console.log(result);
              $("#editOrder").modal("hide");
              $(".orders-button").attr("disabled",false);
              $('#orders_list').html(`<div id="orders_list" class="row">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                      <span class="sr-only"></span>
                    </div>
                </div>
              </div>`);
              OrdersService.list();
            }
            $(".orders-button").attr("disabled",false);
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            toastr.error(XMLHttpRequest.responseJSON.message);
          }
        })
    },

    delete: function(id){
      $("#deleteOrder").modal("show");
      $("#deleteYes").click(function(){
        $.ajax({
          url: 'rest/orders/'+id,
          type: 'DELETE',
          beforeSend: function(xhr){
            xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
          },
          success: function(result){
            $("#deleteOrder").modal("hide");
              $('#orders_list').html(`<div id="orders_list" class="row">
              <div class="d-flex justify-content-center">
                  <div class="spinner-border" role="status">
                    <span class="sr-only"></span>
                  </div>
              </div>
            </div>`);
            OrdersService.list();
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            toastr.error(XMLHttpRequest.responseJSON.message);
          }
        })
      })
    },

    get: function(id){
      $(".orders-button").attr("disabled", true);
      $.ajax({
        url: 'rest/orders/'+id,
        type: 'GET',
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        success: function(data){
            console.log(data);
            $("#orderID").val(data.id);
            $("#editBookName").val(data.book_name);
            $("#editOrderAmount").val(data.Order_Amount);
            $("#editOrderPrice").val(data.Order_price);
            $("#editDateOfOrder").val(data.Date_of_Order);
            $("#editDateOfDelivery").val(data.Date_of_Delivery);
            $("#userName").val(data.User_Name);
            $("#userLastName").val(data.User_Last_Name);
            $("#editOrder").modal("show");
            $(".orders-button").attr("disabled",false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          toastr.error(XMLHttpRequest.responseJSON.message);
        }
      })
    }
}