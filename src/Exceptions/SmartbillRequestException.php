<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use Illuminate\Http\Client\Response;

/**
 * Smartbill rejected the request before any invoicing logic ran: an unknown field
 * name, a value of the wrong type, or malformed JSON. These bodies carry no
 * errorText — the offending field is named in errors[].param.
 */
class SmartbillRequestException extends SmartbillApiException
{
    public static function matches(Response $response): bool
    {
        $body = $response->json();

        return is_array($body) && ($body['type'] ?? null) === 'invalid_request_error';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getErrors(): array
    {
        $body = $this->response->json();
        $errors = is_array($body) ? ($body['errors'] ?? []) : [];

        return is_array($errors) ? array_values(array_filter($errors, 'is_array')) : [];
    }

    /**
     * The field Smartbill could not map, e.g. "zzz" or "products[0].quantity".
     */
    public function getParam(): ?string
    {
        return $this->firstString('param');
    }

    /**
     * One of json_mapping_error, json_parse_error, invalid_accept_header,
     * invalid_content_type, method_not_allowed, resource_not_found.
     */
    public function getErrorCode(): ?string
    {
        return $this->firstString('code');
    }

    protected function resolveMessage(): string
    {
        $message = $this->firstString('message');

        if ($message === null) {
            return 'Smartbill rejected the request';
        }

        $param = $this->firstString('param');

        return $param === null ? $message : $message.' ('.$param.')';
    }

    protected function firstString(string $key): ?string
    {
        $value = $this->getErrors()[0][$key] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }
}
