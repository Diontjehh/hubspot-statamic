@extends('statamic::layout')

@section('title', 'Hubspot Formulierenn')

@section('content')
    <div class="rounded p-6 lg:px-20 lg:py-10 shadow bg-white dark:bg-dark-600 dark:shadow-dark">
        <header class="text-center mb-16">
            <h1 class="mb-6">Add forms</h1>
            <p class="text-gray-600">Make forms</p>
        </header>

        <form action="{{ route('forms.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="font-bold text-base mb-1">Form Type</label>
                <div class="select-input">
                    <select name="form_type" class="input-text">
                        <option value="">Select a form</option>
                        @foreach ($forms as $form)
                            <option value="{{ $form->handle }}">{{ $form->title}}</option>
                        @endforeach
                    </select>
                </div>
                @error('form_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="font-bold text-base mb-1">Hubspot guid</label>
                <input type="text" name="hubspot_guid" class="input-text">
                @error('hubspot_guid')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn-primary mt-4">Save</button>
            </div>
        </form>
    </div>
@endsection