@extends('adminlte::page')

@section('title', 'Partner Management')

@section('content_header')
    <h1>Partner Management</h1>
@stop

@section('content')
    <x-adminlte-card title="Supplier Management" icon="fas fa-lg fa-user">
        <p>Manage All Supplier Data Here!</p>
        <x-adminlte-button theme="primary" label="Add New Customer" icon="fas fa-plus" class="mb-2"/>
        <x-adminlte-datatable id="categoryTable" :heads="['Name', 'Phone Number','Loan Balance' ,'Actions']" bordered beautify>
            <td>Electronics</td>
            <td>Devices and gadgets</td>
            <td>1000</td>
            <td>
                <x-adminlte-button theme="info" label="Edit" icon="fas fa-edit"/>
                <x-adminlte-button theme="danger" label="Delete" icon="fas fa-trash"/>
            </td>
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