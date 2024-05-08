<?php

namespace App\Http\Controllers\Admin\Setting;

use App\DataTransferObjects\Setting\SettingStoreDTO;
use App\DataTransferObjects\Setting\SettingUpdateDTO;
use App\Exceptions\Setting\SettingNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\SettingStoreRequest;
use App\Http\Requests\Setting\SettingUpdateRequest;
use App\Repositories\Setting\SettingRepository;
use App\Services\CrudActionsServices\SettingService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AdminSettingController extends Controller
{
    /**
     * @param SettingService $settingService
     * @param SettingRepository $settingRepository
     */
    public function __construct(
        readonly SettingService $settingService,
        readonly SettingRepository $settingRepository,
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function index(Request $request): \Illuminate\Foundation\Application|View|Factory|Application
    {
        try {
            $settings = $this->settingRepository->getAllWithPaginate(perPage: 10, page: (int) $request->get('page', 1), orderBy: 'id', orderWay: 'asc');

            return view('setting.index',compact('settings'));
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
        return view('setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SettingStoreRequest $settingStoreRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function store(SettingStoreRequest $settingStoreRequest): Factory|View|\Illuminate\Foundation\Application|Application|RedirectResponse
    {
        try {
            $createSetting = $this->settingService->processStore(dto: new SettingStoreDTO($settingStoreRequest));

            if ($createSetting) {
                return redirect()->route('admin.settings.index')->with('success','Настройка успешно создана.');
            }

            return back()->with('error','Ошибка! Настройка не создана.')->withInput();
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
            $setting = $this->settingRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($setting)) {
                throw new SettingNotFoundException(sprintf('Настройка #%s не найдена.', $id));
            }

            return view('setting.show',compact(['setting',]));
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
            $setting = $this->settingRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($setting)) {
                throw new SettingNotFoundException(sprintf('Настройка #%s не найдена.', $id));
            }

            return view('setting.edit',compact(['setting',]));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SettingUpdateRequest $settingUpdateRequest
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function update(SettingUpdateRequest $settingUpdateRequest, $id): Factory|View|\Illuminate\Foundation\Application|Application|RedirectResponse
    {
        try {
            $updateSetting = $this->settingService->processUpdate(dto: new SettingUpdateDTO(settingUpdateRequest: $settingUpdateRequest, id_setting: (int) $id), repository: $this->settingRepository);

            if ($updateSetting) {
                return redirect()->route('admin.settings.index')->with('success', sprintf('Данные настройки #%s успешно обновлены.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Данные настройки #%s не обновлены.', $id))->withInput();
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Foundation\Application|Factory|View|Application|RedirectResponse
     */
    public function destroy($id): \Illuminate\Foundation\Application|Factory|View|Application|RedirectResponse
    {
        try {
            $deleteSetting = $this->settingService->processDestroy(id: $id, repository: $this->settingRepository);

            if ($deleteSetting) {
                return redirect()->route('admin.settings.index')->with('success', sprintf('Настройка #%s успешно удалена.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Настройка #%s не удалена.', $id));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }
}
