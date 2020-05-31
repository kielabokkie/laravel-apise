<?php

namespace Kielabokkie\Apise\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'apise_logs';

    /**
     * The name of the "updated_at" column.
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

    /**
     * Convert query_params attribute to an object.
     *
     * @param string $value
     * @return void
     */
    public function getQueryParamsAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Convert request_headers attribute to an object.
     *
     * @param string $value
     * @return void
     */
    public function getRequestHeadersAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Convert request_body attribute to an object.
     *
     * @param string $value
     * @return void
     */
    public function getRequestBodyAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Convert response_headers attribute to an object.
     *
     * @param string $value
     * @return void
     */
    public function getResponseHeadersAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Convert response_body attribute to an object.
     *
     * @param string $value
     * @return void
     */
    public function getResponseBodyAttribute($value)
    {
        return json_decode($value);
    }
}
