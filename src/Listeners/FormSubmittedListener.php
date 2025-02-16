<?php

namespace DionBoerrigter\Hubspot\Listeners;

use DionBoerrigter\Hubspot\Models\FormFields;
use DionBoerrigter\Hubspot\Models\HubspotForm;
use Exception;
use GuzzleHttp\Exception\ClientException;
use HubSpot\Discovery\Discovery;
use HubSpot\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Statamic\Contracts\Forms\Submission;
use Statamic\Events\SubmissionCreated;

class FormSubmittedListener
{
    private Discovery $hubspot;

    private string $portal_id;

    public function __construct()
    {
        $accesstoken = env('HUBSPOT_ACCESS_TOKEN');

        $this->hubspot = Factory::createWithAccessToken($accesstoken);

        $this->portal_id = env('HUBSPOT_PORTAL_ID');
    }

    public function handle(SubmissionCreated $event)
    {
        $submission = $event->submission;
        $form = $submission->form;

        if (empty($form->model()->hubspot_guid)) {
            return true;
        }

        $hubspotForm = HubspotForm::where('handle', $form->handle())->first();

        $hubspotData = $this->mapToHubspotFields($submission);

        $sendToHubspot = $this->sendHubSpotFormSubmission($hubspotForm, $hubspotData);

        return $sendToHubspot;
    }

    protected function mapToHubspotFields(Submission $submission): Collection
    {
        $fieldMapping = FormFields::all()->pluck('hubspot_field', 'statamic_field')->toArray();

        $hubspotData = collect();

        $formData = $submission->data()->all();

        foreach ($fieldMapping as $localField => $hubspotField) {
            if (isset($formData[$localField]) && ! empty($formData[$localField])) {
                $hubspotData[$hubspotField] = $formData[$localField];
            }
        }

        return $hubspotData;
    }

    protected function sendHubSpotFormSubmission(HubspotForm $form, Collection $input): bool
    {
        $formGuid = $form->hubspot_guid;

        if (empty($formGuid)) {
            throw new Exception('Form guid not found for form with handle '.$form->handle);
        }

        $fields = array_values($input
            ->map(function ($value, $key) {
                return [
                    'objectTypeId' => '0-1',
                    'name' => $key,
                    'value' => $value,
                ];
            })
            ->toArray());

        $context = [
            'hutk' => request()->cookie('hutk'),
            'ipAddress' => request()->ip(),
            'pageUri' => request()->url(),
            'pageName' => $form->title,
        ];

        $request = [
            'method' => 'POST',
            'baseUrl' => 'https://api.hsforms.com',
            'path' => "/submissions/v3/integration/submit/{$this->portal_id}/{$formGuid}",
            'defaultJson' => true,
            'body' => [
                'fields' => $fields,
                'context' => $context,
            ],
        ];

        try {
            $response = $this->hubspot->apiRequest($request);

            return $response->getStatusCode() === 200;
        } catch (ClientException $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
