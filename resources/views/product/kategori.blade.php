@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Product Management</h1>
@stop

@section('content')
    <x-adminlte-card title="Product Categories" icon="fas fa-lg fa-boxes">
        <p>Manage All Product Categories Here!</p>
        
        <a href="javascript:window.history.back()" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="container">
            <h2>Kategori Produk</h2>
            <!-- FIXED: Changed data-toggle to data-bs-toggle for Bootstrap 5 -->
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#modalTambahKategori">Tambah Kategori</button>
        
            <!-- Alert container for AJAX responses -->
            <div id="alert-container"></div>
        
            <table class="table table-bordered" id="kategoriTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($kategoris) && count($kategoris) > 0)
                        @foreach($kategoris as $kategori)
                            <tr id="kategori-row-{{ $kategori->id }}">
                                <td>{{ $kategori->id }}</td>
                                <td class="kategori-nama">{{ $kategori->nama_kategori }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm btn-edit" 
                                            data-id="{{ $kategori->id }}" 
                                            data-nama="{{ $kategori->nama_kategori }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditKategori">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-delete" 
                                            data-id="{{ $kategori->id }}" 
                                            data-nama="{{ $kategori->nama_kategori }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalHapusKategori">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data kategori</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    <!-- FIXED MODAL -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <form id="formTambahKategori">
                  @csrf
                  <div class="modal-header">
                      <h5 class="modal-title" id="modalTambahKategoriLabel">Tambah Kategori</h5>
                      <!-- FIXED: Changed data-dismiss to data-bs-dismiss -->
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="mb-3">
                          <label for="nama_kategori_tambah" class="form-label">Nama Kategori</label>
                          <input type="text" name="nama_kategori" id="nama_kategori_tambah" class="form-control" required>
                          <div class="invalid-feedback"></div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" id="btnSimpan">
                          <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                          Simpan
                      </button>
                      <!-- FIXED: Changed data-dismiss to data-bs-dismiss -->
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                  </div>
              </form>
          </div>
        </div>
    </div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
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

    // Show alert function
    function showAlert(message, type = 'success') {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alert-container').html(alertHtml);
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
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
        if (loading) {
            button.prop('disabled', true);
            spinner.removeClass('d-none');
        } else {
            button.prop('disabled', false);
            spinner.addClass('d-none');
        }
    }

    // Add new row to table
    function addRowToTable(kategori) {
        // Remove "no data" row if exists
        $('#kategoriTable tbody tr').each(function() {
            if ($(this).find('td').length === 1 && $(this).find('td').attr('colspan') === '3') {
                $(this).remove();
            }
        });

        const newRow = `
            <tr id="kategori-row-${kategori.id}">
                <td>${kategori.id}</td>
                <td class="kategori-nama">${kategori.nama_kategori}</td>
                <td>
                    <button class="btn btn-warning btn-sm btn-edit" 
                            data-id="${kategori.id}" 
                            data-nama="${kategori.nama_kategori}"
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEditKategori">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-sm btn-delete" 
                            data-id="${kategori.id}" 
                            data-nama="${kategori.nama_kategori}"
                            data-bs-toggle="modal" 
                            data-bs-target="#modalHapusKategori">
                        Hapus
                    </button>
                </td>
            </tr>
        `;
        $('#kategoriTable tbody').append(newRow);
    }

    // FIXED: Store kategori (CREATE) - Main form submission
    $('#formTambahKategori').on('submit', function(e) {
        e.preventDefault();
        console.log('Form submission started');
        
        const form = $(this);
        const submitBtn = $('#btnSimpan');
        const formData = new FormData(this);
        
        // Debug: Log form data
        for (let [key, value] of formData.entries()) {
            console.log('Form data:', key, value);
        }
        
        setLoadingState(submitBtn, true);
        clearValidation(form);
        
        $.ajax({
            url: '{{ route("category.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                console.log('AJAX request starting to:', '{{ route("category.store") }}');
            },
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    showAlert(response.message, 'success');
                    addRowToTable(response.data);
                    form[0].reset();
                    
                    // FIXED: Close modal using Bootstrap 5 method
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahKategori'));
                    if (modal) {
                        modal.hide();
                    } else {
                        $('#modalTambahKategori').modal('hide');
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
                console.log('AJAX request completed');
                setLoadingState(submitBtn, false);
            }
        });
    });

    // Reset forms when modals are hidden
    $('#modalTambahKategori').on('hidden.modal', function() {
        $('#formTambahKategori')[0].reset();
        clearValidation($('#formTambahKategori'));
    });

    // Debug: Test button click
    $('button[data-target="#modalTambahKategori"]').on('click', function() {
        console.log('Modal trigger clicked');
    });
});
</script>
@stop