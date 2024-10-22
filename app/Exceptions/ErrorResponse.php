<?php

namespace App\Exceptions;
use Illuminate\Http\JsonResponse;
use Throwable;


class ErrorResponse
{

    const INTERNAL_SERVER_ERROR = 500;
    private const ERR_FILE = 'file';
    private const ERR_LINE = 'line';
    private const ERR_INFO = 'info';
    private const ERR_MSG = 'message';
    private const ERR_CODE = 'code';
    private const ERR_API_CODE = 'apiCode';
    public const BAD_REQUEST = 400;

    public static function createFrom(Throwable $exception): JsonResponse
    {
        $handler = app(Handler::class);
        $originalCode = $exception->getCode();
        $code = $originalCode != 0 && !is_string($originalCode) ? $originalCode : self::INTERNAL_SERVER_ERROR;
        $message = $exception->getMessage();

        $data = [
            self::ERR_MSG => $message,
            self::ERR_CODE => $code,
        ];
        if ($exception instanceof ApiException) {
            $data[self::ERR_API_CODE] = $exception->apiCode;
        }
        if (in_array(env('APP_ENV'), ['stage', 'dev', 'local'])) {
            $trace = $exception->getTrace()[0];
            $data[self::ERR_INFO] = [
                self::ERR_FILE => array_key_exists(self::ERR_FILE, $trace) ? $trace[self::ERR_FILE] : '',
                self::ERR_LINE => array_key_exists(self::ERR_LINE, $trace) ? $trace[self::ERR_LINE] : ''
            ];
        }

        return response()->json($data, $code);
    }
}
