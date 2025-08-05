@extends('adminlte::page')

@section('title', 'Partner Management')

@section('content_header')
    <h1>Partner Management</h1>
@stop

@section('content')
    <x-adminlte-card title="Customer Management" icon="fas fa-lg fa-user">
        <p>Manage All Customer Data Here!</p>
        <a href="/" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h2>Customer Data</h2>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahCustomer">
                        <i class="fas fa-plus"></i> Add Customer
                    </button>
                    <button class="btn btn-info" id="btnRefresh">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>
        
            <!-- Alert container for AJAX responses -->
            <div id="alert-container"></div>

            <!-- Loading indicator -->
            <div id="loading" class="text-center" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="customerTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="10%">ID</th>
                            <th width="20%">Nama</th>
                            <th width="15%">Nomor Telepon</th>
                            <th width="10%">Role</th>
                            <th width="10%">Saldo Piutang</th>
                            <th width="10%">Tanggal Dibuat</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($customers) && count($customers) > 0)
                            @foreach($customers as $customer)
                                <tr id="customer-row-{{ $customer->id }}">
                                    <td>{{ $customer->id }}</td>
                                    <td class="customer-nama">{{ $customer->nama }}</td>
                                    <td class="customer-nomor">{{ $customer->nomor_telepon }}</td>
                                    <td class="customer-role">{{ $customer->role }}</td>
                                    <td class="customer-saldo">{{ $customer->saldo_piutang }}</td>
                                    <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-info btn-sm btn-view" 
                                                    data-id="{{ $customer->id }}" 
                                                    data-nama="{{ $customer->nama }}"
                                                    data-nomor="{{ $customer->nomor_telepon }}"
                                                    data-role="{{ $customer->role }}"
                                                    data-saldo="{{ $customer->saldo_piutang }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalViewCustomer"
                                                    title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm btn-edit" 
                                                    data-id="{{ $customer->id }}" 
                                                    data-nama="{{ $customer->nama }}"
                                                    data-nomor="{{ $customer->nomor_telepon }}"
                                                    data-role="{{ $customer->role }}"
                                                    data-saldo="{{ $customer->saldo_piutang }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalEditCustomer"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-delete" 
                                                    data-id="{{ $customer->id }}" 
                                                    data-nama="{{ $customer->nama }}"
                                                    data-nomor="{{ $customer->nomor_telepon }}"
                                                    data-role="{{ $customer->role }}"
                                                    data-saldo="{{ $customer->saldo_piutang }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalHapusCustomer"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="no-data-row">
                                <td colspan="7" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    Belum ada data customer.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </x-adminlte-card>

    <div class="modal fade" id="modalTambahCustomer" tabindex="-1" aria-labelledby="modalTambahCustomerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formTambahCustomer">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalTambahCustomerLabel">
                            <i class="fas fa-plus"></i> Tambah Customer
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_customer_tambah" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama_customer_tambah" class="form-control" required placeholder="Masukkan nama customer">
                            <label for="nomor_telepon_tambah" class="form-label">Nomor Telepon Customer <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon_tambah" class="form-control" required placeholder="Masukkan nomor telepon customer">
                            <label for="role_customer_tambah" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role_customer_tambah" class="form-select form-control" required>
                                <option value="pelanggan">Customer</option>
                                <option value="pemasok">Supplier</option>
                            </select>
                            <label for="saldo_piutang_tambah" class="form-label">Saldo Piutang <span class="text-danger">*</span></label>
                            <input type="number" name="saldo_piutang" id="saldo_piutang_tambah" class="form-control" required placeholder="Masukkan total saldo piutang">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btnSimpan">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalEditCustomer" tabindex="-1" aria-labelledby="modalEditCustomerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditCustomer">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="customer_id_edit" name="customer_id">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="modalEditCustomerLabel">
                            <i class="fas fa-edit"></i> Edit Customer Data
                        </h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_customer_edit" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama_customer_edit" class="form-control" required placeholder="Masukkan nama Customer">
                            <label for="nomor_telepon_edit" class="form-label">Nomor Telepon Customer <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon_edit" class="form-control" required placeholder="Masukkan nomor telepon customer">
                            <label for="role_customer_edit" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role_customer_edit" class="form-select form-control" required readonly>
                                <option value="pelanggan" selected>Customer</option>
                                <option value="pemasok">Supplier</option>
                            </select>
                            <label for="saldo_piutang_edit" class="form-label">Saldo Piutang <span class="text-danger">*</span></label>
                            <input type="number" name="saldo_piutang" id="saldo_piutang_edit" class="form-control" required placeholder="Masukkan total saldo piutang">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning" id="btnUpdate">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                            <i class="fas fa-save"></i> Update
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalHapusCustomer" tabindex="-1" aria-labelledby="modalHapusCustomerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalHapusCustomerLabel">
                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                    <h5>Apakah Anda yakin?</h5>
                    <p>Yakin ingin menghapus customer <strong id="customer-nama-hapus"></strong>?</p>
                    <p class="text-muted small">Data yang sudah dihapus tidak dapat dikembalikan!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" id="btnHapus">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                        <i class="fas fa-trash"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop


@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        $(document).ready(function() { 
            let currentCustomeriId = null;

            // CSRF token setup for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ========================================
            // UTILITY FUNCTIONS
            // ========================================

            // Show alert function
            function showAlert(message, type = 'success') {
                const alertClass = type === 'success' ? 'alert-success' : 
                                type === 'error' ? 'alert-danger' : 
                                type === 'warning' ? 'alert-warning' : 'alert-info';
                
                const icon = type === 'success' ? 'fas fa-check-circle' :
                            type === 'error' ? 'fas fa-exclamation-circle' :
                            type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';

                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="${icon} me-2"></i>${message}
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                $('#alert-container').html(alertHtml);
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 5000);

                // Scroll to alert
                $('html, body').animate({
                    scrollTop: $('#alert-container').offset().top - 100
                }, 500);
            }

            // Clear form validation
            function clearValidation(form) {
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('');
            }

            // Show validation errors
            function showValidationErrors(errors, form) {
                clearValidation(form);
                $.each(errors, function(field, messages) {
                    const input = form.find(`[name="${field}"]`);
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                });
            }

            // Set loading state
            function setLoadingState(button, loading = true) {
                const spinner = button.find('.spinner-border');
                const icon = button.find('i:not(.spinner-border)');
                
                if (loading) {
                    button.prop('disabled', true);
                    spinner.removeClass('d-none');
                    icon.addClass('d-none');
                } else {
                    button.prop('disabled', false);
                    spinner.addClass('d-none');
                    icon.removeClass('d-none');
                }
            }

            // Format date
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
            // ========================================

            function addRowToTable(customer) {
                // Remove "no data" row if exists
                $('#no-data-row').remove();

                const newRow = `
                    <tr id="customer-row-${customer.id}">
                        <td>${customer.id}</td>
                        <td class="customer-nama">${customer.nama}</td>
                        <td class="customer-nomor">${ customer.nomor_telepon }</td>
                        <td class="customer-role">${ customer.role }</td>
                        <td class="customer-saldo">${ customer.saldo_piutang }</td>
                        <td>${formatDate(customer.created_at)}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm btn-view" 
                                        data-id="${customer.id}" 
                                        data-nama="${customer.nama}"
                                        data-nomor="${customer.nomor_telepon }"
                                                    data-role="${customer.role }"
                                                    data-saldo="${customer.saldo_piutang }"
                                        data-toggle="modal" 
                                        data-target="#modalViewCustomer"
                                        title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                 <button class="btn btn-warning btn-sm btn-edit" 
                                                data-id="${customer.id }" 
                                                data-nama="${customer.nama }"
                                                data-nomor="${customer.nomor_telepon }"
                                                data-role="${customer.role }"
                                                data-saldo="${customer.saldo_piutang }"
                                                data-toggle="modal" 
                                                data-target="#modalEditCustomer"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                <button class="btn btn-danger btn-sm btn-delete" 
                                        data-id="${customer.id}" 
                                        data-nama="${customer.nama}"
                                        data-nomor="${customer.nomor_telepon }"
                                        data-role="${customer.role }"
                                        data-saldo="${customer.saldo_piutang }"
                                        data-toggle="modal" 
                                        data-target="#modalHapusCustomer"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                $('#customerTable tbody').append(newRow);
            }

            // Update row in table
            function updateRowInTable(mitra) {
                const row = $(`#customer-row-${customer.id}`);
                row.find('.customer-nama').text(customer.nama);
                row.find('.customer-nomor').text(customer.nomor_telepon);
                row.find('.customer-role').text(customer.role);
                row.find('.customer-saldo').text(customer.saldo_piutang);
                row.find('.btn-edit').attr('data-nama', customer.nama, 
                                            'data-nomor', customer.nomor_telepon,
                                            'data-role', customer.role,
                                            'data-saldo', customer.saldo_piutang);
                row.find('.btn-view').attr('data-nama', customer.nama, 
                                            'data-nomor', customer.nomor_telepon,
                                            'data-role', customer.role,
                                            'data-saldo', customer.saldo_piutang);
                row.find('.btn-delete').attr('data-nama', customer.nama, 
                                            'data-nomor', customer.nomor_telepon,
                                            'data-role', customer.role,
                                            'data-saldo', customer.saldo_piutang);
            }

            // Remove row from table
            function removeRowFromTable(id) {
                $(`#customer-row-${id}`).fadeOut(300, function() {
                    $(this).remove();
                    
                    // Show "no data" message if table is empty
                    if ($('#customerTable tbody tr').length === 0) {
                        const noDataRow = `
                            <tr id="no-data-row">
                                <td colspan="4" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    Belum ada data kategori
                                </td>
                            </tr>
                        `;
                        $('#customerTable tbody').append(noDataRow);
                    }
                });
            }


            $('#formTambahCustomer').on('submit', function(e) {
                e.preventDefault();
                console.log('Form submission started');
                
                const form = $(this);
                const submitBtn = $('#btnSimpan');
                const formData = new FormData(this);
                
                setLoadingState(submitBtn, true);
                clearValidation(form);
                
                $.ajax({
                    url: '{{ route("customer.store") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Success response:', response);
                        if (response.success) {
                            showAlert(response.message, 'success');
                            setLoadingState(submitBtn, false);
                            addRowToTable(response.data);

                            form[0].reset();
                            
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahCustomer'));
                            if (modal) {
                                modal.hide();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            showValidationErrors(errors, form);
                        } else {
                            const message = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                            showAlert(message, 'error');
                        }
                    },
                    complete: function() {
                        setLoadingState(submitBtn, false);
                    }
                });
            });

            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const nomorTelepon = $(this).data('nomor');
                const role = $(this).data('role');
                const saldoPiutang = $(this).data('saldo');
                
                currentCustomerId = id;
                $('#customer_id_edit').val(id);
                $('#nama_customer_edit').val(nama);
                $('#nomor_telepon_edit').val(nomorTelepon);
                $('#role_kategori_edit').val(role);
                $('#saldo_piutang_edit').val(saldoPiutang);
                clearValidation($('#formEditCustomer'));
             });

        // UPDATE - Update kategori
            $('#formEditCustomer').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const submitBtn = $('#btnUpdate');
                const formData = new FormData(this);
                
                setLoadingState(submitBtn, true);
                clearValidation(form);
                
                $.ajax({
                    url: `{{ url('customer') }}/${currentCustomerId}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        if (response.success) {
                            setLoadingState(submitBtn, false);
                            showAlert(response.message, 'success');
                            updateRowInTable(response.data);
                            console.log('Update response:', response.data);
                            
                            
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditCustomer'));
                            if (modal) {
                                modal.hide();
                            }
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            showValidationErrors(errors, form);
                        } else {
                            const message = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                            showAlert(message, 'error');
                        }
                    },
                    complete: function() {
                        setLoadingState(submitBtn, false);
                    }
                });
            });


            // DELETE - Delete button click
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                
                currentCustomerId = id;
                $('#customer-nama-hapus').text(nama);
            });

            // DELETE - Delete kategori
            $('#btnHapus').on('click', function() {
                const submitBtn = $(this);
                
                setLoadingState(submitBtn, true);
                
                $.ajax({
                    url: `{{ url('customer') }}/${currentCustomerId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            setLoadingState(submitBtn, false);
                            showAlert(response.message, 'success');
                            removeRowFromTable(currentCustomerId);
                            
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('modalHapusCustomer'));
                            if (modal) {
                                modal.hide();
                            }
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                        showAlert(message, 'error');
                    },
                    complete: function() {
                        setLoadingState(submitBtn, false);
                    }
                });
            });
        });            
    </script>
@stop