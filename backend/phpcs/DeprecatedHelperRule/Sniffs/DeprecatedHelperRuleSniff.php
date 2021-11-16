<?php

declare(strict_types=1);

namespace Phpcs\DeprecatedHelperRule\Sniffs;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DeprecatedHelperRuleSniff implements Sniff
{
    /**
     * @var array 非推奨の関数と代替関数
     */
    private $deprecated = [
        // ex) 'array_add' => '\Illuminate\Support\Arr::add',
        'env' => 'config',
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
        if (isset($this->deprecated[$content])) {
            $error = 'Deprecated Function; found "%s", please replace with "%s"';
            $data = [$content, $this->deprecated[$content]];
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }
    }
}
