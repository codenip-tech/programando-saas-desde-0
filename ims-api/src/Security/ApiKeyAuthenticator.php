<?php

namespace App\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\NoResultException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    function __construct(
        private readonly UserRepository $userRepository,
        private readonly string $jwtSecret,
    ) {}

    private const HEADER_NAME = 'Authorization';
    private const INVALID_AUTH_TOKEN_FORMAT_ERROR = 'Authorization header must be in Bearer JWT format';

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::HEADER_NAME);
    }

    public function authenticate(Request $request): Passport
    {
        $authorizationToken = $request->headers->get(self::HEADER_NAME);
        if (null === $authorizationToken) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }
        [$type, $jwt] = explode(' ', $authorizationToken);
        if ($type !== 'Bearer') {
            throw new CustomUserMessageAuthenticationException('Only Bearer token is accepted');
        }
        if (!$jwt) {
            throw new CustomUserMessageAuthenticationException(self::INVALID_AUTH_TOKEN_FORMAT_ERROR);
        }

        $userEmail = $this->decodeJwt($jwt);

        return new SelfValidatingPassport(new UserBadge($userEmail));
    }

    private function decodeJwt(string $jwt): string {
        try {
            $decoded = JWT::decode($jwt, new Key($this->jwtSecret, 'HS256'));

            $user = $this->userRepository->findOneById($decoded->sub);

            return $user->getEmail();
        } catch (SignatureInvalidException $exception) {
            throw new CustomUserMessageAuthenticationException('Failed to validate JWT');
        } catch (NoResultException $exception) {
            throw new CustomUserMessageAuthenticationException('User not found');
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
