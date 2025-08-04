@extends('adminlte::page')

@section('title', 'Category Management')

@section('content_header')
    <h1>Product Management</h1>
@stop

@section('content')
    <x-adminlte-card title="Product Management" icon="fas fa-lg fa-box-open">
        <p>Manage All Product Here!</p>
        <a href="/" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h2>Produk</h2>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahProduk">
                        <i class="fas fa-plus"></i> Tambah Produk
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
                            <th width="4%">ID</th>
                            <th width="15%">Nama Produk</th>
                            <th width="10%">Kategori</th>
                            <th width="10%">Harga Beli</th>
                            <th width="10%">Harga Jual</th>
                            <th width="6%">Stock</th>
                            <th width="5%">Status Jual</th>
                            <th width="5%">Status Restock</th>
                            <th width="4%">Tanggal Dibuat</th>
                            <th width="6%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($produks) && count($produks) > 0)
                            @foreach($produks as $produk)
                                <tr id="product-row-{{ $produk->id }}">
                                    <td>{{ $produk->id }}</td>
                                    <td class="produk-nama">{{ $produk->nama_produk }}</td>
                                    <td>{{ $produk->kategori->nama_kategori ?? 'Tidak ada kategori' }}</td>
                                    <td>{{ $produk->harga_beli }}</td>
                                    <td>{{ $produk->harga_jual }}</td>
                                    <td>{{ $produk->stok }}</td>
                                    <td>
                                        @if ($produk->sellable)
                                            <span class="badge bg-success">Bisa Dijual</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Bisa Dijual</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($produk->restock_status)
                                            <span class="badge bg-danger">Perlu Restock</span>
                                        @else
                                            <span class="badge bg-success">Tidak Perlu Restock</span>
                                        @endif</td>
                                    <td>{{ $produk->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-warning btn-sm btn-edit"
                                                data-id="{{ $produk->id }}"
                                                data-nama="{{ $produk->nama_produk}}"
                                                data-kategori="{{ $produk->kategori_id }}"
                                                data-harga-beli="{{ $produk->harga_beli }}"
                                                data-harga-jual="{{ $produk->harga_jual }}"
                                                data-stok="{{ $produk->stok }}"
                                                data-sellable="{{ $produk->sellable }}"
                                                data-restock-status="{{ $produk->restock_status }}"
                                                data-toggle="modal"
                                                data-target="#modalEditProduk"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-delete" 
                                                    data-id="{{ $produk->id }}" 
                                                    data-nama="{{ $produk->nama_produk }}"
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
                                <td colspan="10" class="text-center text-muted">
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
    <div class="modal fade" id="modalTambahProduk" tabindex="-1" aria-labelledby="modalTambahProdukLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formTambahProduk">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalTambahKategoriLabel">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div>
                                <label for="nama_kategori_tambah" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="nama_produk" id="nama_kategori_tambah" class="form-control" required placeholder="Masukkan nama produk">
                            </div>
                            <div>
                                <label for="nama_kategori_tambah" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" id="kategori_id_tambah" class="form-select form-control" required>
                                <option value="1">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                            </div>
                            <div>
                                <label for="harga_beli_tambah" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                <input type="number" name="harga_beli" id="harga_beli_tambah" class="form-control" required placeholder="Masukkan harga beli">
                            </div>
                            <div>
                                <label for="harga_jual_tambah" class="form-label">Harga Jual   <span class="text-danger">*</span></label>
                                <input type="number" name="harga_jual" id="harga_jual_tambah" class="form-control" required placeholder="Masukkan harga jual">
                            </div>
                            <div>
                                <label for="stok_tambah" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" name="stok" id="stok_tambah" class="form-control" required placeholder="Masukkan jumlah stock">
                            </div>
                            <div>
                                <label for="status_jual_tambah" class="form-label">Status Jual <span class="text-danger">*</span></label>
                                <select name="sellable" id="status_jual_tambah" class="form-select form-control" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                            </div>
                            <div>
                                <label for="status_restock_tambah" class="form-label">Status Restock <span class="text-danger">*</span></label>
                                <select name="restock_status" id="status_restock_tambah" class="form-select form-control" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                            </div>
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

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modalEditProduk" tabindex="-1" aria-labelledby="modalEditKategoriLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditProduk">
                    @csrf
                    <input type="hidden" id="produk_id_edit">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="modalEditProdukLabel">
                            <i class="fas fa-edit"></i> Edit Data Produk
                        </h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                                <label for="nama_produk_edit" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="nama_produk" id="nama_produk_edit" class="form-control" required placeholder="Masukkan nama produk">
                            </div>
                            <div>
                                <label for="nama_kategori_edit" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" id="kategori_id_edit" class="form-select form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                            </div>
                            <div>
                                <label for="harga_beli_edit" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                <input type="number" name="harga_beli" id="harga_beli_edit" class="form-control" required placeholder="Masukkan harga beli">
                            </div>
                            <div>
                                <label for="harga_jual_edit" class="form-label">Harga Jual   <span class="text-danger">*</span></label>
                                <input type="number" name="harga_jual" id="harga_jual_edit" class="form-control" required placeholder="Masukkan harga jual">
                            </div>
                            <div>
                                <label for="stok_edit" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" name="stok" id="stok_edit" class="form-control" required placeholder="Masukkan jumlah stock">
                            </div>
                            <div>
                                <label for="status_jual_edit" class="form-label">Status Jual <span class="text-danger">*</span></label>
                                <select name="sellable" id="status_jual_edit" class="form-select form-control" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                            </div>
                            <div>
                                <label for="status_restock_edit" class="form-label">Status Restock <span class="text-danger">*</span></label>
                                <select name="restock_status" id="status_restock_edit" class="form-select form-control" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                            </div>
                        <div class="invalid-feedback"></div>
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
    let currentProductId = null;

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
    $('#formTambahProduk').on('submit', function(e) {
        e.preventDefault();
        console.log('Form submission started');
        
        const form = $(this);
        const submitBtn = $('#btnSimpan');
        const formData = new FormData(this);
        
        setLoadingState(submitBtn, true);
        clearValidation(form);
        
        $.ajax({
            url: '{{ route("product.store") }}',
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahProduk'));
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

     $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama'); 
        const kategori = $(this).data('kategori');
        const hargaBeli = $(this).data('harga-beli');
        const hargaJual = $(this).data('harga-jual');
        const stok = $(this).data('stok');
        const sellable = $(this).data('sellable');
        const restockStatus = $(this).data('restock-status');
        if (!id) {
            console.error('No ID found on edit button');
            return;
        }
        
        currentProductId = id;
        
        // Populate form fields
        $('#produk_id_edit').val(id);
        $('#nama_produk_edit').val(nama);
        $('#kategori_id_edit').val(kategori);
        $('#harga_beli_edit').val(hargaBeli);
        $('#harga_jual_edit').val(hargaJual);
        $('#stok_edit').val(stok);
        $('#status_jual_edit').val(sellable);
        $('#status_restock_edit').val(restockStatus);
        clearValidation($('#formEditProduk'));
    });
    
    // UPDATE - Update kategori
    $('#formEditProduk').on('submit', function(e) {
        e.preventDefault();
        console.log('Edit form submission started');
        
        const form = $('#formEditProduk');   
        const submitBtn = $('#btnUpdate');
        const formData = new FormData(form[0]); 

        setLoadingState(submitBtn, true);
        clearValidation(form);
        
        $.ajax({
            url: `{{ url('product') }}/${currentProductId}`,
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditProduk'));
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
            url: `{{ url('category') }}/${currentProductId}`,
            type: 'DELETE',
            success: function(response) {
                if (response.success) {
                    setLoadingState(submitBtn, false);
                    showAlert(response.message, 'success');
                    removeRowFromTable(currentProductId);
                    
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