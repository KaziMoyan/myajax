<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel 11 CRUD Ajax By Moyan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #000;
            color: white;
            font-family: Arial, sans-serif;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    
        .stars {
            width: 100%;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/stardust.png') repeat;
            position: fixed;
            top: 0;
            left: 0;
            animation: starry 50s linear infinite;
            z-index: -1;
        }
    
        @keyframes starry {
            from { background-position: 0 0; }
            to { background-position: -10000px 5000px; }
        }
    
        h1 {
            padding: 10px;
            text-align: center;
            animation: colorChange 5s infinite;
            font-size: 2em;
        }
    
        @keyframes colorChange {
            0% { color: white; }
            25% { color: #ff5733; }
            50% { color: #33ff57; }
            75% { color: #3357ff; }
            100% { color: white; }
        }
    
        .container {
            position: relative;
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            z-index: 10;
        }
    
        table.dataTable {
            color: white;
            background-color: black; /* Change the background color of the table */
            animation: tableColorChange 10s infinite;
        }
    
        @keyframes tableColorChange {
            0% { background-color: rgba(0, 0, 0, 0.8); }
            25% { background-color: rgba(51, 51, 255, 0.8); }
            50% { background-color: rgba(255, 51, 51, 0.8); }
            75% { background-color: rgba(51, 255, 51, 0.8); }
            100% { background-color: rgba(0, 0, 0, 0.8); }
        }
    
        table.dataTable thead th {
            color: white;
        }
    
        td {
            animation: textAnimation 3s infinite;
        }
    
        @keyframes textAnimation {
            0% { color: white; }
            50% { color: #33ff57; }
            100% { color: white; }
        }
    </style>
    
  
</head>
<body>
    <div class="stars"></div>

    <div class="container">
        <h1>Laravel 11 CRUD Ajax By Moyan</h1>
        <a href="javascript:void(0)" class="btn btn-info ml-3" id="create-new-product">Add New</a>
        <br><br>
        <table class="table table-bordered table-striped" id="laravel_11_datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>S. No</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="ajax-product-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="productCrudModal"></h4>
                </div>
                <div class="modal-body">
                    <form id="productForm" name="productForm" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="category" class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="category" name="category" placeholder="Enter Category" value="" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price" class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="price" name="price" placeholder="Enter Price" value="" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image" class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-12">
                                <input id="image" type="file" name="image" accept="image/*" onchange="readURL(this);">
                                <input type="hidden" name="hidden_image" id="hidden_image">
                            </div>
                        </div>
                        <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100" height="100">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var SITEURL = '{{ url("/") }}/';
        $(document).ready(function() {
            var table = $('#laravel_11_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: SITEURL + "products",
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': false },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'category', name: 'category' },
                    { data: 'price', name: 'price' },
                    { data: 'image', name: 'image', orderable: false },
                    { data: 'action', name: 'action', orderable: false },
                ],
                order: [[0, 'desc']]
            });

            $('#create-new-product').click(function() {
                $('#btn-save').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $('#productCrudModal').html("Add New Product");
                $('#ajax-product-modal').modal('show');
                $('#modal-preview').attr('src', 'https://via.placeholder.com/150').addClass('hidden');
            });

            $('body').on('click', '.edit-product', function() {
                var product_id = $(this).data('id');
                $.get(SITEURL + 'products/edit/' + product_id, function(data) {
                    $('#productCrudModal').html("Edit Product");
                    $('#btn-save').val("edit-product");
                    $('#ajax-product-modal').modal('show');
                    $('#product_id').val(data.id);
                    $('#title').val(data.title);
                    $('#category').val(data.category);
                    $('#price').val(data.price);
                    if (data.image) {
                        $('#modal-preview').attr('src', SITEURL + 'public/product/' + data.image).removeClass('hidden');
                        $('#hidden_image').val(data.image);
                    } else {
                        $('#modal-preview').attr('src', 'https://via.placeholder.com/150').addClass('hidden');
                    }
                });
            });

            $('body').on('click', '#delete-product', function() {
                var product_id = $(this).data("id");
                if (confirm("Are you sure you want to delete this product?")) {
                    $.ajax({
                        type: "DELETE",
                        url: SITEURL + "products/" + product_id,
                        success: function(data) {
                            table.draw(false);
                        },
                        error: function(data) {
                            console.error('Error:', data);
                        }
                    });
                }
            });

            $('body').on('submit', '#productForm', function(e) {
                e.preventDefault();
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: SITEURL + "products/store",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#productForm').trigger("reset");
                        $('#ajax-product-modal').modal('hide');
                        $('#btn-save').html('Save changes');
                        table.draw(false);
                    },
                    error: function(data) {
                        console.error('Error:', data);
                        $('#btn-save').html('Save changes');
                    }
                });
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#modal-preview').attr('src', e.target.result).removeClass('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>

