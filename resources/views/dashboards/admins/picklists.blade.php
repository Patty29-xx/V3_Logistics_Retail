@extends('layouts.app')
@section('title', 'OSave | Pick List')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div>
                        <h4 class="">Pick List</h4>
                    </div>
                    <a href="/admin/picklistForm" class="btn btn-primary add-list"><i
                        class="las la-plus mr-3"></i>Add Pick List</a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="ttl-amt py-2 px-3 d-flex justify-content-end mt-2">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 picklist-table">
                        <thead>
                            <tr class="ligth ligth-data">
                                <th>No.</th>
                                <th>Created Date</th>
                                <th>Estimated Delivery Date</th>
                                <th>Pick List No.</th>
                                <th>Order From</th>
                                <th>Deliver To</th>
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

    <div class="modal fade" id="viewPicklist" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="viewPicklistLabel">Picklist Information</h6>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="ligth ligth-data">
                                    <th>Product Picklist#</th>
                                    <th>Created Date</th>
                                    <th>Supplier</th>
                                    <th>Supplier Address</th>
                                    <th>Warehouse Address</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                <tr>
                                    <td id="ppl"></td>
                                    <td id="pcd"></td>
                                    <td id="ps"></td>
                                    <td id="ps"></td>
                                    <td id="pw"></td>
                                    <td id="pstat"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function viewPicklist(picklistId) {
            $.ajax({
                url: '/admin/picklistView',
                type: 'GET',
                data: {
                    id: picklistId
                },
                success: function(response) {
                    window.location.href = '/admin/picklistView?id=' + picklistId;
                },
                error: function(xhr) {
                    console.error('Error fetching picklist details:', xhr);
                }
            });
        }

        $(function() {
            var picklistTable = $('.picklist-table').DataTable({
                processing: true,
                responsive: true,
                autoWidth: false,
                lengthMenu: [10, 20, 50, 100],
                ajax: {
                    url: "{{ route('picklists.getPicklists') }}",
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
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            var date = new Date(data);
                            var options = {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            };
                            return date.toLocaleDateString('en-GB', options);
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            var date = new Date(data);
                            var options = {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            };
                            return date.toLocaleDateString('en-GB', options);
                        }
                    },

                    {
                        data: 'picklist_no',
                        name: 'picklist_no'
                    },
                    {
                        data: 'order_from',
                        name: 'order_from'
                    },
                    {
                        data: 'address.for',
                        name: 'address.for'
                    },
                    {
                        data: null,
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            var viewButton = '<a href="javascript:void(0)" data-id="' + full.id +
                                '" onclick="viewPicklist(' + full.id +
                                ')" data-toggle="tooltip" data-placement="top" title="View" class="badge badge-info mr-2"><i class="ri-eye-line mr-0"></i></a>';

                            var editButton = '<a href="javascript:void(0)" data-id="' + full.id +
                                '" data-toggle="tooltip" data-placement="top" title="Edit" class="badge bg-success mr-2 editPicklist"><i class="ri-pencil-line mr-0"></i></a>';
                            var deleteButton = '<a href="javascript:void(0)" data-id="' + full.id +
                                '" data-toggle="tooltip" data-placement="top" title="Delete" class="badge bg-danger deletePicklist"><i class="ri-delete-bin-line mr-0"></i></a>';

                            return '<div class="d-flex align-items-center list-action">' +
                                viewButton +
                                deleteButton +
                                '</div>';
                        }
                    }
                ],
            });

            $("#createPicklist").click(function() {
                $('#picklist_id').val('');
                $('#picklistForm').trigger('reset');
                $('#modalHeading').html('Add Picklist');
                $('#picklistModal').modal('show');
            });

            $('#picklistSaveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Save');

                $.ajax({
                    data: $("#picklistForm").serialize(),
                    url: "{{ route('picklists.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#picklistForm').trigger('reset');
                        $('#picklistModal').modal('hide');
                        picklistTable.ajax.reload();
                        Swal.fire({
                            title: "Success!",
                            text: "Picklist data saved successfully!",
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(data) {
                        console.log('Error', data);
                        $('#picklistSaveBtn').html('Save');
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

            $('body').on('click', '.deletePicklist', function() {
                var picklist_id = $(this).data("id");
                Swal.fire({
                    title: 'Confirm Delete',
                    text: "Are you sure you want to delete this picklist?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('picklists.store') }}" + '/' + picklist_id,
                            success: function(data) {
                                picklistTable.ajax.reload();
                                Swal.fire(
                                    'Deleted!',
                                    'Picklist data has been deleted.',
                                    'success'
                                );
                            },
                            error: function(data) {
                                Swal.fire(
                                    'Error!',
                                    'Picklist cannot be deleted.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('body').on('click', '.editPicklist', function() {
                var picklist_id = $(this).data("id");
                $.get("{{ route('picklists.index') }}" + "/" + picklist_id + "/edit", function(data) {
                    $("#modalHeading").html("Edit Picklist");
                    $('#picklistModal').modal('show');
                    $('#picklist_id').val(data.id);
                    $('#customer').val(data.customer);
                    $('#email').val(data.email);
                    $('#phone_number').val(data.phone_number);
                    $('#address').val(data.address);
                    $('#status').val(data.status);
                });
            });
        });
    </script>

@endsection

