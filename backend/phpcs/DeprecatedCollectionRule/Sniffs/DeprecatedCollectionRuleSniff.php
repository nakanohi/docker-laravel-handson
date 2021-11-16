<?php

declare(strict_types=1);

namespace Phpcs\DeprecatedCollectionRule\Sniffs;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Illuminate\Support\Collection::uniqueの代替メソッドの
 * App\Utils\CollectionUnique::uniqueを使用するようにWarningを
 * を出すルールを追加しました。
 *
 * @todo phpcs2のciの設定に、allow_failure: trueが追加されたら、phpcs-rule.xmlからこのルールを削除して、phpcs/phpcs.xmlにこのルールを追加する。
 * (phpcs2では、warningでもfailedになってしまうため、このルールはphpcsでのみ動くようにしています。)
 *
 * @see https://redmine.kaonavi.jp/issues/19422
 */
class DeprecatedCollectionRuleSniff implements Sniff
{
    /**
     * @var array
     */
    private $deprecated = [
        'Illuminate\Support\Collection::unique' => 'App\\Utils\\CollectionUnique::unique'
    ];


    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [T_STRING];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $content = trim($tokens[$stackPtr]['content']);

        if ($content === "unique") {
            if ($tokens[$stackPtr - 1]['type'] !== 'T_DOUBLE_COLON') {
                // Collection::unique()を使用していても、keyの指定がある場合は、Warningを出さない
                if ($tokens[$stackPtr + 1]['type'] === 'T_OPEN_PARENTHESIS' && $tokens[$stackPtr + 2]['type'] === 'T_CLOSE_PARENTHESIS') {
                    $message = 'This function ("%s") is unrecommended in this project. If possible, please replace with "%s"';
                    $ns = 'Illuminate\Support\Collection::unique';
                    $data = [$ns, $this->deprecated[$ns]];
                    $phpcsFile->addWarning($message, $stackPtr, 'Found', $data);
                }
            }
        }
    }
}
