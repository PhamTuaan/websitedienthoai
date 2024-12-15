<?php
class ContactModel extends BaseModel 
{
    public function createContact($data) 
    {
        return $this->create('contacts', [
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message']
        ]);
    }
}