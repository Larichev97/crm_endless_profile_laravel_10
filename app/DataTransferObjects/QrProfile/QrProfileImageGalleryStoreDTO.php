<?php

namespace App\DataTransferObjects\QrProfile;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\QrProfile\QrProfileImageGalleryStoreRequest;

final readonly class QrProfileImageGalleryStoreDTO implements FormFieldsDtoInterface
{
    public int $id_qr_profile;
    public int $is_active;
    public array $gallery_photos;

    /**
     * @param QrProfileImageGalleryStoreRequest $qrProfileImageGalleryStoreRequest
     */
    public function __construct(QrProfileImageGalleryStoreRequest $qrProfileImageGalleryStoreRequest)
    {
        $this->id_qr_profile = (int) $qrProfileImageGalleryStoreRequest->validated('id_qr_profile');
        $this->is_active = (int) $qrProfileImageGalleryStoreRequest->validated('is_active');
        $this->gallery_photos = (array) $qrProfileImageGalleryStoreRequest->validated('gallery_photos');
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        return [
            'id_qr_profile' => $this->id_qr_profile,
            'is_active' => $this->is_active,
        ];
    }

    /**
     * @return array
     */
    public function getGalleryPhotos(): array
    {
        return !empty($this->gallery_photos) ? $this->gallery_photos : [];
    }
}
