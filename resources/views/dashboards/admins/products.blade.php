@extends('layouts.app')
@section('title', 'OSave | Product List')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div>
                        <h4 class="">Product List</h4>

                    </div>
                    <div>
                        <!-- <a href="javascript:void(0)" id="editProduct" class="btn btn-warning add-list"><i
                                class="las la-edit"></i> Edit Table</a> -->
                        
                        <a href="javascript:void(0)" id="createProduct" class="btn btn-primary add-list"><i
                                class="las la-plus mr-3"></i>Add Product</a>
                                
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="ttl-amt py-2 px-3 d-flex justify-content-end mt-2"></div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3 ">
                    <table class="table mb-0 product-table">
                        <thead>
                            <tr class="ligth ligth-data">
                                <th>No.</th>
                                <th>Product Description</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th>Cost Price</th>
                                <th>Supplier</th>
                                <th>Re-Order Point</th>
                                <th>Item Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="productModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form id="productForm" name="productForm" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="container">
                            
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-osave">Product Description*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter Name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="category" class="col-sm-4 col-form-label text-osave">Category*</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="category" name="category" required onchange="toggleNewCategoryInput()">
                                        <option value="">Select Category</option>
                                        <option value="Beverages">Beverages</option>
                                        <option value="Dry Goods">Dry Goods</option>
                                        <option value="new">Create New Category</option> <!-- New option to create category -->
                                    </select>
                                    <input type="text" id="newCategory" name="newCategory" class="form-control mt-2" 
                                        placeholder="Enter new category" style="display: none;"> <!-- Hidden input for new category -->
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="unit" class="col-sm-4 col-form-label text-osave">Unit*</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="unit" name="unit" required>
                                        <option value="">Select unit</option>
                                        <option value="BXS">BXS</option>
                                        <option value="SCK">SCK</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="form-group row">
                                <label for="re-oder-point" class="col-sm-4 col-form-label text-osave">Re-Order Point*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="re-oder-point" name="re-oder-point"
                                        placeholder="Enter Re-Order Point" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="image" class="col-sm-4 col-form-label text-osave">Image</label>
                                <div class="col-sm-8">
                                    <input type="file" id="image" name="image" class="form-control-file"
                                        accept="image/*">
                                </div>
                            </div>
                            <h6>Purchase Information</h6>
                            <div class="form-group row">
                                <label for="price" class="col-sm-4 col-form-label text-osave">Cost Price*</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="price" name="price"
                                        placeholder="Enter Price" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="supplier" class="col-sm-4 col-form-label text-osave">Supplier</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="supplier" name="supplier">
                                        <option value="" disabled selected>Select a supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="productSaveBtn" value="create">Save</button>
                </div>
            </div>
        </div>
    </div>    
@endsection

@section('scripts')
    <script>
        $(function() {
            var productTable = $('.product-table').DataTable({
                processing: true,
                responsive: true,
                autoWidth: false,
                lengthMenu: [10, 20, 50, 100],
                ajax: {
                    url: "{{ route('products.getProducts') }}",
                    method: 'GET',
                    dataType: 'JSON'
                },
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name',
                        render: function(data, type, row) {
                            var imageUrl = row.image;
                            var productName = row.name;
                            return '<img src="/' + imageUrl + '" alt="' + productName +
                                '" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">' +
                                productName;
                        },
                        editable: true
                    },
                    {
                        data: 'category',
                        name: 'category',
                        editable: true
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        editable: true
                    },
                    {
                        data: 'price',
                        name: 'price',
                        editable: true
                    },
                    {
                        data: 'supplier.name',
                        name: 'supplier.name',
                        editable: true
                    },
                    {
                        data: 'reorder.point',
                        name: 'reorder.point',
                        editable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            if (data === 'Active') {
                                return '<div class="badge bg-success">Active</div>';
                            } else if (data === 'Inactive') {
                                return '<div class="badge bg-danger">Inactive</div>';
                            } else if (data === 'Delisted') {
                                return '<div class="badge bg-secondary">Delisted</div>';
                            }
                            return data;
                        },
                        editable: true
                    },
                    {
                        data: null,
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            var addButton = '<a href="/admin/products" data-toggle="tooltip" data-placement="top" title="Add New Product" class="badge badge-success mr-2 addProduct"><i class="ri-add-line mr-0"></i></a>';
                            var editButton = '<a href="javascript:void(0)" data-id="' + full.id +
                                '" data-toggle="tooltip" data-placement="top" title="Edit" class="badge bg-primary mr-2 editSupplier"><i class="ri-eye-line mr-0"></i></a>';
                            var deleteButton = '<a href="javascript:void(0)" data-id="' + full.id +
                                '" data-toggle="tooltip" data-placement="top" title="Delete" class="badge bg-danger deleteSupplier"><i class="ri-delete-bin-line mr-0"></i></a>';

                            return '<div class="d-flex align-items-center list-action">' +
                                editButton +
                                deleteButton +
                                '</div>';
                        }
                    }
                ],
            });

            // $("#editProduct").click(function() {
            //     var $this = $(this);

            //     if ($this.hasClass('editing')) {
            //         var data = [];

            //         productTable.rows().every(function(rowIdx, tableLoop, rowLoop) {
            //             var rowData = this.data();
            //             var $row = $(this.node());

            //             rowData.name = $row.find('input[name="name"]').val();
            //             rowData.category = $row.find('select[name="category"]').val();
            //             rowData.unit = $row.find('select[name="unit"]').val();
            //             rowData.price = $row.find('input[name="price"]').val();
            //             rowData.status = $row.find('select[name="status"]').val();

            //             data.push(rowData);
            //         });

            //         $.ajax({
            //             url: "{{ route('products.updateTable') }}",
            //             method: "POST",
            //             data: {
            //                 _token: "{{ csrf_token() }}",
            //                 products: data
            //             },
            //             success: function(response) {
            //                 Swal.fire({
            //                     title: "Success!",
            //                     text: response.success,
            //                     icon: "success",
            //                     timer: 2000,
            //                     showConfirmButton: false
            //                 });
            //                 $this.removeClass('editing').text('Edit Table');
            //                 productTable.ajax.reload();
            //             },
            //             error: function(response) {
            //                 console.log('Error:', response);
            //                 Swal.fire({
            //                     title: "Oops!",
            //                     text: "Something went wrong!",
            //                     icon: "error",
            //                     timer: 2000,
            //                     showConfirmButton: false
            //                 });
            //             }
            //         });
            //     } else {
            //         $this.addClass('editing').text('Save Table');

            //         productTable.columns().every(function() {
            //             var column = this;
            //             column.nodes().to$().each(function() {
            //                 var cell = $(this);

            //                 if (column.index() !== 0 && column.index() !== 5) { 
            //                     if (column.index() === 2) { 
            //                         cell.html(`
            //                             <select name="category" class="form-control" onchange="toggleNewCategoryInput()">
            //                                 <option value="Beverages" ${cell.text() === 'Beverages' ? 'selected' : ''}>Beverages</option>
            //                                 <option value="Dry Goods" ${cell.text() === 'Dry Goods' ? 'selected' : ''}>Dry Goods</option>
            //                                 <option value="new">Create New Category</option> <!-- New option to create category -->
            //                             </select>
            //                             <input type="text" name="newCategory" class="form-control mt-2" 
            //                                    placeholder="Enter new category" style="display: none;"> <!-- Hidden input for new category -->
            //                         `);
            //                     } else if (column.index() === 3) { 
            //                         cell.html(`
            //                             <select name="unit" class="form-control">
            //                                 <option value="BXS" ${cell.text() === 'BXS' ? 'selected' : ''}>BXS</option>
            //                                 <option value="SCK" ${cell.text() === 'SCK' ? 'selected' : ''}>SCK</option>
            //                             </select>
            //                         `);
            //                     } else if (column.index() === 6) { 
            //                         cell.html(`
            //                             <select name="status" class="form-control">
            //                                 <option value="Active" ${cell.text() === 'Active' ? 'selected' : ''}>Active</option>
            //                                 <option value="Inactive" ${cell.text() === 'Inactive' ? 'selected' : ''}>Inactive</option>
            //                             </select>
            //                         `);
            //                     } else {
            //                         var columnName = column.dataSrc();
            //                         cell.html('<input type="text" name="' + columnName +
            //                             '" class="form-control" value="' + cell.text() +
            //                             '"/>');
            //                     }
            //                 }
            //             });
            //         });
            //     }
            // });

            $("#createProduct").click(function() {
                $('#product_id').val('');
                $('#productForm').trigger('reset');
                $('#modalHeading').html('Add Product');
                $('#productModal').modal('show');
            });

            $('#productSaveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Saving...');

                var formData = new FormData($("#productForm")[0]);

                // Handle new category input
                var newCategory = $('#newCategory').val();
                if (newCategory) {
                    formData.append('category', newCategory); 
                }

                $.ajax({
                    data: formData,
                    url: "{{ route('products.store') }}",
                    type: "POST",
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        $('#productForm').trigger('reset');
                        $('#productModal').modal('hide');
                        productTable.ajax.reload();
                        Swal.fire({
                            title: "Success!",
                            text: "Product data saved successfully!",
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(data) {
                        console.log('Error', data);
                        $('#productSaveBtn').html('Save');
                        Swal.fire({
                            title: "Oops!",
                            text: "Something went wrong!",
                            icon: "error",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });

            $('body').on('click', '.editProduct', function() {
            var product_id = $(this).data("id");
            $.get("{{ route('products.index') }}" + "/" + product_id + "/edit", function(data) {
                $("#modalHeading").html("Edit Product Details");
                $('#productModal').modal('show');
                $('#product_id').val(data.id);
                $('#name').val(data.name);
                $('#category').val(data.category); 
                $('#unit').val(data.unit); 
                $('#re-oder-point').val(data.re-oder-point);   
                $('#image').val(data.image);      
                $('#price').val(data.price);
                $('#supplier').val(data.supplier_id);  
            });
        });


        function toggleNewCategoryInput() {
            $('#newCategory').toggle(); 
        }

        $(document).on('click', '.deleteProduct', function() {
            var productId = $(this).data('id'); 

        
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
                        url: "{{ route('products.destroy', '') }}/" + productId, 
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" 
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Supplier has been deleted.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            productTable.ajax.reload(); 
                        },
                        error: function(response) {
                            Swal.fire({
                                title: 'Oops!',
                                text: 'Something went wrong!',
                                icon: 'error',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });    
    </script>
@endsection

