@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome, to this beautiful admin panel.</p>
    <x-adminlte-card title="Product Management" icon="fas fa-lg fa-boxes" collapsible>
        <p>Manage All Product Here !</p>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('product.index') }}'">
            <i class="fas fa-box-open"></i> Manage Product
        </button>
        <button class="btn btn-success" onclick="window.location.href='{{ route('category.index') }}'">
            <i class="fas fa-boxes"></i> Manage Product Category
        </button>
        <button class="btn btn-info" onclick="window.location.href='{{ route('transaction.index') }}'">
            <i class="fas fa-cart-plus"></i> Manage Purchasing
        </button>
    </x-adminlte-card>

    <x-adminlte-card title="Partner Management" icon="fas fa-lg fa-users" collapsible>
        <p>Manage All Partner Here !</p>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('customer.index') }}'">
            <i class="fas fa-user"></i> Manage Customer
        </button>
        <button class="btn btn-success" onclick="window.location.href='{{ route('supplier.index') }}'">
            <i class="fas fa-user-tag"></i> Manage Supplier
        </button>
    </x-adminlte-card>

    <x-adminlte-card title="Finance Management" icon="fas fa-lg fa-money-bill" collapsible>
        <p>Manage All Finance Here !</p>
        <x-adminlte-datatable id="financeTable" :heads="['Account Name', 'Finance Type', 'Balance']" bordered beautify>
            <td>2023-10</td>
            <td>Invoice #1234</td>
            <td>100.00</td>
        </x-adminlte-datatable>
    </x-adminlte-card>
    <x-adminlte-card title="Daily Payment Management" icon="fas fa-lg fa-money-bill" collapsible>
        <p>Manage All Daily Payment Here !</p>
        <x-adminlte-datatable id="financeTable" :heads="['Account Name', 'Finance Type', 'Balance']" bordered beautify>
            <td>2023-10</td>
            <td>Invoice #1234</td>
            <td>100.00</td>
        </x-adminlte-datatable>
    </x-adminlte-card>
    <x-adminlte-card title="Income Management" icon="fas fa-lg fa-money-bill" collapsible>
        <p>Manage All Income Here !</p>
        <x-adminlte-datatable id="financeTable" :heads="['Account Name', 'Finance Type', 'Balance']" bordered beautify>
            <td>2023-10</td>
            <td>Invoice #1234</td>
            <td>100.00</td>
        </x-adminlte-datatable>
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