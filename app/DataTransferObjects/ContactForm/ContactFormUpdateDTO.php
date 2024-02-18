<?php

namespace App\DataTransferObjects\ContactForm;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\ContactForm\ContactFormUpdateRequest;

final class ContactFormUpdateDTO implements FormFieldsDtoInterface
{
    public readonly int $id_status;
    public readonly string $firstname;
    public readonly string $lastname;
    public readonly string $email;
    public readonly string $phone_number;
    public readonly string $comment;
    public readonly int $id_employee;

    /**
     * @param ContactFormUpdateRequest $contactFormUpdateRequest
     * @param int $idContactForm
     */
    public function __construct(ContactFormUpdateRequest $contactFormUpdateRequest, public readonly int $idContactForm)
    {
        $this->id_status = (int) $contactFormUpdateRequest->validated('id_status');
        $this->firstname = (string) $contactFormUpdateRequest->validated('firstname');
        $this->lastname = (string) $contactFormUpdateRequest->validated('lastname');
        $this->email = (string) $contactFormUpdateRequest->validated('email');
        $this->phone_number = (string) $contactFormUpdateRequest->validated('phone_number');
        $this->comment = strip_tags($contactFormUpdateRequest->validated('comment'));
        $this->id_employee = (int) $contactFormUpdateRequest->validated('id_employee');
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
