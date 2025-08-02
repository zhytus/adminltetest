@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome, to this beautiful admin panel.</p>
    <x-adminlte-card title="Product Management" icon="fas fa-lg fa-boxes" collapsible>
        <p>Manage All Product Here !</p>
        <x-adminlte-button theme="primary" label="Manage Product" icon="fas fa-box-open"/>
        <x-adminlte-button theme="success" label="Manage Category " icon="fas fa-boxes"/>
    </x-adminlte-card>

    <x-adminlte-card title="Partner Management" icon="fas fa-lg fa-users" collapsible>
        <p>Manage All Partner Here !</p>
        <x-adminlte-button theme="primary" label="Manage Customer" icon="fas fa-user"/>
        <x-adminlte-button theme="success" label="Manage Supplier" icon="fas fa-user-tag"/>
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