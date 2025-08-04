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
                <table class="table table-bordered table-striped" id="kategoriTable">
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
                                    <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-info btn-sm btn-view" 
                                                    data-id="{{ $customer->id }}" 
                                                    data-nama="{{ $customer->nama }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalViewCustomer"
                                                    title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm btn-edit" 
                                                    data-id="{{ $customer->id }}" 
                                                    data-nama="{{ $customer->nama }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalEditCustomer"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-delete" 
                                                    data-id="{{ $customer->id }}" 
                                                    data-nama="{{ $customer->nama }}"
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



@stop


@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");

        function showAlert() {
            alert('Hello, this is a custom alert!');
        }
    </script>
@stop