<?php

namespace App\Http\Controllers\Api\V1\QrProfile;

use App\DataTransferObjects\QrProfile\Api\QrProfileStoreApiDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\QrProfile\Api\QrProfileStoreApiRequest;
use App\Http\Resources\Api\V1\QrProfileResource;
use App\Models\QrProfile;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\CrudActionsServices\Api\QrProfileApiService;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Post(
 *     path="/api/v1/qrs",
 *     summary="Создание нового QR-профиля (необходим Bearer Token)",
 *     tags={"QR Profile"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"id_client", "id_country", "id_city", "firstname", "lastname"},
 *                 @OA\Property(property="id_client", type="integer", example=1, description="ID клиента, с которым будет связан QR-профиль человека"),
 *                 @OA\Property(property="id_country", type="integer", example=1, description="ID страны, в которой был рождён человек из QR-профиля"),
 *                 @OA\Property(property="id_city", type="integer", example=1, description="ID города, в котором был рождён человек из QR-профиля"),
 *                 @OA\Property(property="firstname", type="string", example="Мария", description="Имя человека из QR-профиля"),
 *                 @OA\Property(property="lastname", type="string", example="Тестовая", description="Фамилия человека из QR-профиля"),
 *                 @OA\Property(property="surname", type="string", example="Тестовна", description="Отчество человека из QR-профиля"),
 *                 @OA\Property(property="birth_date", type="string", format="date", example="1985-05-25", description="Дата рождения человека из QR-профиля"),
 *                 @OA\Property(property="death_date", type="string", format="date", example="2020-11-16", description="Дата смерти человека из QR-профиля"),
 *                 @OA\Property(property="cause_death", type="string", example="Сердечный приступ", description="Причина смерти человека из QR-профиля"),
 *                 @OA\Property(property="profession", type="string", example="Секретарь", description="Профессия человека из QR-профиля"),
 *                 @OA\Property(property="hobbies", type="string", example="Рисование, велосипед, кино", description="Хобби человека из QR-профиля"),
 *                 @OA\Property(property="biography", type="string", example="Мария — творческая натура...", description="Биография человека из QR-профиля"),
 *                 @OA\Property(property="last_wish", type="string", example="Мария желала...", description="Последнее желание человека из QR-профиля"),
 *                 @OA\Property(property="favourite_music_artist", type="string", example="The Beatles", description="Любимая музыкальная группа человека из QR-профиля"),
 *                 @OA\Property(property="link", type="string", format="url", example="https://google.com", description="Ссылка на внешний источник"),
 *                 @OA\Property(property="geo_latitude", type="string", example="-37.397325", description="Географическая широта места захоронения [где будет находится QR-код]"),
 *                 @OA\Property(property="geo_longitude", type="string", example="92.499078", description="Географическая долгота места захоронения [где будет находится QR-код]"),
 *                 @OA\Property(property="photo_file", type="string", format="binary", description="Фотография для QR-профиля. Допускаются форматы: jpg, jpeg, png. Максимальный размер файла 4 МБ."),
 *                 @OA\Property(property="voice_message_file", type="string", format="binary", description="Файл аудиозаписи для QR-профиля. Допускаются форматы: mp3, wav, ogg, flac. Максимальный размер файла 10 МБ."),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="QR-профиль успешно создан",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="country", type="string", example="Украина"),
 *                 @OA\Property(property="city", type="string", example="Днепр"),
 *                 @OA\Property(property="birth_date", type="string", example="1985-05-25"),
 *                 @OA\Property(property="death_date", type="string", example="2020-11-16"),
 *                 @OA\Property(property="full_name", type="string", example="Тестовая Мария Тестовна"),
 *                 @OA\Property(property="firstname", type="string", example="Мария"),
 *                 @OA\Property(property="lastname", type="string", example="Тестовая"),
 *                 @OA\Property(property="surname", type="string", example="Тестовна"),
 *                 @OA\Property(property="cause_death", type="string", example="Сердечный приступ"),
 *                 @OA\Property(property="profession", type="string", example="Секретарь"),
 *                 @OA\Property(property="hobbies", type="string", example="Рисование, велосипед, кино"),
 *                 @OA\Property(property="biography", type="string", example="Мария — творческая натура..."),
 *                 @OA\Property(property="last_wish", type="string", example="Мария желала..."),
 *                 @OA\Property(property="favourite_music_artist", type="string", example="The Beatles"),
 *                 @OA\Property(property="link", type="string", example="https://google.com"),
 *                 @OA\Property(property="geo_latitude", type="string", example="-37.397325"),
 *                 @OA\Property(property="geo_longitude", type="string", example="92.499078"),
 *                 @OA\Property(property="photo_file_name", type="string", example="1703167599.jpg"),
 *                 @OA\Property(property="photo_file_path", type="string", example="https://crm-qr-laravel.loc/storage/qr/1/1703167599.jpg"),
 *                 @OA\Property(property="voice_message_file_name", type="string", example="test_voice.mp3"),
 *                 @OA\Property(property="voice_message_file_path", type="string", example="https://crm-qr-laravel.loc/storage/qr/1/test_voice.mp3"),
 *                 @OA\Property(property="qr_code_file_name", type="string", example="qr_code.png"),
 *                 @OA\Property(property="qr_code_file_path", type="string", example="https://crm-qr-laravel.loc/storage/qr/1/qr_code.png"),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Неавторизованный",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Невалидные данные",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="bool", example=true),
 *             @OA\Property(property="message", type="string", example="Unable to create a Qr Profile due to incorrect data."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Внутренняя ошибка сервера",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="bool", example=true),
 *             @OA\Property(property="message", type="string", example="Something went wrong."),
 *         ),
 *     ),
 *     security={{"bearerAuth":{}}}
 * ),
 */
class StoreController extends Controller
{
    /**
     * @param FileService $fileService
     * @param QrProfileApiService $qrProfileApiService
     * @param QrProfileRepository $qrProfileRepository
     */
    public function __construct(
        readonly FileService $fileService,
        readonly QrProfileApiService $qrProfileApiService,
        readonly QrProfileRepository $qrProfileRepository
    )
    {
    }

    /**
     *  Store a newly created resource in storage.
     *
     * @param QrProfileStoreApiRequest $qrProfileStoreApiRequest
     * @return QrProfileResource|JsonResponse
     */
    public function store(QrProfileStoreApiRequest $qrProfileStoreApiRequest): QrProfileResource|JsonResponse
    {
        try {
            $qrProfile = $this->qrProfileApiService->processStore(dto: new QrProfileStoreApiDTO($qrProfileStoreApiRequest));

            if ($qrProfile instanceof QrProfile && $qrProfile->getKey() > 0) {
                $publicDirPath = 'qr/'.$qrProfile->getKey();

                $qrProfile->setPhotoPath($this->fileService->processGetFrontPublicFilePath($qrProfile->getPhotoName(), $publicDirPath));
                $qrProfile->setVoiceMessagePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getVoiceMessageFileName(), $publicDirPath));
                $qrProfile->setQrCodePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getQrCodeFileName(), $publicDirPath));

                $qrProfileJsonResource = new QrProfileResource($qrProfile);

                return $qrProfileJsonResource->response()->setStatusCode(Response::HTTP_CREATED);
            }

            return response()->json(data: ['error' => true, 'message' => __('Unable to create a Qr Profile due to incorrect data.'),], status: Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());

            return response()->json(data: ['error' => true, 'message' => __('Something went wrong.')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
