<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spam extends Model
{
    use HasFactory;

    public function detect($body)
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    protected function detectInvalidKeywords()
    {
        $invalidKeywords = ['Yahoo Customer Support'];

        foreach ($invalidKeywords as $keyword) {
            if (stripos(request('body'), $keyword) !== false) {
                throw new Exception('Your reply contains spam');
            }
        }
    }
}
