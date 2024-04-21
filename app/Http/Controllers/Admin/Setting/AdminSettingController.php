<?php

namespace App\Http\Controllers\Admin\Setting;

use App\DataTransferObjects\Setting\SettingStoreDTO;
use App\DataTransferObjects\Setting\SettingUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\SettingStoreRequest;
use App\Http\Requests\Setting\SettingUpdateRequest;
use App\Repositories\Setting\SettingRepository;
use App\Services\CrudActionsServices\SettingService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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
     * @return \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
     */
    public function index(Request $request): \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
    {
        try {
            $settings = $this->settingRepository->getAllWithPaginate(10, (int) $request->get('page', 1), 'id', 'asc', true);

            return view('setting.index',compact('settings'));
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
        return view('setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SettingStoreRequest $settingStoreRequest
     * @return RedirectResponse|JsonResponse
     */
    public function store(SettingStoreRequest $settingStoreRequest): RedirectResponse|JsonResponse
    {
        try {
            $settingStoreDTO = new SettingStoreDTO($settingStoreRequest);

            $createSetting = $this->settingService->processStore($settingStoreDTO);

            if ($createSetting) {
                return redirect()->route('admin.settings.index')->with('success','Настройка успешно создана.');
            }

            return back()->with('error','Ошибка! Настройка не создана.')->withInput();
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
            $setting = $this->settingRepository->getForEditModel((int) $id, true);

            if (empty($setting)) {
                abort(404);
            }

            return view('setting.show',compact(['setting',]));
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
            $setting = $this->settingRepository->getForEditModel((int) $id, true);

            if (empty($setting)) {
                abort(404);
            }

            return view('setting.edit',compact(['setting',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SettingUpdateRequest $settingUpdateRequest
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function update(SettingUpdateRequest $settingUpdateRequest, $id): RedirectResponse|JsonResponse
    {
        try {
            $settingUpdateDTO = new SettingUpdateDTO($settingUpdateRequest, (int) $id);

            $updateSetting = $this->settingService->processUpdate($settingUpdateDTO, $this->settingRepository);

            if ($updateSetting) {
                return redirect()->route('admin.settings.index')->with('success', sprintf('Данные настройки #%s успешно обновлены.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Данные настройки #%s не обновлены.', $id))->withInput();
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
            $deleteSetting = $this->settingService->processDestroy($id, $this->settingRepository);

            if ($deleteSetting) {
                return redirect()->route('admin.settings.index')->with('success', sprintf('Настройка #%s успешно удалена.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Настройка #%s не удалена.', $id));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
