<?php

namespace App\DataTransferObjects\ContactForm;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Enums\ContactFormStatusEnum;
use App\Http\Requests\ContactForm\ContactFormStoreRequest;

final readonly class ContactFormStoreDTO implements FormFieldsDtoInterface
{
    public int $id_status;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $phone_number;
    public string $comment;
    public int $id_employee;

    /**
     * @param ContactFormStoreRequest $contactFormStoreRequest
     */
    public function __construct(ContactFormStoreRequest $contactFormStoreRequest)
    {
        $this->id_status = ContactFormStatusEnum::NEW->value;
        $this->firstname = (string) $contactFormStoreRequest->validated('firstname');
        $this->lastname = (string) $contactFormStoreRequest->validated('lastname');
        $this->email = (string) $contactFormStoreRequest->validated('email');
        $this->phone_number = preg_replace('/[. ()-]*/','', $contactFormStoreRequest->validated('phone_number'));
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
