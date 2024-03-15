<?php

namespace App\DataTransferObjects\ContactForm;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\ContactForm\ContactFormUpdateRequest;

final readonly class ContactFormUpdateDTO implements FormFieldsDtoInterface
{
    public int $id_status;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $phone_number;
    public string $comment;
    public int $id_employee;

    /**
     * @param ContactFormUpdateRequest $contactFormUpdateRequest
     * @param int $idContactForm
     */
    public function __construct(ContactFormUpdateRequest $contactFormUpdateRequest, public int $idContactForm)
    {
        $this->id_status = (int) $contactFormUpdateRequest->validated('id_status');
        $this->firstname = (string) $contactFormUpdateRequest->validated('firstname');
        $this->lastname = (string) $contactFormUpdateRequest->validated('lastname');
        $this->email = (string) $contactFormUpdateRequest->validated('email');
        $this->phone_number = preg_replace('/[. ()-]*/','', $contactFormUpdateRequest->validated('phone_number'));
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
