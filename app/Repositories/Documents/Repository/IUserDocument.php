<?php

namespace App\Repositories\Documents\Repository;

interface IUserDocument
{
    public function listDocuments(string $from, string $to);

    public function listDocumentsByUser($id);

}
