@extends('statamic::layout')

@section('title', 'Hubspot Formulieren')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="flex-1">Hubspot Forms</h1>
        <a href="{{ route('forms.create') }}" class="btn-primary">Add form</a>
    </div>

    <div class="card">
        <table data-size="sm" tabindex="0" class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th class="flex justify-end">Actions</th>
                </tr>
            </thead>
            <tbody tabindex="0">
                @foreach ($forms as $form)
                    <tr id="form_{{ $form->id }}" class="sortable-row outline-none" tabindex="0">
                        <td>
                            <a href="">
                                {{ $form->title }}
                            </a>
                        </td>

                        <td class="flex justify-end">
                            <dropdown-list class="mr-1">
                                @if($form->id !== 'default-rate' && $form->id !== 'default-shipping-rate' && auth()->user()->can('delete tax rates'))
                                    <dropdown-item>
                                        <form action="{{ route('forms.destroy', $form->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-warning" onclick="return confirm('Are you sure you want to clear the HubSpot GUID?');">
                                                Delete GUID
                                            </button>
                                        </form>
                                    </dropdown-item>
                                @endif
                            </dropdown-list>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection