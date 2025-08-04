@extends('adminlte::page')

@section('title', 'Category Management')

@section('content_header')
    <h1>Product Management</h1>
@stop

@section('content')
    <x-adminlte-card title="Product Categories" icon="fas fa-lg fa-boxes">
        <p>Manage All Product Categories Here!</p>
        <a href="/" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h2>Kategori Produk</h2>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahKategori">
                        <i class="fas fa-plus"></i> Tambah Kategori
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
                <table class="table table-bordered table-striped" id="kategoriTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="10%">ID</th>
                            <th width="60%">Nama Kategori</th>
                            <th width="20%">Tanggal Dibuat</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($kategoris) && count($kategoris) > 0)
                            @foreach($kategoris as $kategori)
                                <tr id="kategori-row-{{ $kategori->id }}">
                                    <td>{{ $kategori->id }}</td>
                                    <td class="kategori-nama">{{ $kategori->nama_kategori }}</td>
                                    <td>{{ $kategori->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-info btn-sm btn-view" 
                                                    data-id="{{ $kategori->id }}" 
                                                    data-nama="{{ $kategori->nama_kategori }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalViewKategori"
                                                    title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm btn-edit" 
                                                    data-id="{{ $kategori->id }}" 
                                                    data-nama="{{ $kategori->nama_kategori }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalEditKategori"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-delete" 
                                                    data-id="{{ $kategori->id }}" 
                                                    data-nama="{{ $kategori->nama_kategori }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalHapusKategori"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="no-data-row">
                                <td colspan="4" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    Belum ada data kategori
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </x-adminlte-card>

    <!-- MODAL CREATE -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formTambahKategori">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalTambahKategoriLabel">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kategori_tambah" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" id="nama_kategori_tambah" class="form-control" required placeholder="Masukkan nama kategori">
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

    <!-- MODAL VIEW -->
    <div class="modal fade" id="modalViewKategori" tabindex="-1" aria-labelledby="modalViewKategoriLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalViewKategoriLabel">
                        <i class="fas fa-eye"></i> Detail Kategori
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>ID:</strong></td>
                            <td id="view-id">-</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Kategori:</strong></td>
                            <td id="view-nama">-</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Dibuat:</strong></td>
                            <td id="view-created">-</td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir Diupdate:</strong></td>
                            <td id="view-updated">-</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modalEditKategori" tabindex="-1" aria-labelledby="modalEditKategoriLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditKategori">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="kategori_id_edit" name="kategori_id">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="modalEditKategoriLabel">
                            <i class="fas fa-edit"></i> Edit Kategori
                        </h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kategori_edit" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" id="nama_kategori_edit" class="form-control" required placeholder="Masukkan nama kategori">
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

    <!-- MODAL DELETE -->
    <div class="modal fade" id="modalHapusKategori" tabindex="-1" aria-labelledby="modalHapusKategoriLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalHapusKategoriLabel">
                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                    <h5>Apakah Anda yakin?</h5>
                    <p>Yakin ingin menghapus kategori <strong id="kategori-nama-hapus"></strong>?</p>
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
    <style>
        .btn-group .btn {
            margin-right: 2px;
        }
        .table th {
            vertical-align: middle;
        }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        #loading {
            margin: 20px 0;
        }
        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }
        .modal-footer {
            border-top: 1px solid #dee2e6;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    let currentKategoriId = null;

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
    // TABLE MANAGEMENT FUNCTIONS
    // ========================================

    // Add new row to table
    function addRowToTable(kategori) {
        // Remove "no data" row if exists
        $('#no-data-row').remove();

        const newRow = `
            <tr id="kategori-row-${kategori.id}">
                <td>${kategori.id}</td>
                <td class="kategori-nama">${kategori.nama_kategori}</td>
                <td>${formatDate(kategori.created_at)}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-info btn-sm btn-view" 
                                data-id="${kategori.id}" 
                                data-nama="${kategori.nama_kategori}"
                                data-toggle="modal" 
                                data-target="#modalViewKategori"
                                title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-sm btn-edit" 
                                data-id="${kategori.id}" 
                                data-nama="${kategori.nama_kategori}"
                                data-toggle="modal" 
                                data-target="#modalEditKategori"
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-delete" 
                                data-id="${kategori.id}" 
                                data-nama="${kategori.nama_kategori}"
                                data-toggle="modal" 
                                data-target="#modalHapusKategori"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        $('#kategoriTable tbody').append(newRow);
    }

    // Update row in table
    function updateRowInTable(kategori) {
        const row = $(`#kategori-row-${kategori.id}`);
        row.find('.kategori-nama').text(kategori.nama_kategori);
        row.find('.btn-edit').attr('data-nama', kategori.nama_kategori);
        row.find('.btn-view').attr('data-nama', kategori.nama_kategori);
        row.find('.btn-delete').attr('data-nama', kategori.nama_kategori);
    }

    // Remove row from table
    function removeRowFromTable(id) {
        $(`#kategori-row-${id}`).fadeOut(300, function() {
            $(this).remove();
            
            // Show "no data" message if table is empty
            if ($('#kategoriTable tbody tr').length === 0) {
                const noDataRow = `
                    <tr id="no-data-row">
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                            Belum ada data kategori
                        </td>
                    </tr>
                `;
                $('#kategoriTable tbody').append(noDataRow);
            }
        });
    }

    // ========================================
    // CRUD OPERATIONS
    // ========================================

    // CREATE - Store kategori
    $('#formTambahKategori').on('submit', function(e) {
        e.preventDefault();
        console.log('Form submission started');
        
        const form = $(this);
        const submitBtn = $('#btnSimpan');
        const formData = new FormData(this);
        
        setLoadingState(submitBtn, true);
        clearValidation(form);
        
        $.ajax({
            url: '{{ route("category.store") }}',
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahKategori'));
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

    // READ - View kategori detail
    $(document).on('click', '.btn-view', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: `{{ url('category') }}/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const kategori = response.data;
                    $('#view-id').text(kategori.id);
                    $('#view-nama').text(kategori.nama_kategori);
                    $('#view-created').text(formatDate(kategori.created_at));
                    $('#view-updated').text(formatDate(kategori.updated_at));
                }
            },
            error: function(xhr) {
                showAlert('Error loading data', 'error');
            }
        });
    });

    // UPDATE - Edit button click
    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        currentKategoriId = id;
        $('#kategori_id_edit').val(id);
        $('#nama_kategori_edit').val(nama);
        clearValidation($('#formEditKategori'));
    });

    // UPDATE - Update kategori
    $('#formEditKategori').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#btnUpdate');
        const formData = new FormData(this);
        
        setLoadingState(submitBtn, true);
        clearValidation(form);
        
        $.ajax({
            url: `{{ url('category') }}/${currentKategoriId}`,
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
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditKategori'));
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
        
        currentKategoriId = id;
        $('#kategori-nama-hapus').text(nama);
    });

    // DELETE - Delete kategori
    $('#btnHapus').on('click', function() {
        const submitBtn = $(this);
        
        setLoadingState(submitBtn, true);
        
        $.ajax({
            url: `{{ url('category') }}/${currentKategoriId}`,
            type: 'DELETE',
            success: function(response) {
                if (response.success) {
                    setLoadingState(submitBtn, false);
                    showAlert(response.message, 'success');
                    removeRowFromTable(currentKategoriId);
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalHapusKategori'));
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

    // ========================================
    // ADDITIONAL FEATURES
    // ========================================

    // Refresh button
    $('#btnRefresh').on('click', function() {
        const btn = $(this);
        const originalHtml = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');
        btn.prop('disabled', true);
        
        // Reload page after 1 second
        setTimeout(() => {
            location.reload();
        }, 1000);
    });

    // ========================================
    // MODAL EVENT HANDLERS
    // ========================================

    // Reset forms when modals are hidden
    $('#modalTambahKategori').on('hidden.modal', function() {
        $('#formTambahKategori')[0].reset();
        clearValidation($('#formTambahKategori'));
    });

    $('#modalEditKategori').on('hidden.modal', function() {
        clearValidation($('#formEditKategori'));
        currentKategoriId = null;
    });

    $('#modalHapusKategori').on('hidden.modal', function() {
        currentKategoriId = null;
    });

    // Auto focus on input when modal is shown
    $('#modalTambahKategori').on('shown.modal', function() {
        $('#nama_kategori_tambah').focus();
    });

    $('#modalEditKategori').on('shown.modal', function() {
        $('#nama_kategori_edit').focus().select();
    });

    // ========================================
    // KEYBOARD SHORTCUTS
    // ========================================

    // Ctrl + N = New Category
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            $('#modalTambahKategori').modal('show');
        }
    });

    // Enter key in modals
    $('#modalTambahKategori, #modalEditKategori').on('keypress', 'input', function(e) {
        if (e.which === 13) { // Enter key
            $(this).closest('form').submit();
        }
    });

    console.log('Category CRUD system initialized successfully');
});
</script>
@stop