@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <x-adminlte-card title="Product Categories" icon="fas fa-lg fa-boxes">
        <p>Manage Product Stock Here!</p>
        <a href="/" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h2>Data Produk</h2>
                </div>
                <div class="col-md-6 text-right">
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
                <table class="table table-bordered table-striped" id="productTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="30%">Nama Produk</th>
                            <th width="10%">Kategori</th>
                            <th width="10%">Harga Beli</th>
                            <th width="10%">Harga Jual</th>
                            <th width="10%">Stok</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($produks) && count($produks) > 0)
                            @foreach($produks as $produk)
                                <tr id="produk-row-{{ $produk->id }}">
                                    <td>{{ $produk->id }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>{{ $produk->kategori->nama_kategori }}</td>
                                    <td>{{ $produk->harga_beli }}</td>
                                    <td>{{ $produk->harga_jual }}</td>
                                    <td class="produk-stok">{{ $produk->stok }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-primary btn-sm btn-edit" data-toggle="modal" 
                                            data-target="#modalBeliProduk" 
                                            data-id="{{ $produk->id }}" 
                                            data-nama="{{ $produk->nama_produk }}" 
                                            data-kategori="{{ $produk->kategori->nama_kategori }}" 
                                            data-harga-beli="{{ $produk->harga_beli }}   ">
                                                Beli Produk
                                            </button>
                                            <button class="btn btn-warning btn-sm">
                                                Stock OPName 
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

    <div class="modal fade" id="modalBeliProduk" tabindex="-1" aria-labelledby="modalBeliProdukLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formBeliProduk">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="produk_id_beli" name="produk_id">
                    <div class="modal-header bg-primary text-dark">
                        <h5 class="modal-title" id="modalBeliProdukLabel">
                            <i class="fas fa-store-alt"></i> Beli Produk <span id="produk-nama-beli"></span>
                        </h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_produk_beli" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama_produk_beli" class="form-control" readonly placeholder="Masukkan nama produk">
                            <label for="kategori_produk_beli" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="kategori_produk" id="kategori_produk_beli" class="form-control" readonly placeholder="Kategori Produk">
                            <label for="harga_beli_produk" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                <input type="text" name="harga_beli_produk" id="harga_beli_produk" class="form-control" readonly placeholder="Harga Beli Produk">
                            <label for="jumlah_produk_beli" class="form-label">Jumlah Pembelian Produk <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_produk" id="jumlah_produk_beli" class="form-control" required placeholder="Masukkan Jumlah Pembelian Produk">
                            <label for="supplier_produk_beli" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" id="supplier_produk_beli" class="form-select" required>
                                <option value="">Pilih Supplier</option>
                                @foreach($mitras as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                            
                            <label for="harga_beli_produk_beli" class="form-label">Total <span class="text-danger">:</span></label>
                            <input type="text" name="total_beli_produk" id="total-beli-produk" class="" readonly placeholder="Total Pembelian Produk">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btnUpdate">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                            <i class="fas fa-shopping-cart"></i> Done
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </form>
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
            let currentProductiId = null;
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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

            function updateRowInTable(responseData) {
                const produk = responseData.produk;
                const row = $(`#produk-row-${produk.id}`);
                row.find('.produk-stok').text(produk.stok);
            }

            // Refresh button functionality
            $('#btnRefresh').click(function() {
                location.reload();
            });

            $(document).on('click', '.btn-edit', function() {
                console.log('Edit button clicked', $(this).data('harga-beli'));
                
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const kategori = $(this).data('kategori');
                const hargaBeli = $(this).data('harga-beli');
                
                currentProdukId = id;
                $('#nama_produk_beli').val(nama);
                $('#kategori_produk_beli').val(kategori);
                $('#harga_beli_produk').val(hargaBeli);
                $('#produk_id_beli').val(id);
                clearValidation($('#formBeliProduk'));
            });

            $(document).on('input change keyup', '#jumlah_produk_beli', function() {
                calculateTotal();
            });
            
            function calculateTotal() {
                const hargaBeli = parseFloat($('#harga_beli_produk').val()) || 0;
                const jumlah = parseInt($('#jumlah_produk_beli').val()) || 0;
                const total = hargaBeli * jumlah;
                $('#total-beli-produk').val(total.toFixed(2));
            }

           $('#formBeliProduk').on('submit', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const form = $(this);
                const submitBtn = $('#btnUpdate');
                const formData = new FormData(this);
                const supplierId = $('#supplier_id').val();
                
                setLoadingState(submitBtn, true);
                clearValidation(form);
                
                $.ajax({
                    url: `{{ url('transaction') }}/${currentProdukId}`,
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
                            const modal = bootstrap.Modal.getInstance(document.getElementById('modalBeliProduk'));
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
        });
    </script>


@stop