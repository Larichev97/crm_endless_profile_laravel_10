<?php

namespace App\Http\Controllers\Country;

use App\DataTransferObjects\Country\CountryStoreDTO;
use App\DataTransferObjects\Country\CountryUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Country\CountryStoreRequest;
use App\Http\Requests\Country\CountryUpdateRequest;
use App\Models\Country;
use App\Repositories\Country\CountryRepository;
use App\Services\CrudActionsServices\CountryService;
use App\Services\FileService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * @param FileService $fileService
     * @param CountryService $countryService
     * @param CountryRepository $countryRepository
     * @param string $publicDirPath
     */
    public function __construct(
        readonly FileService $fileService,
        readonly CountryService $countryService,
        readonly CountryRepository $countryRepository,
        readonly string $publicDirPath = 'images/countries'
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
            $countries = $this->countryRepository->getAllWithPaginate(10, (int) $request->get('page', 1), true);

            return view('country.index',compact('countries'));
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
        return view('country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CountryStoreRequest $countryStoreRequest
     * @return RedirectResponse|JsonResponse
     */
    public function store(CountryStoreRequest $countryStoreRequest): RedirectResponse|JsonResponse
    {
        try {
            $countryStoreDTO = new CountryStoreDTO($countryStoreRequest);

            $createCountry = $this->countryService->processStore($countryStoreDTO, $this->fileService);

            if ($createCountry) {
                return redirect()->route('countries.index')->with('success','Страна успешно создана.');
            }

            return back()->with('error','Ошибка! Страна не создана.')->withInput();
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
            $country = $this->countryRepository->getForEditModel((int) $id, true);

            if (empty($country)) {
                abort(404);
            }

            /** @var Country $country */
            $countryPublicDirPath = $this->publicDirPath.'/'.$country->getKey();

            $flagFilePath = $this->fileService->processGetPublicFilePath($country->getFlagName(), $countryPublicDirPath);

            return view('country.show',compact(['country', 'flagFilePath']));
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
            $country = $this->countryRepository->getForEditModel((int) $id, true);

            if (empty($country)) {
                abort(404);
            }

            /** @var Country $country */
            $countryPublicDirPath = $this->publicDirPath.'/'.$country->getKey();

            $flagFilePath = $this->fileService->processGetPublicFilePath($country->getFlagName(), $countryPublicDirPath);

            return view('country.edit',compact(['country', 'flagFilePath',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CountryUpdateRequest $countryUpdateRequest
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function update(CountryUpdateRequest $countryUpdateRequest, $id): RedirectResponse|JsonResponse
    {
        try {
            $countryUpdateDTO = new CountryUpdateDTO($countryUpdateRequest, (int) $id);

            $updateCountry = $this->countryService->processUpdate($countryUpdateDTO, $this->fileService, $this->countryRepository);

            if ($updateCountry) {
                return redirect()->route('countries.index')->with('success', sprintf('Данные страны #%s успешно обновлены.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Данные страны #%s не обновлены.', $id))->withInput();
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
            $deleteCountry = $this->countryService->processDestroy($id, $this->countryRepository);

            if ($deleteCountry) {
                return redirect()->route('countries.index')->with('success', sprintf('Страна #%s успешно удалена.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Страна #%s не удалена.', $id));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
