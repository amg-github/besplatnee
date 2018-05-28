<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use App\Application;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;

class ApiCheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authToken = $request->bearerToken();

        try {
            // проверка валидности токена
            $this->payloadIsValid(
                  // JWT::decode  принимает строку с токеном первым аргументом
                  // затем ключ, которым закодирован токен
                  //  и список алгоритмов
                $payload = (array) JWT::decode($authToken, $_ENV['APP_TOKEN_SECRET'], ['HS256'])
            );

            $app = Application::where('key', $payload['sub'])->firstOrFail();
        } catch (\Firebase\JWT\ExpiredException $e) {
            return response([
                'success' => false,
                'message' => 'token_expired'
            ], 401);
        } catch (\Throwable $e) {
            return response([
                'success' => false,
                'message' => 'token_invalid'
            ], 401);
        }

        if (! $app->active) {
            return response([
                'success' => false,
                'message' => 'app_inactive'
            ], 403);
        }

         // Получив инстанс аутентифицированного приложения
         // передаем его в Request. Это позволит нам 
         // иметь легкий доступ к  инстансу приложения повсеместно.
         $request->merge(['__authenticatedApp' => $app]);

        return $next($request);
    }

    private function payloadIsValid($payload)
    {
        $validator = Validator::make($payload, [
            'iss' => 'required|in:' . $_ENV['APP_NAME'],
            'sub' => 'required',
        ]);

        if (! $validator->passes()) {
            throw new \InvalidArgumentException;
        }
    }
}
