<?php

declare(strict_types=1);

namespace Phpcs\DeprecatedHelperRule\Sniffs;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DeprecatedFacadeRuleSniff implements Sniff
{
    /**
     * @var array 非推奨のFacadeと代替関数
     */
    private $deprecated = [
        // ex) 'Redis' => 'app(\'redis\')',
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
            // "use Facade;" も引っかかる
            // "Facade::"だけ引っ掛けるようにしたいので、次のトークン位置がWコロンならNGにする
            $next = $stackPtr + 1;
            if (isset($tokens[$next]) && $tokens[$next]['type'] === 'T_DOUBLE_COLON') {
                $error = 'Deprecated Facade; found "%s", please replace with "%s"';
                $data = [$content, $this->deprecated[$content]];
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            }
        }
    }
}
