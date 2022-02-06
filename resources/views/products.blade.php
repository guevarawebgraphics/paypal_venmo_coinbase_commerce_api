<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <style>
            .products{
                margin-top: 2em;
            }
        </style>
    



    </head>


        <body>

        @include('layouts.header')


        <div class="container" style="margin-top: 5em;">

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">

    <h1 class="display-5 fw-bold">Products</h1>
        <p class="col-md-8 ">Note: This product list is connected to your coinbase commerce account. If you create new product, automatically it will directly stores to your configured coinbase commerce account.</p>
        
    <!-- Button trigger modal -->
<button type="button" class="btn btn-dark btnAdd text-white">
  Add New Product
</button>
    </div>
</div>

<div class="h-100 p-5  border rounded-3">


<table id="product-table" class="table ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Currency</th>
                        <th>Checkout ID</th>
                        <th>Action</th>
                    </tr>

                </thead>
                <tbody>
                    @forelse( $products as $field )
                        @php 
                            $data = array([
                                'name'  =>  $field->name,  
                                'description'  =>  $field->description, 
                                'price'  =>  $field->price
                            ]);
                        @endphp
                        <tr> 
                            <td>{{$field->id}}</td>
                            <td>{{$field->name}}</td>
                            <td>{{$field->description}}</td>
                            <td>{{number_format($field->price,2)}}</td>
                            <td>{{$field->currency}}</td>
                            <td>{{$field->checkout_id}}</td>
                            <td>
                                <button class="btn btn-success text-white btnUpdate" data-id="{{$field->id}}" data-data="{{json_encode($data[0])}}"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger text-white btnDelete" data-id="{{$field->id}}" data-data="{{json_encode($data[0])}}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty 

                    @endforelse 
                </tbody>
        </table>


</div>
</div>

    
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <input type="hidden" name="update_product_id" value="" id="update_product_id">
           <div class="form-group">
                <label for="product_name">Name</label>
                <input type="text" class="form-control" id="product_name">
              
            </div>
            <div class="form-group">
                <label for="product_desc">Description</label>
                <textarea class="form-control" name="product_desc" id="product_desc"></textarea>
            </div>
            <div class="form-group">
                <label for="currency">
                    Currency
                </label>
                <input type="text" class="form-control" value="USD" name="currency" id="currency" readonly>
              
            </div>
            <div class="form-group">
                <label for="product_price">Price</label>
                <input type="text" class="form-control decimal" name="product_price" id="product_price" value="" placeholder="$1.00">
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-dark text-white btn-save-coinbase">Save changes</button>
        <button type="button" class="btn btn-dark text-white btn-update-coinbase" style="display:none;">Update changes</button>
      </div>
    </div>
  </div>
</div>



        <script src="{{ asset('public/js/swal.js') }}"></script>
        <script>
        $(document).ready( function () {
            $('#product-table').DataTable();
        });

        $(".btnAdd").click(function () {
            $(".btn-update-coinbase").hide();
            $(".btn-save-coinbase").show();
            $("#exampleModal").modal('show');
        });

        $(".btnUpdate").click(function () {
            var data = JSON.parse($(this).attr('data-data'));
            var id = $(this).attr('data-id');
            $(".btn-update-coinbase").show();
            $(".btn-save-coinbase").hide();
            $("#exampleModal").modal('show');

            $("#product_name").val(data.name);
            $("#product_desc").val(data.description);
            $("#product_price").val(data.price);
            $("#update_product_id").val(id);
        });

        $(".btn-save-coinbase").click(function () {
            
            var product_name = $("#product_name").val();
            var product_desc = $("#product_desc").val();
            var product_price = $("#product_price").val();

            if (  product_name == "") {
                Swal.fire(
                    'Error',
                    'Product Name is required',
                    'error'
                );
            } else if (  product_desc == "") {
                Swal.fire(
                    'Error',
                    'Product Description is required',
                    'error'
                );
            } else if (  product_price == "") {
                Swal.fire(
                    'Error',
                    'Product Price is required',
                    'error'
                );
            } else {
                $(this).prop('disabled',true);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('checkout_coinbase') }}",
                        method: "POST",
                        data:{
                            product_name:product_name,
                            product_desc:product_desc,
                            product_price:product_price,
                            _token:"{{ csrf_token() }}"
                        }, 
                        success:function(data)
                        {
                            $(this).prop('disabled',false);
                            Swal.fire(
                                'Success',
                                'Product Successfully Stored',
                                'success'
                            );
                            location.reload();
                        },
                        error: function(xhr, ajaxOptions, thrownError){
                            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            Swal.fire(
                                'Error!',
                                xhr.responseText,
                                'error'
                            )
                        }
                });
            }
        });

        $(".btn-update-coinbase").click(function () {
            
            var product_name = $("#product_name").val();
            var product_desc = $("#product_desc").val();
            var product_price = $("#product_price").val();
            var product_id = $("#update_product_id").val();

            if (  product_name == "") {
                Swal.fire(
                    'Error',
                    'Product Name is required',
                    'error'
                );
            } else if (  product_desc == "") {
                Swal.fire(
                    'Error',
                    'Product Description is required',
                    'error'
                );
            } else if (  product_price == "") {
                Swal.fire(
                    'Error',
                    'Product Price is required',
                    'error'
                );
            } else if (  product_id == "") {
                Swal.fire(
                    'Error',
                    'Something went wrong.. Please reload the page',
                    'error'
                );
            } else {
                $(this).prop('disabled',true);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('update_coinbase') }}",
                        method: "POST",
                        data:{
                            product_id:product_id,
                            product_name:product_name,
                            product_desc:product_desc,
                            product_price:product_price,
                            _token:"{{ csrf_token() }}"
                        }, 
                        success:function(data)
                        {
                            $(this).prop('disabled',false);
                            Swal.fire(
                                'Success',
                                'Product Successfully Updated!',
                                'success'
                            );
                            location.reload();
                        },
                        error: function(xhr, ajaxOptions, thrownError){
                            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            Swal.fire(
                                'Error!',
                                xhr.responseText,
                                'error'
                            )
                        }
                });
            }
        });

        $('.decimal').keypress(function(evt){
            return (/^[0-9]*\.?[0-9]*$/).test($(this).val()+evt.key);
        });

        $(".btnDelete").click(function () {
            var product_id = $(this).attr('data-id');
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {


                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('delete_coinbase') }}",
                            method: "POST",
                            data:{
                                product_id:product_id,
                                _token:"{{ csrf_token() }}"
                            }, 
                            success:function(data)
                            {
                               
                                Swal.fire(
                                'Deleted!',
                                'Your product has been deleted.',
                                'success'
                                );
                                location.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError){
                                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                Swal.fire(
                                    'Error!',
                                    xhr.responseText,
                                    'error'
                                )
                            }
                    });

                }
            });
        });

        </script>

            

        @include('layouts.footer')
    </body>
</html>
