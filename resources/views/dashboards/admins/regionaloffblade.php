@extends('layouts.app')
@section('title', 'OSave | Reginal Office List')

@section('content')


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                <div>
                    <h4 class="">Reginal Office List</h4>

                </div>
                <a href="javascript:void(0)" id="createReginaloff" class="btn btn-primary add-list"><i
                        class="las la-plus mr-3"></i>Add Reginal Office</a>
            </div>
        </div>
        <div class="col-md-12">
            <div class="ttl-amt py-2 px-3 d-flex justify-content-end mt-2"></div>
        </div>
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3 ">
                <table class="table mb-0 regionialoff-table">
                    <thead>
                        <tr class="ligth ligth-data">
                            <th>No</th>
                            <th>Region Name</th>
                            <th>Address</th>
                            <th>Location</th>
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

<div class="modal fade" id="regionialoffModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="regionaloffForm" name="regionaloffForm" class="form-horizontal">
                    <input type="hidden" name="regionaloff_id" id="regionaloff_id">

                    <div class="container">
                        <div class="form-group row">
                            <label for="roname" class="col-sm-4 col-form-label text-osave">Region Name*</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="roname" name="roname" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="roaddress" class="col-sm-4 col-form-label text-osave">Region Address*</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="roaddress" name="roaddress" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="rolocation" class="col-sm-4 col-form-label text-osave">Location*</label>
                            <div class="col-sm-8">
                                <input type="rolocation" class="form-control" id="rolocation" saddress="rolocation" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="regionaloffSaveBtn" value="create">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(function() {
        var regionaloffTable = $('.regionaloff-table').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            lengthMenu: [10, 20, 50, 100],
            ajax: {
                url: "{{ route('regionaloff.getRegionaloff') }}",
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
                    data: 'roname',
                    name: 'roname'
                },
               
                {
                    data: 'roaddress',
                    name: 'roaddress'
                },
                {
                    data: 'roloaction',
                    name: 'roloaction'
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
                            '" data-toggle="tooltip" data-placement="top" title="Edit" class="badge bg-primary mr-2 editRegionaloff"><i class="ri-eye-line mr-0"></i></a>';
                        var deleteButton = '<a href="javascript:void(0)" data-id="' + full.id +
                            '" data-toggle="tooltip" data-placement="top" title="Delete" class="badge bg-danger deleteRegionaloff"><i class="ri-delete-bin-line mr-0"></i></a>';

                        return '<div class="d-flex align-items-center list-action">' +
                            editButton +
                            deleteButton +

                            '</div>';
                    }
                }

            ],

        });

        $("#createRegionaloff").click(function() {
            $('#regionaloff_id').val('');
            $('#regionaloffForm').trigger('reset');
            $('#modalHeading').html('Add Regional Office');
            $('#regionoffmodal').modal('show');
        });

        $('#regionaloffSaveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Save');

            $.ajax({
                data: $("#regionaloffForm").serialize(),
                url: "{{ route('regionaloff.regionaloff') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#regionaloffForm').trigger('reset');
                    $('#regionaloffModal').modal('hide');
                    regionaloffTable.ajax.reload();
                    Swal.fire({
                        title: "Success!",
                        text: "Regional Office data saved successfully!",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(data) {
                    console.log('Error', data);
                    $('#regionaloffSaveBtn').html('Save');
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

        $('body').on('click', '.editRegionaloff', function() {
            var regionaloff_id = $(this).data("id");
            $.get("{{ route('regionaloff.index') }}" + "/" + regionaloff_id + "/edit", function(data) {
                $("#modalHeading").html("Reginal Office Details");
                $('#regionaloffModal').modal('show');
                $('#regionaloff_id').val(data.id);
                $('#regionaloff_no').val(data.regionaloff_no);
                $('#roname').val(data.name);
                $('#roaddress').val(data.address);
                $('#roloacation').val(data.roloacation);
            });
        });

    });


    $(document).on('click', '.deleteRegionalOff', function() {
        var regionaloff_id = $(this).data("id");


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
                    url: "{{ route('regionaloff.destroy', '') }}/" + regionaloffId,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Regional Office has been deleted.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                       regionaloffTable.ajax.reload();
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