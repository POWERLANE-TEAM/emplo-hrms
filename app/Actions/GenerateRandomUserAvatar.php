<?php

namespace App\Actions;

class GenerateRandomUserAvatar
{
    public function __invoke(string $username)
    {
        return 'https://ui-avatars.com/api?name='.urlencode($username).'&background=random';
    }
}
