<?php

declare(strict_types=1);

namespace App\Common\EventSubscriber\Exception;

use App\Common\Exception\Domain\DomainException;
use Exception;
use IWD\Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use IWD\Symfony\PresentationBundle\Exception\PresentationBundleException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'onFormatterException', priority: 1)]
class HttpExceptionSubscriber
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly string $env,
        private readonly bool $debug,
    ) {
    }

    public function onFormatterException(ExceptionEvent $event): void
    {
        $format = (string) $event->getRequest()->attributes->get('_format', 'json');

        $exception = $event->getThrowable();
        try {
            $previous = $exception->getPrevious();
            if (!empty($previous) && is_subclass_of($previous, DomainException::class)) {
                throw $previous;
            }

            throw $exception;
        } catch (DomainException|PresentationBundleException $exception) {
            $response = new Response();
            $response->setContent(
                $this->serializer->serialize(
                    $this->toApiFormat($exception, Response::HTTP_UNPROCESSABLE_ENTITY),
                    $format
                )
            );
            $response->headers->add(['Content-Type' => 'application/' . $format]);
            $event->setResponse($response);
        } catch (\Doctrine\DBAL\Exception $exception) {
            if ($this->isDev() || ($this->isTest() && $this->debug)) {
                return;
            }
            if ($this->isTest() || $this->isProd()) {
                $response = new Response();
                $response->setContent(
                    $this->serializer->serialize(
                        ApiFormatter::prepare(
                            null,
                            Response::HTTP_BAD_REQUEST,
                            ['Bad Request']
                        ),
                        $format
                    )
                );
                $response->headers->add(['Content-Type' => 'application/' . $format]);
                $event->setResponse($response);
            }
        } catch (Throwable $exception) {
            if ($this->isDev() || ($this->isTest() && $this->debug)) {
                return;
            }
            if ($this->isTest() || $this->isProd()) {
                $response = new Response();
                $response->setContent(
                    $this->serializer->serialize(
                        ApiFormatter::prepare(
                            null,
                            Response::HTTP_INTERNAL_SERVER_ERROR,
                            'Internal Server Error'
                        ),
                        $format
                    )
                );
                $response->headers->add(['Content-Type' => 'application/' . $format]);
                $event->setResponse($response);
            }
        }
    }

    protected function isDev(): bool
    {
        return 'dev' === $this->env;
    }

    protected function isTest(): bool
    {
        return 'test' === $this->env;
    }

    protected function isProd(): bool
    {
        return 'prod' === $this->env;
    }

    protected function toApiFormat(Exception $exception, ?int $code = null): array
    {
        $errors = $this->isValidJson($exception->getMessage())
            ? json_decode($exception->getMessage(), true, 512, JSON_THROW_ON_ERROR)
            : [$exception->getMessage()]
        ;

        return ApiFormatter::prepare(
            [],
            $code ?? $exception->getCode(),
            $errors
        );
    }

    /**
     * @param string $string
     */
    protected function isValidJson($string): bool
    {
        return is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string)));
    }
}
