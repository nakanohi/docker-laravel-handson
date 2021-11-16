<?php

declare(strict_types=1);

namespace Phpcs\Test;

use App;
use App\Enums;
use App\Enums\HttpStatusCode;
use Symfony;
use Symfony\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class DeprecatedClassUseRuleSniffSniffTest
{
    /**
     * TODO このメソッド内の書き方もエラーにしたい
     */
    public function test(): void
    {
        \App\Enums\HttpStatusCode::OK;
        App\Enums\HttpStatusCode::OK;
        Enums\HttpStatusCode::OK;
        HttpStatusCode::OK;

        \Symfony\Component\HttpFoundation\Response::HTTP_OK;
        Symfony\Component\HttpFoundation\Response::HTTP_OK;
        Component\HttpFoundation\Response::HTTP_OK;
        HttpFoundation\Response::HTTP_OK;
        Response::HTTP_OK;
    }
}
