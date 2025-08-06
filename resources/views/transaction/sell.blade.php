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
        
        <!-- Cart Summary -->
        <div class="row mb-3 align-center">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shopping-cart"></i> Shopping Cart Summary
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Items in Cart</span>
                                        <span class="info-box-number" id="cart-items-count">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Amount</span>
                                        <span class="info-box-number" id="cart-total-amount">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <button class="btn btn-warning btn-block" id="btnViewCart">
                                            <i class="fas fa-eye"></i> View Cart
                                        </button>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <button class="btn btn-success btn-block" id="btnCheckout">
                                            <i class="fas fa-credit-card"></i> Checkout
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <select name="mitra_id" id="supplier_produk_beli" required class="custom-select">
                                        <option value="">Pilih Pembeli</option>
                                        @foreach($mitras as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
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
                            <th width="25%">Nama Produk</th>
                            <th width="10%">Kategori</th>
                            <th width="10%">Stok</th>
                            <th width="15%">Harga</th>
                            <th width="10%">Quantity</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($produks) && count($produks) > 0)
                            @foreach($produks as $produk)
                                <tr id="produk-row-{{ $produk->id }}">
                                    <td>{{ $produk->id }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>{{ $produk->kategori->nama_kategori }}</td>
                                    <td class="produk-stok">{{ $produk->stok }}</td>
                                    <td class="produk-harga" data-harga="{{ $produk->harga_jual }}">
                                        Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="input-group" style="width: 120px;">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm btn-qty-minus" 
                                                        data-produk-id="{{ $produk->id }}" type="button">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" class="form-control form-control-sm text-center qty-input" 
                                                   data-produk-id="{{ $produk->id }}" 
                                                   value="1" min="1" max="{{ $produk->stok }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm btn-qty-plus" 
                                                        data-produk-id="{{ $produk->id }}" type="button">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm btn-add-to-cart" 
                                                data-produk-id="{{ $produk->id }}"
                                                data-nama="{{ $produk->nama_produk }}"
                                                data-harga="{{ $produk->harga_jual }}"
                                                data-stok="{{ $produk->stok }}">
                                            <i class="fas fa-plus"></i> Add to Cart
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="no-data-row">
                                <td colspan="7" class="text-center text-muted">
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

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">
                        <i class="fas fa-shopping-cart"></i> Shopping Cart
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="cart-modal-body">
                    <!-- Cart items will be loaded here -->
                </div>
                <select name="mitra_id" id="supplier_produk_beli" class="form-select" required>
                    <option value="">Pilih Pembeli</option>
                    @foreach($mitras as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                    @endforeach
                </select>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="btnClearCart">
                        <i class="fas fa-trash"></i> Clear Cart
                    </button>
                    <button type="button" class="btn btn-success" id="btnModalCheckout">
                        <i class="fas fa-credit-card"></i> Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <style>
        .qty-input {
            width: 60px !important;
        }
        .cart-item {
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            let currentProductId = null;
            let cart = JSON.parse(localStorage.getItem('shopping_cart') || '[]');
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize cart display
            updateCartSummary();

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
                
                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 5000);

                $('html, body').animate({
                    scrollTop: $('#alert-container').offset().top - 100
                }, 500);
            }

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

            function formatCurrency(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            }

            function updateCartSummary() {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                const totalAmount = cart.reduce((sum, item) => sum + (item.harga * item.quantity), 0);
                
                $('#cart-items-count').text(totalItems);
                $('#cart-total-amount').text(formatCurrency(totalAmount));
                $('#supplier_produk_beli').val("");
                
                // Save to localStorage
                localStorage.setItem('shopping_cart', JSON.stringify(cart));
            }

            function updateRowInTable(productId, newStock) {
                const row = $(`#produk-row-${productId}`);
                if (row.length) {
                    row.find('.produk-stok').text(newStock);
                    
                    // Update max quantity in input
                    const qtyInput = row.find('.qty-input');
                    qtyInput.attr('max', newStock);
                    
                    // Reset quantity to 1 if current value exceeds new stock
                    if (parseInt(qtyInput.val()) > newStock) {
                        qtyInput.val(Math.min(1, newStock));
                    }
                    
                    // Disable add to cart if no stock
                    const addBtn = row.find('.btn-add-to-cart');
                    if (newStock <= 0) {
                        addBtn.prop('disabled', true).html('<i class="fas fa-ban"></i> Out of Stock');
                    } else {
                        addBtn.prop('disabled', false).html('<i class="fas fa-plus"></i> Add to Cart');
                    }
                }
            }

            function addToCart(productId, productName, productPrice, quantity, availableStock) {
                const existingItemIndex = cart.findIndex(item => item.id == productId);
                
                if (existingItemIndex > -1) {
                    // Update existing item
                    const newQuantity = cart[existingItemIndex].quantity + quantity;
                    if (newQuantity <= availableStock) {
                        cart[existingItemIndex].quantity = newQuantity;
                    } else {
                        showAlert(`Cannot add more items. Only ${availableStock} items available in stock.`, 'warning');
                        return false;
                    }
                } else {
                    // Add new item
                    if (quantity <= availableStock) {
                        cart.push({
                            id: productId,
                            nama: productName,
                            harga: productPrice,
                            quantity: quantity,
                            stok: availableStock
                        });
                    } else {
                        showAlert(`Cannot add more items. Only ${availableStock} items available in stock.`, 'warning');
                        return false;
                    }
                }
                
                updateCartSummary();
                return true;
            }

            function renderCartModal() {
                let cartHtml = '';
                
                if (cart.length === 0) {
                    cartHtml = `
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5>Your cart is empty</h5>
                            <p class="text-muted">Add some products to get started!</p>
                        </div>
                    `;
                } else {
                    cart.forEach((item, index) => {
                        cartHtml += `
                            <div class="cart-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>${item.nama}</h6>
                                        <p class="text-muted mb-0">${formatCurrency(item.harga)} per item</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-cart-qty-minus" 
                                                        data-index="${index}" type="button">-</button>
                                            </div>
                                            <input type="number" class="form-control text-center cart-qty-input" 
                                                   data-index="${index}" value="${item.quantity}" 
                                                   min="1" max="${item.stok}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-cart-qty-plus" 
                                                        data-index="${index}" type="button">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <strong>${formatCurrency(item.harga * item.quantity)}</strong>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger btn-sm btn-remove-from-cart" 
                                                data-index="${index}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    const total = cart.reduce((sum, item) => sum + (item.harga * item.quantity), 0);
                    cartHtml += `
                        <div class="mt-3 pt-3 border-top">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Total: ${formatCurrency(total)}</h5>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                $('#cart-modal-body').html(cartHtml);
            }

            // Quantity controls for products
            $('.btn-qty-plus').click(function() {
                const input = $(this).closest('.input-group').find('.qty-input');
                const max = parseInt(input.attr('max'));
                const current = parseInt(input.val());
                if (current < max) {
                    input.val(current + 1);
                }
            });

            $('.btn-qty-minus').click(function() {
                const input = $(this).closest('.input-group').find('.qty-input');
                const min = parseInt(input.attr('min')) || 1;
                const current = parseInt(input.val());
                if (current > min) {
                    input.val(current - 1);
                }
            });

            // Add to cart functionality
            $('.btn-add-to-cart').click(function() {
                const button = $(this);
                const produkId = button.data('produk-id');
                const productName = button.data('nama');
                const productPrice = parseFloat(button.data('harga'));
                const availableStock = parseInt(button.data('stok'));
                const quantity = parseInt($(this).closest('tr').find('.qty-input').val());
                
                if (availableStock <= 0) {
                    showAlert('Product is out of stock!', 'error');
                    return;
                }
                
                setLoadingState(button, true);
                
                // Simulate API call with timeout
                setTimeout(() => {
                    if (addToCart(produkId, productName, productPrice, quantity, availableStock)) {
                        showAlert(`${quantity} ${productName} added to cart successfully!`, 'success');
                        
                        // Simulate stock reduction (in real app, this would come from server)
                        const newStock = availableStock - quantity;
                        button.data('stok', newStock);
                        updateRowInTable(produkId, newStock);
                    }
                    setLoadingState(button, false);
                }, 500);
            });

            // View cart modal
            $('#btnViewCart').click(function() {
                renderCartModal();
                $('#cartModal').modal('show');
            });

            // Cart modal quantity controls
            $(document).on('click', '.btn-cart-qty-plus', function() {
                const index = $(this).data('index');
                const input = $(this).closest('.input-group').find('.cart-qty-input');
                const max = parseInt(input.attr('max'));
                const current = parseInt(input.val());
                
                if (current < max) {
                    cart[index].quantity = current + 1;
                    updateCartSummary();
                    renderCartModal();
                }
            });

            $(document).on('click', '.btn-cart-qty-minus', function() {
                const index = $(this).data('index');
                const input = $(this).closest('.input-group').find('.cart-qty-input');
                const current = parseInt(input.val());
                
                if (current > 1) {
                    cart[index].quantity = current - 1;
                    updateCartSummary();
                    renderCartModal();
                }
            });

            $(document).on('change', '.cart-qty-input', function() {
                const index = $(this).data('index');
                const newQuantity = parseInt($(this).val());
                const max = parseInt($(this).attr('max'));
                
                if (newQuantity > 0 && newQuantity <= max) {
                    cart[index].quantity = newQuantity;
                    updateCartSummary();
                    renderCartModal();
                } else {
                    $(this).val(cart[index].quantity);
                    showAlert(`Maximum ${max} items available`, 'warning');
                }
            });

            // Remove from cart
            $(document).on('click', '.btn-remove-from-cart', function() {
                const index = $(this).data('index');
                const item = cart[index];
                
                if (confirm(`Remove ${item.nama} from cart?`)) {
                    cart.splice(index, 1);
                    updateCartSummary();
                    renderCartModal();
                    showAlert(`${item.nama} removed from cart`, 'info');
                }
            });

            // Clear cart
            $('#btnClearCart').click(function() {
                if (cart.length > 0 && confirm('Are you sure you want to clear the cart?')) {
                    cart = [];
                    updateCartSummary();
                    renderCartModal();
                    showAlert('Cart cleared successfully', 'info');
                }
            });

            // Checkout functionality
            // Replace the checkout functionality section in your blade template with this:

// Checkout functionality
$('#btnCheckout, #btnModalCheckout').click(function(e) {
    e.preventDefault();
    
    if (cart.length === 0) {
        showAlert('Cart is empty! Add some products first.', 'warning');
        return;
    }

    const mitraId = $('#supplier_produk_beli').val();
    if (!mitraId) {
        showAlert('Please select a buyer (mitra) before checkout.', 'warning');
        return;
    }
    
    const total = cart.reduce((sum, item) => sum + (item.harga * item.quantity), 0);
    const submitBtn = $(this);
    
    if (confirm(`Proceed to checkout with ${cart.length} items totaling ${formatCurrency(total)}?`)) {
        setLoadingState(submitBtn, true);
        
        // Debug: Check CSRF token
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        
        if (!csrfToken) {
            showAlert('CSRF token not found. Please refresh the page.', 'error');
            setLoadingState(submitBtn, false);
            return;
        }
        
        // FIXED: Simplified data structure (removed wrapper)
        const transactionData = {
            items: cart.map(item => ({
                produk_id: item.id,
                produk_nama: item.nama,
                harga_jual_produk: item.harga,
                jumlah_produk: item.quantity,
                subtotal: item.harga * item.quantity
            })),
            total_amount: total,
            total_items: cart.reduce((sum, item) => sum + item.quantity, 0),
            transaction_date: new Date().toISOString(), 
            tipe_pembayaran: 'tunai',
            supplier_id:mitraId
        };

        $.ajax({
            url: '{{ route("transaction.store") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            // CRITICAL FIX: Send data directly, not wrapped
            data: JSON.stringify(transactionData),
            success: function(response) {
                
                try {
                    // More flexible success checking
                    const isSuccess = response.success === true || 
                                    response.success === 'true' || 
                                    response.status === 'success' ||
                                    response.success === 1;
                    
                    
                    if (isSuccess) {
                        
                        const successMessage = response.message || 'Order placed successfully!';
                        showAlert(successMessage, 'success');
                        
                        // Update stock in UI for each item
                        console.log('Updating stock for', cart.length, 'items...');
                        cart.forEach(item => {
                            try {
                                const currentStock = parseInt($(`#produk-row-${item.id} .produk-stok`).text()) || 0;
                                const newStock = Math.max(0, currentStock - item.quantity);
                                
                                console.log(`Stock update - Item ${item.id} (${item.nama}): ${currentStock} → ${newStock}`);
                                
                                updateRowInTable(item.id, newStock);
                                $(`#produk-row-${item.id} .btn-add-to-cart`).attr('data-stok', newStock);
                                
                            } catch (stockError) {
                                console.error('❌ Stock update error for item:', item.id, stockError);
                            }
                        });
                        
                        // Show transaction code if available
                        if (response.data && response.data.transaction_code) {
                            const transactionCode = response.data.transaction_code;
                            showAlert(`Transaction ${transactionCode} completed successfully!`, 'success');
                            console.log('Transaction code:', transactionCode);
                        }
                        
                        // Clear cart
                        console.log('Clearing cart...');
                        cart = [];
                        localStorage.removeItem('shopping_cart'); // Clear localStorage too
                        updateCartSummary();
                        
                        // Hide modal
                        try {
                            $('#cartModal').modal('hide');
                            console.log('✅ Modal hidden');
                        } catch (modalError) {
                            console.error('❌ Modal hide error:', modalError);
                        }
                        
                        // Handle redirect or show completion message
                        if (response.redirect_url) {
                            console.log('Redirecting to:', response.redirect_url);
                            setTimeout(() => {
                                window.location.href = response.redirect_url;
                            }, 2000);
                        } else {
                            setTimeout(() => {
                                showAlert('Thank you for your purchase!', 'info');
                            }, 1500);
                        }
                        
                        console.log('✅ Success handler completed');
                        
                    } else {
                        console.log('❌ TRANSACTION FAILED');
                        console.log('Response message:', response.message);
                        console.log('Response errors:', response.errors);
                        
                        let errorMsg = response.message || 'Transaction failed. Please try again.';
                        
                        // Handle validation errors
                        if (response.errors) {
                            console.log('Validation errors detected:', response.errors);
                            if (typeof response.errors === 'object') {
                                errorMsg = Object.values(response.errors).flat().join('<br>');
                            } else {
                                errorMsg = response.errors.toString();
                            }
                        }
                        
                        showAlert(errorMsg, 'error');
                    }
                    
                } catch (processingError) {
                    console.error('❌ Error processing success response:', processingError);
                    console.error('Stack trace:', processingError.stack);
                    showAlert('An error occurred while processing the response. Please refresh and try again.', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.log('=== AJAX ERROR OCCURRED ===');
                console.error('HTTP Status:', xhr.status);
                console.error('Status Text:', xhr.statusText);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                
                let errorResponse = null;
                try {
                    errorResponse = JSON.parse(xhr.responseText);
                    console.log('Parsed error response:', errorResponse);
                } catch (e) {
                    console.log('Could not parse error response as JSON');
                }
                
                let errorMessage = 'An error occurred during checkout. Please try again.';
                
                // Handle specific HTTP status codes
                switch(xhr.status) {
                    case 403:
                        errorMessage = 'Access denied. Please refresh the page and try again.';
                        break;
                    case 419:
                        errorMessage = 'Session expired. Please refresh the page and try again.';
                        break;
                    case 422:
                        errorMessage = 'Validation error. Please check your data and try again.';
                        if (errorResponse && errorResponse.errors) {
                            const validationErrors = Object.values(errorResponse.errors).flat();
                            errorMessage = 'Validation errors:<br>' + validationErrors.join('<br>');
                        }
                        break;
                    case 500:
                        errorMessage = 'Server error. Please try again later.';
                        if (errorResponse && errorResponse.message) {
                            errorMessage += '<br>Details: ' + errorResponse.message;
                        }
                        break;
                    case 404:
                        errorMessage = 'Route not found. Please check your configuration.';
                        break;
                    default:
                        if (errorResponse && errorResponse.message) {
                            errorMessage = errorResponse.message;
                        }
                }
                
                showAlert(errorMessage, 'error');
                console.log('Error message shown:', errorMessage);
            },
            complete: function() {
                setLoadingState(submitBtn, false);
                console.log('=== REQUEST COMPLETED ===');
            }
        });
    }
});

            // Refresh button functionality
            $('#btnRefresh').click(function() {
                location.reload();
            });
        });
    </script>
@stop