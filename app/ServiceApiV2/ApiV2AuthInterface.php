<?php

namespace App\ServiceApiV2;

use Illuminate\Database\Eloquent\Model;

interface ApiV2AuthInterface
{
    public function findAuthor(array $attributes): ?Model;

    public function createAuthor(array $attributes): ?Model;
}
