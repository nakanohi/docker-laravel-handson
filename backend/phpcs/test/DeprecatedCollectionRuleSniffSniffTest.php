<?php

declare(strict_types=1);

namespace Phpcs\Test;

use App\Models\LoginUser;
use App\Utils\CollectionUnique;

class DeprecatedCollectionRuleSniffSniffTest
{
    public function test(): void
    {
        $array = ['3', '2', '1', '4'];
        $cols = collect($array);

        // warning
        $cols->unique();

        // warning
        $login_user = LoginUser::all();
        $login_user->unique();

        // no warning key指定がある場合はエラーにしない
        $login_user->unique('id');

        // no warning
        array_unique($array);

        // no warning
        CollectionUnique::unique($cols);
    }
}
