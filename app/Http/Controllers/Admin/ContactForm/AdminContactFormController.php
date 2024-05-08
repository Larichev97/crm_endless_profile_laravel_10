<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\DataTransferObjects\ContactForm\ContactFormStoreDTO;
use App\DataTransferObjects\ContactForm\ContactFormUpdateDTO;
use App\Enums\ContactFormStatusEnum;
use App\Exceptions\ContactForm\ContactFormNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactForm\ContactFormStoreRequest;
use App\Http\Requests\ContactForm\ContactFormUpdateRequest;
use App\Repositories\ContactForm\ContactFormRepository;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\User\UserRepository;
use App\Services\CrudActionsServices\ContactFormService;
use App\Services\FilterTableService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AdminContactFormController extends Controller
{
    /**
     * @param ContactFormService $contactFormService
     * @param ContactFormRepository $contactFormRepository
     * @param SettingRepository $settingRepository
     */
    public function __construct(
        readonly ContactFormService $contactFormService,
        readonly ContactFormRepository $contactFormRepository,
        readonly SettingRepository $settingRepository,
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param FilterTableService $filterTableService
     * @param UserRepository $userRepository
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function index(Request $request, FilterTableService $filterTableService, UserRepository $userRepository): \Illuminate\Foundation\Application|View|Factory|Application
    {
        try {
            $page = (int) $request->get(key: 'page', default: 1);
            $sortBy = $request->get(key: 'sort_by', default: 'id');
            $sortWay = $request->get(key: 'sort_way', default: 'desc');

            $filterFieldsArray = $filterTableService->processPrepareFilterFieldsArray(allFieldsData: $request->all());
            $filterFieldsObject = $filterTableService->processConvertFilterFieldsToObject(filterFieldsArray: $filterFieldsArray);

            $contactForms = $this->contactFormRepository->getAllWithPaginate(perPage: 10, page: $page, orderBy: $sortBy, orderWay: $sortWay);
            $displayedFields = $this->contactFormRepository->getDisplayedFieldsOnIndexPage();

            $statusesListData = ContactFormStatusEnum::getStatusesList();
            $employeesListData = $userRepository->getForDropdownList(fieldId: 'id', fieldName: 'CONCAT(lastname, " ", firstname) AS name', useCache: true);

            return view('contact_form.index',compact(['contactForms', 'displayedFields', 'filterFieldsObject', 'statusesListData', 'employeesListData', 'sortBy', 'sortWay',]));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
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
     * @return View|Factory|\Illuminate\Foundation\Application|RedirectResponse|Application
     */
    public function store(ContactFormStoreRequest $contactFormStoreRequest): View|Factory|\Illuminate\Foundation\Application|RedirectResponse|Application
    {
        try {
            $createContactForm = $this->contactFormService->processStore(dto: new ContactFormStoreDTO(contactFormStoreRequest: $contactFormStoreRequest));

            if ($createContactForm) {
                return redirect()->route('admin.contact-forms.index')->with('success','Форма обратной связи успешно создана.');
            }

            return back()->with('error','Ошибка! Форма обратной связи не создана.')->withInput();
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function show($id): Application|Factory|View|\Illuminate\Foundation\Application
    {
        try {
            $contactForm = $this->contactFormRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($contactForm)) {
                throw new ContactFormNotFoundException(sprintf('Заявка из формы обратной связи #%s не найдена.', $id));
            }

            return view('contact_form.show',compact(['contactForm',]));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function edit($id): Application|Factory|View|\Illuminate\Foundation\Application
    {
        try {
            $contactForm = $this->contactFormRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($contactForm)) {
                throw new ContactFormNotFoundException(sprintf('Заявка из формы обратной связи #%s не найдена.', $id));
            }

            $statusesListData = ContactFormStatusEnum::getStatusesList();

            return view('contact_form.edit',compact(['contactForm', 'statusesListData',]));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContactFormUpdateRequest $contactFormUpdateRequest
     * @param $id
     * @return View|Factory|\Illuminate\Foundation\Application|RedirectResponse|Application
     */
    public function update(ContactFormUpdateRequest $contactFormUpdateRequest, $id): View|Factory|\Illuminate\Foundation\Application|RedirectResponse|Application
    {
        try {
            $updateContactForm = $this->contactFormService->processUpdate(dto: new ContactFormUpdateDTO(contactFormUpdateRequest: $contactFormUpdateRequest, idContactForm: (int) $id), repository: $this->contactFormRepository);

            if ($updateContactForm) {
                return redirect()->route('admin.contact-forms.index')->with('success', sprintf('Данные формы обратной связи #%s успешно обновлены.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Данные формы обратной связи #%s не обновлены.', $id))->withInput();
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application|RedirectResponse
     */
    public function destroy($id): \Illuminate\Foundation\Application|View|Factory|Application|RedirectResponse
    {
        try {
            $deleteContactForm = $this->contactFormService->processDestroy(id: $id, repository: $this->contactFormRepository);

            if ($deleteContactForm) {
                return redirect()->route('admin.contact-forms.index')->with('success', sprintf('Форма обратной связи #%s успешно удалена.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Форма обратной связи #%s не удалена.', $id));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }
}
