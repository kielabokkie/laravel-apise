<?php

namespace Kielabokkie\GuzzleApiService\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_logs';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
