<?php

namespace DionBoerrigter\Hubspot\Http\Controllers;

use DionBoerrigter\Hubspot\Models\HubspotForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Statamic\Facades\Form;
use Statamic\Http\Controllers\CP\CpController;

class HubspotFormController extends CpController
{
    public function index(): View
    {
        $forms = HubspotForm::all()->whereNotNull('hubspot_guid');
        
        return view('hubspot::forms.index', compact('forms'));
    }

    public function create(): View
    {
        $forms = HubspotForm::whereNull('hubspot_guid')->get();

        return view('hubspot::forms.create', ['forms' => $forms]);
    }  

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'form_type' => ['required'],
            'hubspot_guid' => ['required', 'string'],
        ]);
    
        HubspotForm::all()
            ->where('handle', $request->input('form_type'))
            ->first()
            ->update([
                'hubspot_guid' => $request->input('hubspot_guid'),
            ]);
    
        return redirect()
            ->route('forms.index')
            ->with('success', 'Formulier succesvol opgeslagen!');
    }

    public function destroy($id): RedirectResponse
    {
        $form = HubspotForm::find($id);

        $form->update([
            'hubspot_guid' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Formulier succesvol verwijderd!');
    }
}