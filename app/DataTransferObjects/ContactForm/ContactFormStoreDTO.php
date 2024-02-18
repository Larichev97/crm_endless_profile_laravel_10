<?php

namespace App\DataTransferObjects\ContactForm;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Enums\ContactFormStatusEnum;
use App\Http\Requests\ContactForm\ContactFormStoreRequest;

final class ContactFormStoreDTO implements FormFieldsDtoInterface
{
    public readonly int $id_status;
    public readonly string $firstname;
    public readonly string $lastname;
    public readonly string $email;
    public readonly string $phone_number;
    public readonly string $comment;
    public readonly int $id_employee;

    /**
     * @param ContactFormStoreRequest $contactFormStoreRequest
     */
    public function __construct(ContactFormStoreRequest $contactFormStoreRequest)
    {
        $this->id_status = ContactFormStatusEnum::NEW->value;
        $this->firstname = (string) $contactFormStoreRequest->validated('firstname');
        $this->lastname = (string) $contactFormStoreRequest->validated('lastname');
        $this->email = (string) $contactFormStoreRequest->validated('email');
        $this->phone_number = (string) $contactFormStoreRequest->validated('phone_number');
        $this->comment = strip_tags($contactFormStoreRequest->validated('comment'));
        $this->id_employee = (int) $contactFormStoreRequest->validated('id_employee');
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        return [
            'id_status' => $this->id_status,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'comment' => $this->comment,
            'id_employee' => $this->id_employee,
        ];
    }
}
