@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Product Management</h1>
@stop

@section('content')
    <x-adminlte-card title="Product Categories" icon="fas fa-lg fa-boxes">
        <p>Manage All Product Categories Here!</p>
        <x-adminlte-button theme="primary" label="Add New Category" icon="fas fa-plus" class="mb-2"/>
        <x-adminlte-datatable id="categoryTable" :heads="['Category Name', 'Description', 'Actions']" bordered beautify>
            <td>Electronics</td>
            <td>Devices and gadgets</td>
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