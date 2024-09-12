<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDoc extends Model
{
    use HasFactory;

    // pivot table - sort of
    protected $table = 'employee_docs';

    protected $primaryKey = 'emp_doc_id';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'submitted_at';

    // /**
    //  * The name of the "updated at" column.
    //  *
    //  * @var string
    //  */
    // const UPDATED_AT = 'datetime_updated';

    protected $fillable = [
        'emp_doc_id',
        'deleted_at',
    ];
}
