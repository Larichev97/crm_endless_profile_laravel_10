<?php

namespace App\Http\Controllers\ContactForm;

use App\DataTransferObjects\ContactForm\ContactFormStoreDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactForm\ContactFormStoreRequest;
use App\Repositories\Setting\SettingRepository;
use App\Services\CrudActionsServices\ContactFormService;
use Exception;
use Illuminate\Http\JsonResponse;

final class ContactFormController extends Controller
{
    /**
     * @param ContactFormService $contactFormService
     * @param SettingRepository $settingRepository
     */
    public function __construct(
        readonly ContactFormService $contactFormService,
        readonly SettingRepository $settingRepository,
    )
    {
    }

    /**
     * Store a newly created resource in storage via AJAX (from modal on site).
     *
     * @param ContactFormStoreRequest $contactFormStoreRequest
     * @return JsonResponse
     */
    public function ajaxStore(ContactFormStoreRequest $contactFormStoreRequest): JsonResponse
    {
        try {
            $contactFormStoreDTO = new ContactFormStoreDTO(contactFormStoreRequest: $contactFormStoreRequest);

            $createContactForm = $this->contactFormService->processStore(dto: $contactFormStoreDTO);

            if ($createContactForm) {
                return response()->json(['success' => true, 'message' => 'Форма зворотного зв\'язку успішно створена.'], 201);
            }

            return response()->json(['success' => false, 'message' => 'Помилка! Форма зворотного зв\'язку не створена.'], 400);
        } catch (Exception $exception) {
            return response()->json(['success' => false, 'message' => 'Помилка! Форма зворотного зв\'язку не створена.'], 500);
        }
    }
}
