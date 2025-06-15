<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationLog extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'integration_logs';

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
        'http_status' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Indica se o modelo deve ser timestamped.
     * Usamos apenas created_at, sem updated_at.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Nome da coluna de timestamp de criação.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

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
