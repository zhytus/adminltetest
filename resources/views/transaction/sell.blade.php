@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <x-adminlte-card title="Product Categories" icon="fas fa-lg fa-boxes">
        <p>Sell Some Product Here!</p>
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
                            <th width="10%">Stok</th>
                            <th width="10%">Harga</th>
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
                                    <td>{{ $produk->harga_jual }}</td>
                                    <td class="produk-stok">{{ $produk->stok }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm">
                                            Tambah <i class="fa-solid fa-cart-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="no-data-row">
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    Belum ada data Produk
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </x-adminlte-card>

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
                
            }

            // Refresh button functionality
            $('#btnRefresh').click(function() {
                location.reload();
            });
        });
    </script>
@stop