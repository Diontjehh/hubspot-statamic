<?php

namespace DionBoerrigter\Hubspot\Http\Controllers;

use DionBoerrigter\Hubspot\Models\FormFields;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Statamic\Http\Controllers\CP\CpController;

class HubspotFieldController extends CpController
{
    public function index(): View
    {
        $fields = FormFields::all();

        return view('hubspot::fields.index', compact('fields'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'hubspot_field' => ['required', 'string'],
            'statamic_field' => ['required', 'string'],
        ]);

        FormFields::create([
            'hubspot_field' => $request->input('hubspot_field'),
            'statamic_field' => $request->input('statamic_field'),
        ]);

        return redirect()
            ->route('fields.index')
            ->with('success', 'Field successfully saved!');
    }

    public function destroy($id): RedirectResponse
    {
        $field = FormFields::find($id);

        $field->delete();

        return redirect()
            ->back()
            ->with('success', 'Field successfully deleted!');
    }
}
