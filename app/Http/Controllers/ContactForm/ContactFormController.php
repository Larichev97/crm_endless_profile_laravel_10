<?php

namespace App\Http\Controllers\ContactForm;

use App\DataTransferObjects\ContactForm\ContactFormStoreDTO;
use App\DataTransferObjects\ContactForm\ContactFormUpdateDTO;
use App\Enums\ContactFormStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactForm\ContactFormStoreRequest;
use App\Http\Requests\ContactForm\ContactFormUpdateRequest;
use App\Repositories\ContactForm\ContactFormRepository;
use App\Repositories\User\UserRepository;
use App\Services\CrudActionsServices\ContactFormService;
use App\Services\FilterTableService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactFormController extends Controller
{
    /**
     * @param ContactFormService $contactFormService
     * @param ContactFormRepository $contactFormRepository
     */
    public function __construct(
        readonly ContactFormService $contactFormService,
        readonly ContactFormRepository $contactFormRepository,
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param FilterTableService $filterTableService
     * @param UserRepository $userRepository
     * @return \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
     */
    public function index(Request $request, FilterTableService $filterTableService, UserRepository $userRepository): \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
    {
        try {
            $filterFieldsArray = $filterTableService->processPrepareFilterFieldsArray(allFieldsData: $request->all());
            $filterFieldsObject = json_decode(json: json_encode(value: $filterFieldsArray));

            $contactForms = $this->contactFormRepository->getAllWithPaginate(perPage: 10, page: (int) $request->get('page', 1), orderBy: 'id', orderWay: 'desc',  useCache: false, filterFieldsData: $filterFieldsArray);

            $statusesListData = ContactFormStatusEnum::getStatusesList();
            $employeesListData = $userRepository->getForDropdownList(fieldId: 'id', fieldName: 'CONCAT(lastname, " ", firstname) AS name', useCache: true);

            return view('contact_form.index',compact(['contactForms', 'filterFieldsObject', 'statusesListData', 'employeesListData',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        return view('contact_form.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactFormStoreRequest $contactFormStoreRequest
     * @return RedirectResponse|JsonResponse
     */
    public function store(ContactFormStoreRequest $contactFormStoreRequest): RedirectResponse|JsonResponse
    {
        try {
            $contactFormStoreDTO = new ContactFormStoreDTO(contactFormStoreRequest: $contactFormStoreRequest);

            $createContactForm = $this->contactFormService->processStore(contactFormStoreDTO: $contactFormStoreDTO);

            if ($createContactForm) {
                return redirect()->route('contact-forms.index')->with('success','Форма обратной связи успешно создана.');
            }

            return back()->with('error','Ошибка! Форма обратной связи не создана.')->withInput();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
     */
    public function show($id): Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
    {
        try {
            $contactForm = $this->contactFormRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($contactForm)) {
                abort(404);
            }

            return view('contact_form.show',compact(['contactForm',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
     */
    public function edit($id): Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
    {
        try {
            $contactForm = $this->contactFormRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($contactForm)) {
                abort(404);
            }

            $statusesListData = ContactFormStatusEnum::getStatusesList();

            return view('contact_form.edit',compact(['contactForm', 'statusesListData',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContactFormUpdateRequest $contactFormUpdateRequest
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function update(ContactFormUpdateRequest $contactFormUpdateRequest, $id): RedirectResponse|JsonResponse
    {
        try {
            $contactFormUpdateDTO = new ContactFormUpdateDTO(contactFormUpdateRequest: $contactFormUpdateRequest, idContactForm: (int) $id);

            $updateContactForm = $this->contactFormService->processUpdate(contactFormUpdateDTO: $contactFormUpdateDTO, contactFormRepository: $this->contactFormRepository);

            if ($updateContactForm) {
                return redirect()->route('contact-forms.index')->with('success', sprintf('Данные формы обратной связи #%s успешно обновлены.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Данные формы обратной связи #%s не обновлены.', $id))->withInput();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function destroy($id): RedirectResponse|JsonResponse
    {
        try {
            $deleteContactForm = $this->contactFormService->processDestroy(id: $id, contactFormRepository: $this->contactFormRepository);

            if ($deleteContactForm) {
                return redirect()->route('contact-forms.index')->with('success', sprintf('Форма обратной связи #%s успешно удалена.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Форма обратной связи #%s не удалена.', $id));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
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

            $createContactForm = $this->contactFormService->processStore(contactFormStoreDTO: $contactFormStoreDTO);

            if ($createContactForm) {
                return response()->json(['success' => true, 'message' => 'Форма зворотного зв\'язку успішно створена.'], 201);
            }

            return response()->json(['success' => false, 'message' => 'Помилка! Форма зворотного зв\'язку не створена.'], 400);
        } catch (Exception $exception) {
            return response()->json(['success' => false, 'message' => 'Помилка! Форма зворотного зв\'язку не створена.'], 500);
        }
    }
}
