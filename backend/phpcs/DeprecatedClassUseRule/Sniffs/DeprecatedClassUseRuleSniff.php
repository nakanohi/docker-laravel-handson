<?php

declare(strict_types=1);

namespace Phpcs\DeprecatedHelperRule\Sniffs;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DeprecatedClassUseRuleSniff implements Sniff
{
    /**
     * @var array 非推奨のUseと代替クラス
     */
    private $deprecated = [
        'App\\Enums\\HttpStatusCode' => 'Illuminate\\Http\\Response',
        'Symfony\\Component\\HttpFoundation\\Response' => 'Illuminate\\Http\\Response',
    ];


    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [T_USE];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $ns = $this->createNamespace($phpcsFile->getTokens(), $stackPtr);
        if (isset($this->deprecated[$ns])) {
            $error = 'Deprecated Class Use; found "%s", please replace with "%s"';
            $data = [$ns, $this->deprecated[$ns]];
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }
    }

    /**
     * @param array $tokens
     * @param int $stackPtr
     * @return string
     */
    private function createNamespace(array $tokens, int $stackPtr): string
    {
        $ns = '';
        $next = $stackPtr + 1;

        // セミコロンまでループ
        while ($tokens[$next]['type'] !== 'T_SEMICOLON') {
            // 空白は無視して次を見る
            if ($tokens[$next]['type'] === 'T_WHITESPACE') {
                $next++;
                continue;
            }

            if ($tokens[$next]['type'] === 'T_NS_SEPARATOR') {
                // バックスラッシュはエスケープしないとダメ
                $ns .= '\\';
            } else {
                $ns .= $tokens[$next]['content'];
            }

            $next++;
        }

        return $ns;
    }
}
