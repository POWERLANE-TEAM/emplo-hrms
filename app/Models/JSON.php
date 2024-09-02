<?php

namespace App\Models;

use Illuminate\Support\Facades\Vite;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\TryCatch;

class JSON extends Model
{

    public function get($reqtedData)
    {
        try {
            $data = file_get_contents(Vite::asset('resources/js/' . $reqtedData . '.json'));
            $data = json_decode($data, true);
            echo json_encode($data);
        } catch (\Throwable $th) {
            abort(404, $th);
        }
    }
}
