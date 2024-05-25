<?php

namespace App\Http\Controllers\Api\V1\QrProfile;

use App\Exceptions\QrProfile\QrProfileNotFoundJsonException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QrProfileResource;
use App\Models\QrProfile;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\CrudActionsServices\QrProfileService;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Get(
 *     path="/api/v1/qrs/{id}",
 *     summary="Получение данных о конкретном QR-профиле",
 *     tags={"QR Profile"},
 *     @OA\Parameter(
 *         description="ID QR-профиля",
 *         in="path",
 *         name="id",
 *         required=true,
 *         example=1
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1, description="ID клиента, с которым будет связан QR-профиль человека"),
 *                 @OA\Property(property="country", type="string", example="Украина", description="Название страны, в которой был рождён человек из QR-профиля"),
 *                 @OA\Property(property="city", type="string", example="Днепр", description="Название города, в котором был рождён человек из QR-профиля"),
 *                 @OA\Property(property="birth_date", type="string", example="25.05.1985", description="Дата рождения человека из QR-профиля"),
 *                 @OA\Property(property="death_date", type="string", example="16.11.2020", description="Дата смерти человека из QR-профиля"),
 *                 @OA\Property(property="full_name", type="string", example="Тестовая Мария Тестовна", description="ФИО человека из QR-профиля"),
 *                 @OA\Property(property="firstname", type="string", example="Мария", description="Имя человека из QR-профиля"),
 *                 @OA\Property(property="lastname", type="string", example="Тестовая", description="Фамилия человека из QR-профиля"),
 *                 @OA\Property(property="surname", type="string", example="Тестовна", description="Отчество человека из QR-профиля"),
 *                 @OA\Property(property="cause_death", type="string", example="Сердечный приступ", description="Причина смерти человека из QR-профиля")),
 *                 @OA\Property(property="profession", type="string", example="Секретарь", description="Профессия человека из QR-профиля"),
 *                 @OA\Property(property="hobbies", type="string", example="Рисование, велосипед, кино", description="Хобби человека из QR-профиля"),
 *                 @OA\Property(property="biography", type="string", example="Мария — творческая натура, обожающая рисовать утренние пейзажи и городские силуэты. В свободное время любит покататься на велосипеде, исследуя улочки города. Часто посещает кинотеатры и наслаждается просмотром разнообразных фильмов. В течение нескольких лет работала в качестве секретаря в уютном офисе, где находила радость в организации рабочего процесса и поддержке коллег.", description="Биография человека из QR-профиля"),
 *                 @OA\Property(property="last_wish", type="string", example="Мария желала, чтобы ее последнее мгновение было окружено уютом, мелодиями любимой музыки и в объятиях близких людей.", description="Последнее желание человека из QR-профиля"),
 *                 @OA\Property(property="favourite_music_artist", type="string", example="The Beatles", description="Любимая музыкальная группа человека из QR-профиля"),
 *                 @OA\Property(property="link", type="string", example="https://google.com", description="Ссылка на внешний источник"),
 *                 @OA\Property(property="geo_latitude", type="string", example="-37.397325", description="Географическая широта места захоронения [где находится QR-код]"),
 *                 @OA\Property(property="geo_longitude", type="string", example="92.499078", description="Географическая долгота места захоронения [где находится QR-код]"),
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
 *         response=400,
 *         description="Bad request",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found 404",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="bool", example=true),
 *             @OA\Property(property="message", type="string", example="QR Profile not found."),
 *             @OA\Property(property="id", type="integer", example="1"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Внутренняя ошибка сервера",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="bool", example=true),
 *             @OA\Property(property="message", type="string", example="Something went wrong."),
 *         ),
 *      ),
 * ),
 */
class ShowController extends Controller
{
    /**
     * @param FileService $fileService
     * @param QrProfileService $qrProfileService
     * @param QrProfileRepository $qrProfileRepository
     */
    public function __construct(
        readonly FileService $fileService,
        readonly QrProfileService $qrProfileService,
        readonly QrProfileRepository $qrProfileRepository
    )
    {
    }

    /**
     *  Display the specified resource.
     *
     * @param $id
     * @return QrProfileResource|JsonResponse
     * @throws QrProfileNotFoundJsonException
     */
    public function show($id): QrProfileResource|JsonResponse
    {
        try {
            $qrProfile = $this->qrProfileRepository->getForEditModel((int) $id, true);

            if (empty($qrProfile)) {
                throw new QrProfileNotFoundJsonException();
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $qrProfile->setPhotoPath($this->fileService->processGetFrontPublicFilePath($qrProfile->getPhotoName(), $publicDirPath));
            $qrProfile->setVoiceMessagePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getVoiceMessageFileName(), $publicDirPath));
            $qrProfile->setQrCodePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getQrCodeFileName(), $publicDirPath));

            $qrProfileJsonResource = new QrProfileResource($qrProfile);

            return $qrProfileJsonResource->response()->setStatusCode(Response::HTTP_OK);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());

            return response()->json(data: ['error' => true, 'message' => __('Something went wrong.')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
