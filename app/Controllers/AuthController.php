<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Controllers;

use JR\ChefsDiary\Enums\HttpStatusCode;
use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\Shared\Helpers\BooleanHelper;
use JR\ChefsDiary\Enums\LogoutAttemptStatusEnum;
use JR\ChefsDiary\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use JR\ChefsDiary\DataObjects\Data\RegisterUserData;
use JR\ChefsDiary\Enums\RefreshTokenAttemptStatusEnum;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\RequestValidators\TwoFactorLoginRequestValidator;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Shared\ResponseFormatter\ResponseFormatter;
use JR\ChefsDiary\RequestValidators\Auth\UserLoginRequestValidator;
use JR\ChefsDiary\RequestValidators\RequestValidatorFactoryInterface;
use JR\ChefsDiary\RequestValidators\Auth\RegisterUserRequestValidator;

class AuthController
{
    public function __construct(
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly AuthServiceInterface $authService,
        private readonly ResponseFormatter $responseFormatter
    ) {
    }

    /**
     * Register new user
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @author Jan Ribka
     */
    public function register(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(RegisterUserRequestValidator::class)
            ->validate(
                $request->getParsedBody()
            );

        $this->authService->register(
            new RegisterUserData(
                $data['login'],
                $data['email'],
                $data['password']
            )
        );

        return $response->withStatus(HttpStatusCode::CREATED->value);
    }

    /**
     * Login user
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @author Jan Ribka
     */
    public function login(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(UserLoginRequestValidator::class)->validate(
            $request->getParsedBody()
        );

        $status = $this->authService->attemptLogin($data);

        if ($status === AuthAttemptStatusEnum::FAILED) {
            throw new ValidationException(['unauthorized' => ['incorrectLoginPassword']], HttpStatusCode::UNAUTHORIZED->value);
        }

        if ($status === AuthAttemptStatusEnum::DISABLED) {
            throw new ValidationException(['forbidden' => ['accessDenied']], HttpStatusCode::FORBIDDEN->value);
        }

        if ($status === AuthAttemptStatusEnum::TWO_FACTOR) {
            return $this->responseFormatter->asJson($response, ['twoFactor' => true]);
        }

        return $this->responseFormatter->asJson($response, $status);
    }

    /**
     * Two factor login
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @author Jan Ribka
     */
    public function twoFactorLogin(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(TwoFactorLoginRequestValidator::class)->validate(
            $request->getParsedBody()
        );

        if (!$this->authService->attemptTwoFactorLogin($data)) {
            throw new ValidationException(['code' => ['invalidCode']], HttpStatusCode::UNAUTHORIZED->value);
        }

        return $response;
    }

    /**
     * Logout user
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @author Jan Ribka
     */
    public function logout(Response $response): Response
    {
        $status = $this->authService->attemptLogout();

        if ($status === LogoutAttemptStatusEnum::NO_COOKIE) {
            throw new ValidationException(['noContent' => ['noCookie']], HttpStatusCode::NO_CONTENT->value);
        }

        if ($status === LogoutAttemptStatusEnum::NO_USER) {
            throw new ValidationException(['forbidden' => ['noUser']], HttpStatusCode::FORBIDDEN->value);
        }

        return $response->withStatus(HttpStatusCode::NO_CONTENT->value);
    }

    /**
     * Refresh token
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @author Jan Ribka
     */
    public function refreshToken(Request $request, Response $response): Response
    {
        $parseBoolean = BooleanHelper::parse();

        $queryParams = $request->getQueryParams();
        $persistLogin = $parseBoolean($queryParams['persistLogin']);
        $data = ['persistLogin' => $persistLogin];

        $status = $this->authService->attemptRefreshToken($data);

        if ($status === RefreshTokenAttemptStatusEnum::NO_COOKIE) {
            throw new ValidationException(['unauthorized' => ['noCookie']], HttpStatusCode::UNAUTHORIZED->value);
        }

        if ($status === RefreshTokenAttemptStatusEnum::NO_USER) {
            throw new ValidationException(['forbidden' => ['noUser']], HttpStatusCode::FORBIDDEN->value);
        }

        if ($status === RefreshTokenAttemptStatusEnum::USER_NOT_EQUAL) {
            throw new ValidationException(['forbidden' => ['invalidToken']], HttpStatusCode::FORBIDDEN->value);
        }


        return $this->responseFormatter->asJson($response, $status);
    }

    public function test(Request $request, Response $response): Response
    {
        // TODO: Pro import pou6iju validaci UploadReceiptRequestValidator z tutorialu a vytvo59m na to service. Bude validace na obraky a zvlast na ostatni soubory
        return $response->withStatus(HttpStatusCode::OK->value);
    }
}