<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'endpoint',
        'method',
        'payload',
        'response',
        'http_status',
        'error',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Static method to create a new log entry.
     *
     * @param string $endpoint
     * @param string $method
     * @param array|null $payload
     * @param array|null $response
     * @param int|null $httpStatus
     * @param string|null $error
     * @return self
     */
    public static function log(
        string $endpoint,
        string $method,
        ?array $payload = null,
        ?array $response = null,
        ?int $httpStatus = null,
        ?string $error = null
    ): self {
        return self::create([
            'endpoint' => $endpoint,
            'method' => $method,
            'payload' => $payload,
            'response' => $response,
            'http_status' => $httpStatus,
            'error' => $error,
        ]);
    }
}
