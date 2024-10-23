@extends('layouts.app')
@section('title', 'OSave | Store List')

@section('content')


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                <div>
                    <h4 class="">Store List</h4>

                </div>
                <a href="javascript:void(0)" id="createStore" class="btn btn-primary add-list"><i
                        class="las la-plus mr-3"></i>Add Store</a>
            </div>
        </div>
        <div class="col-md-12">
            <div class="ttl-amt py-2 px-3 d-flex justify-content-end mt-2"></div>
        </div>
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3 ">
                <table class="table mb-0 store-table">
                    <thead>
                        <tr class="ligth ligth-data">
                            <th>No</th>
                            <th>Store Name</th>
                            <th>Address</th>
                            <th>Area</th>
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

<div class="modal fade" id="storeModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="storeForm" name="storeForm" class="form-horizontal">
                    <input type="hidden" name="store_id" id="store_id">

                    <div class="container">
                        <div class="form-group row">
                            <label for="sname" class="col-sm-4 col-form-label text-osave">Store Name*</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="sname" name="sname" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="saddress" class="col-sm-4 col-form-label text-osave">Store Address*</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="saddress" name="saddress" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="area" class="col-sm-4 col-form-label text-osave">Store Area*</label>
                            <div class="col-sm-8">
                                <input type="area" class="form-control" id="area" saddress="area" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="storeSaveBtn" value="create">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(function() {
        var storeTable = $('.store-table').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            lengthMenu: [10, 20, 50, 100],
            ajax: {
                url: "{{ route('store.getStore') }}",
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
                    data: 'sname',
                    name: 'sname'
                },
               
                {
                    data: 'saddress',
                    name: 'saddress'
                },
                {
                    data: 'area',
                    name: 'area'
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
                    }
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var editButton = '<a href="javascript:void(0)" data-id="' + full.id +
                            '" data-toggle="tooltip" data-placement="top" title="Edit" class="badge bg-primary mr-2 editStore"><i class="ri-eye-line mr-0"></i></a>';
                        var deleteButton = '<a href="javascript:void(0)" data-id="' + full.id +
                            '" data-toggle="tooltip" data-placement="top" title="Delete" class="badge bg-danger deleteStore"><i class="ri-delete-bin-line mr-0"></i></a>';

                        return '<div class="d-flex align-items-center list-action">' +
                            editButton +
                            deleteButton +

                            '</div>';
                    }
                }

            ],

        });

        $("#createStore").click(function() {
            $('#store_id').val('');
            $('#storeForm').trigger('reset');
            $('#modalHeading').html('Add Store');
            $('#storeModal').modal('show');
        });

        $('#storeSaveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Save');

            $.ajax({
                data: $("#storeForm").serialize(),
                url: "{{ route('store.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#storeForm').trigger('reset');
                    $('#storeModal').modal('hide');
                    storeTable.ajax.reload();
                    Swal.fire({
                        title: "Success!",
                        text: "Store data saved successfully!",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(data) {
                    console.log('Error', data);
                    $('#storeSaveBtn').html('Save');
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

        $('body').on('click', '.editStore', function() {
            var store_id = $(this).data("id");
            $.get("{{ route('store.index') }}" + "/" + store_id + "/edit", function(data) {
                $("#modalHeading").html("Store Details");
                $('#storeModal').modal('show');
                $('#store_id').val(data.id);
                $('#store_no').val(data.store_no);
                $('#sname').val(data.name);
                $('#saddress').val(data.address);
                $('#area').val(data.area);
            });
        });

    });


    $(document).on('click', '.deleteStore', function() {
        var store_id = $(this).data("id");


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
                    url: "{{ route('store.destroy', '') }}/" + storeId,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Store has been deleted.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        storeTable.ajax.reload();
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