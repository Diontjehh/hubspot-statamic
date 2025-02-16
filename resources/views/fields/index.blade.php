@extends('statamic::layout')

@section('title', 'Hubspot Fields')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="flex-1">Hubspot Fields</h1>
        <a href="{{ route('fields.create') }}" class="btn-primary">Add field</a>
    </div>

    <div class="card">
        <table data-size="sm" tabindex="0" class="data-table">
            <thead>
                <tr>
                    <th>Hubspot</th>
                    <th>Statamic</th>
                    <th class="flex justify-end">Actions</th>
                </tr>
            </thead>
            <tbody tabindex="0">
                @foreach ($fields as $field)
                    <tr id="field_{{ $field->id }}" class="sortable-row outline-none" tabindex="0">
                        <td>
                            <a href="">
                                {{ $field->hubspot_field }}
                            </a>
                        </td>
                        <td>
                            <a href="">
                                {{ $field->statamic_field }}
                            </a>
                        </td>

                        <td class="flex justify-end">
                            <dropdown-list class="mr-1">
                                @if(auth()->user()->can('delete tax rates'))
                                    <dropdown-item>
                                        <form action="{{ route('fields.destroy', $field->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-warning" onclick="return confirm('Are you sure you want to clear the HubSpot GUID?');">
                                                Delete fields
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