<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PushToken extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $primaryKey = 'user_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['id', 'type'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';


}
