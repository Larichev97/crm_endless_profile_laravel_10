<?php

namespace App\Http\Controllers\Api\V1\QrProfile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QrProfileResource;
use App\Models\QrProfile;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\CrudActionsServices\QrProfileService;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

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
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="country", type="string", example="Украина"),
 *                 @OA\Property(property="city", type="string", example="Днепр"),
 *                 @OA\Property(property="birth_date", type="string", example="25.05.1985"),
 *                 @OA\Property(property="death_date", type="string", example="16.11.2020"),
 *                 @OA\Property(property="full_name", type="string", example="Тестовая Мария Тестовна"),
 *                 @OA\Property(property="firstname", type="string", example="Мария"),
 *                 @OA\Property(property="lastname", type="string", example="Тестовая"),
 *                 @OA\Property(property="surname", type="string", example="Тестовна"),
 *                 @OA\Property(property="cause_death", type="string", example="Сердечный приступ"),
 *                 @OA\Property(property="profession", type="string", example="Секретарь"),
 *                 @OA\Property(property="hobbies", type="string", example="Рисование, велосипед, кино"),
 *                 @OA\Property(property="biography", type="string", example="Мария — творческая натура, обожающая рисовать утренние пейзажи и городские силуэты. В свободное время любит покататься на велосипеде, исследуя улочки города. Часто посещает кинотеатры и наслаждается просмотром разнообразных фильмов. В течение нескольких лет работала в качестве секретаря в уютном офисе, где находила радость в организации рабочего процесса и поддержке коллег."),
 *                 @OA\Property(property="last_wish", type="string", example="Мария желала, чтобы ее последнее мгновение было окружено уютом, мелодиями любимой музыки и в объятиях близких людей."),
 *                 @OA\Property(property="favourite_music_artist", type="string", example="The Beatles"),
 *                 @OA\Property(property="link", type="string", example="http://test.com"),
 *                 @OA\Property(property="geo_latitude", type="string", example="-37.397325"),
 *                 @OA\Property(property="geo_longitude", type="string", example="92.499078"),
 *                 @OA\Property(property="photo_file_name", type="string", example="1703167599.jpg"),
 *                 @OA\Property(property="photo_file_path", type="string", example="http://crm-qr-laravel.loc/storage/qr/1/1703167599.jpg"),
 *                 @OA\Property(property="voice_message_file_name", type="string", example="test_voice.mp3"),
 *                 @OA\Property(property="voice_message_file_path", type="string", example="http://crm-qr-laravel.loc/storage/qr/1/test_voice.mp3"),
 *                 @OA\Property(property="qr_code_file_name", type="string", example="qr_code.png"),
 *                 @OA\Property(property="qr_code_file_path", type="string", example="http://crm-qr-laravel.loc/storage/qr/1/qr_code.png"),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request",
 *     ),
 *     @OA\Response(
 *          response=404,
 *          description="Not Found 404",
 *          @OA\JsonContent(
 *              @OA\Property(property="error", type="bool", example=true),
 *              @OA\Property(property="message", type="string", example="QR Profile not found."),
 *              @OA\Property(property="id", type="integer", example="1"),
 *          ),
 *      ),
 * )
 *
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
     * Display the specified resource.
     *
     * @param $id
     * @return QrProfileResource|JsonResponse
     */
    public function show($id): QrProfileResource|JsonResponse
    {
        try {
            $qrProfile = $this->qrProfileRepository->getForEditModel((int) $id, true);

            if (empty($qrProfile)) {
                return response()->json(['error' => true, 'message' => 'QR Profile not found.', 'id' => (int) $id], 404);
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $qrProfile->setPhotoPath($this->fileService->processGetFrontPublicFilePath($qrProfile->getPhotoName(), $publicDirPath));
            $qrProfile->setVoiceMessagePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getVoiceMessageFileName(), $publicDirPath));
            $qrProfile->setQrCodePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getQrCodeFileName(), $publicDirPath));

            return new QrProfileResource($qrProfile);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
