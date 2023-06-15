var BookService = {
    init: function(){
        $('#addBooksForm').validate({
            submitHandler: function(form){ 
              var books = Object.fromEntries((new FormData(form)).entries())
              BookService.add(books);
            }
          });
          BookService.list();
    },

    list: function(){
          $.ajax({
            url: "rest/books",
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
            },
            success: function(data){
              SPApp.handleSectionVisibility("#view_books");
              var html=`<div class="book-change" class="row">
              <button class="btn btn btn-warning" data-bs-toggle="modal" data-bs-target="#addElement" style="margin-bottom: 10px"> Add Book </button>
              <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#searchWriter" style="margin-bottom: 10px"> <i class="bi bi-search"> Search Writer</i></button>
            </div>`;
              for(let i=0;i<data.length;i++){
                if(data[i].is_available >= 1){
                  html+=`
                  <div class="col-lg-3 container overflow-hidden" id="view_books" style="padding-bottom: 25px;">
                  <div class="card" style="width: 18rem; background-color:#86DC3D; border-radius: 25px;">
                      <img class="card-img-top" src="https://www.pngall.com/wp-content/uploads/4/Book-Now-Button-Transparent.png" alt="Card image cap">
                      <div class="card-body">
                          <h4 class="card-title">`+data[i].Writer_Name +` ` +data[i].Writer_Last_Name+`</h4>
                          <h5 class="card-title" id="displayBookName">`+data[i].Book_Name+`</h5>
                          <p class="card-text">`+data[i].name +`
                          <br>`+data[i].Book_price+`KM</p>
                          <div class="btn-group" role="group">
                              <button type="button" class="btn btn-secondary books-button" onclick="BookService.get(`+data[i].id+`)">View Info</button>
                              <button type="button" class="btn btn-warning books-button" onclick="purchasesService.sell(`+data[i].id+`)">Sell</button>
                              <button type="button" class="btn btn-warning books-button" style="background-color:#23aef2;" onclick="BookService.remove(`+data[i].id+`)">Remove</button>
                          </div>
                      </div>
                      </div>
                      </div>`;
                }
                else if(data[i].is_available < 1){
                  html+=`
                  <div class="col-lg-3 container overflow-hidden" id="view_books" style="padding-bottom: 25px;">
                  <div class="card" style="width: 18rem; background-color:#FF2C2C; border-radius: 25px;">
                      <img class="card-img-top" src="https://www.pngall.com/wp-content/uploads/4/Book-Now-Button-Transparent.png" alt="Card image cap">
                      <div class="card-body">
                          <h4 class="card-title">`+data[i].Writer_Name +` ` +data[i].Writer_Last_Name+`</h4>
                          <h5 class="card-title" id="displayBookName">`+data[i].Book_Name+`</h5>
                          <p class="card-text">`+data[i].name +`
                          <br>`+data[i].Book_price+`KM</p>
                          <div class="btn-group" role="group">
                              <button type="button" class="btn btn-secondary books-button" onclick="BookService.get(`+data[i].id+`)">View Info</button>
                              <button type="button" class="btn btn-warning books-button" onclick="purchasesService.sell(`+data[i].id+`)">Sell</button>
                              <button type="button" class="btn btn-warning books-button" style="background-color:#23aef2;" onclick="BookService.remove(`+data[i].id+`)">Remove</button>
                          </div>
                      </div>
                      </div>
                      </div>`;
                }
              }
              $("#view_books").html(html);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              toastr.error(XMLHttpRequest.responseJSON.message);
            }
        });
    },

    remove: function(id){
      $(".books-button").attr("disabled",true);
        $.ajax({
          url: "rest/books/remove/"+id,
          type: "PUT",
          beforeSend: function(xhr){
            xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
          },
          success: function(result){
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
    },

    get: function(id){
      $(".books-button").attr("disabled",true);
        $.ajax({
          url: "rest/books/"+id,
          type: "GET",
          beforeSend: function(xhr){
            xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
          },
          success: function(data){
            data = data[0];
            console.log(data);
            $("#id").val(data.id);
            $("#bookName").val(data.Book_Name);
            $("#writerName").val(data.Writer_Name);
            $("#writerLastName").val(data.Writer_Last_Name);
            $("#publisher").val(data.name);
            $("#yearOfPublishing").val(data.Year_of_publishing);
            $("#price").val(data.Book_price);
            $("#inventory").val(data.In_inventory);
            $("#exampleModal").modal("show");
            $(".books-button").attr("disabled",false);
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            toastr.error(XMLHttpRequest.responseJSON.message);
          }
        })
    },

    add: function(books){
        $.ajax({
            url:'rest/books',
            type:'POST',
            data:JSON.stringify(books),
            contentType:'application/json',
            dataType:'json',
            beforeSend: function(xhr){
              xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
            },
            success: function(result){
              $("#addElement").modal("hide");
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
    },

    delete: function(id){
     $(".books-button").attr("disabled",true);
    $.ajax({
      url:'rest/books/'+id,
      type:'DELETE',
      beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
      },
      success: function(result){
        $('#book-list').html(`<div id="book-list" class="row">
              <div class="d-flex justify-content-center">
                  <div class="spinner-border" role="status">
                    <span class="sr-only"></span>
                  </div>
              </div>
          </div>`)
          BookService.list();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      }
    })
    },


    update: function(id){
        $(".books-button").attr("disabled",true);
        var books={};
        books.id=$("#id").val();
        books.Book_Name=$("#bookName").val();
        //books.Writer_ID=$("#writerid").val();
        books.Writer_Name=$("#writerName").val();
        books.Writer_Last_Name=$("#writerLastName").val();
        books.name=$("#publisher").val();
        books.Year_of_publishing=$("#yearOfPublishing").val();
        books.Book_price=$("#price").val();
        books.In_inventory=$("#inventory").val();
        console.log(books);
        $.ajax({
          url:'rest/books/'+$("#id").val(),
          type:'PUT',
          data:JSON.stringify(books),
          contentType:'application/json',
          dataType:'json',
          beforeSend: function(xhr){
            xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
          },
          success: function(result){
            if(result.error!=null){
              toastr.error(result.error);
            }if(result.message!=null){
              toastr.success(result.message);
              $("#exampleModal").modal("hide");
              $(".books-button").attr("disabled",false);
              $('#book-list').html(`<div id="book-list" class="row">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                          <span class="sr-only"></span>
                        </div>
                    </div>
                </div>`)
                BookService.list();
            }
            $(".books-button").attr("disabled",false);
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            toastr.error(XMLHttpRequest.responseJSON.message);
          }
        })
    },

    search: function(){
        var name = document.getElementById("search-material").value;
        console.log(name);
        $.ajax({
          url: "rest/books/search/"+name,
          type: "GET",
          beforeSend: function(xhr){
            xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
          },
          success: function(data){
            SPApp.handleSectionVisibility("#view_search_books");
            var html=`<div class="book-change" class="row">
              <button class="btn btn btn-warning" data-bs-toggle="modal" data-bs-target="#addElement" style="margin-bottom: 10px"> Add Book </button>
              <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#searchWriter" style="margin-bottom: 10px"> <i class="bi bi-search"> Search Writer</i></button>
            </div>`;
            for(let i=0;i<data.length;i++){
              if(data[i].is_available >= 1){
                html+=`
                <div class="col-lg-3 container overflow-hidden" id="view_books" style="padding-bottom: 25px;">
                <div class="card" style="width: 18rem; background-color:#86DC3D; border-radius: 25px;">
                    <img class="card-img-top" src="https://www.pngall.com/wp-content/uploads/4/Book-Now-Button-Transparent.png" alt="Card image cap">
                    <div class="card-body">
                        <h4 class="card-title">`+data[i].Writer_Name +` ` +data[i].Writer_Last_Name+`</h4>
                        <h5 class="card-title" id="displayBookName">`+data[i].Book_Name+`</h5>
                        <p class="card-text">`+data[i].name +`
                        <br>`+data[i].Book_price+`KM</p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary books-button" onclick="BookService.get(`+data[i].id+`)">View Info</button>
                            <button type="button" class="btn btn-warning books-button" onclick="purchasesService.sell(`+data[i].id+`)">Sell</button>
                            <button type="button" class="btn btn-warning books-button" style="background-color:#23aef2;" onclick="BookService.remove(`+data[i].id+`)">Remove</button>
                        </div>
                    </div>
                </div>
                </div>`;
              }
              else if(data[i].is_available < 1){
                html+=`
                <div class="col-lg-3 container overflow-hidden" id="view_books" style="padding-bottom: 25px;">
                <div class="card" style="width: 18rem; background-color:#FF2C2C; border-radius: 25px;">
                    <img class="card-img-top" src="https://www.pngall.com/wp-content/uploads/4/Book-Now-Button-Transparent.png" alt="Card image cap">
                    <div class="card-body">
                        <h4 class="card-title">`+data[i].Writer_Name +` ` +data[i].Writer_Last_Name+`</h4>
                        <h5 class="card-title" id="displayBookName">`+data[i].Book_Name+`</h5>
                        <p class="card-text">`+data[i].name +`
                        <br>`+data[i].Book_price+`KM</p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary books-button" onclick="BookService.get(`+data[i].id+`)">View Info</button>
                            <button type="button" class="btn btn-warning books-button" onclick="purchasesService.sell(`+data[i].id+`)">Sell</button>
                            <button type="button" class="btn btn-warning books-button" style="background-color:#23aef2;" onclick="BookService.remove(`+data[i].id+`)">Remove</button>
                        </div>
                    </div>
                    </div>
                    </div>`;
              }
            }
            $("#view_search_books").html(html);
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            toastr.error(XMLHttpRequest.responseJSON.message);
          }
      });
    },

    searchWriter: function(){
      var writerName = document.getElementById("searchWriterName").value;
      var writerLastName = document.getElementById("searchWriterLastName").value;
      console.log(writerName+" "+writerLastName);
      $.ajax({
        url: "rest/search_books/writer?name="+writerName+"&lastname="+writerLastName,
        type: "GET",
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        success: function(data){
          $("#searchWriter").modal("hide");
          SPApp.handleSectionVisibility("#view_search_by_writers");
          var html=`<div class="book-change" class="row">
              <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addElement" style="margin-bottom: 10px"> Add Book </button>
              <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#searchWriter" style="margin-bottom: 10px"> <i class="bi bi-search"> Search Writer</i></button>
            </div>`;
          for(let i=0;i<data.length;i++){
              if(data[i].is_available >= 1){
                  html+=`
                  <div class="col-lg-3 container overflow-hidden" id="view_books" style="padding-bottom: 25px;">
                  <div class="card" style="width: 18rem; background-color:#86DC3D; border-radius: 25px;">
                      <img class="card-img-top" src="https://www.pngall.com/wp-content/uploads/4/Book-Now-Button-Transparent.png" alt="Card image cap">
                      <div class="card-body">
                          <h4 class="card-title">`+data[i].Writer_Name +` ` +data[i].Writer_Last_Name+`</h4>
                          <h5 class="card-title" id="displayBookName">`+data[i].Book_Name+`</h5>
                          <p class="card-text">`+data[i].name +`
                          <br>`+data[i].Book_price+`KM</p>
                          <div class="btn-group" role="group">
                              <button type="button" class="btn btn-secondary books-button" onclick="BookService.get(`+data[i].id+`)">View Info</button>
                              <button type="button" class="btn btn-warning books-button" onclick="purchasesService.sell(`+data[i].id+`)">Sell</button>
                              <button type="button" class="btn btn-warning books-button" style="background-color:#23aef2;" onclick="BookService.remove(`+data[i].id+`)">Remove</button>
                          </div>
                      </div>
                  </div>
                  </div>`;
                }
                else if(data[i].is_available < 1){
                  html+=`
                  <div class="col-lg-3 container overflow-hidden" id="view_books" style="padding-bottom: 25px;">
                  <div class="card" style="width: 18rem; background-color:#FF2C2C; border-radius: 25px;">
                      <img class="card-img-top" src="https://www.pngall.com/wp-content/uploads/4/Book-Now-Button-Transparent.png" alt="Card image cap">
                      <div class="card-body">
                          <h4 class="card-title">`+data[i].Writer_Name +` ` +data[i].Writer_Last_Name+`</h4>
                          <h5 class="card-title" id="displayBookName">`+data[i].Book_Name+`</h5>
                          <p class="card-text">`+data[i].name +`
                          <br>`+data[i].Book_price+`KM</p>
                          <div class="btn-group" role="group">
                              <button type="button" class="btn btn-secondary books-button" onclick="BookService.get(`+data[i].id+`)">View Info</button>
                              <button type="button" class="btn btn-warning books-button" onclick="purchasesService.sell(`+data[i].id+`)">Sell</button>
                              <button type="button" class="btn btn-warning books-button" style="background-color:#23aef2;" onclick="BookService.remove(`+data[i].id+`)">Remove</button>
                          </div>
                      </div>
                      </div>
                      </div>`;
                }
        }
        $("#view_search_by_writers").html(html);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          toastr.error(XMLHttpRequest.responseJSON.message);
        }
      })
    }
}