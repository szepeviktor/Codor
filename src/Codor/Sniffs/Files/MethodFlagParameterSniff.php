<?php declare(strict_types = 1);

namespace Codor\Sniffs\Files;

use PHP_CodeSniffer\Sniffs\Sniff as PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer\Files\File as PHP_CodeSniffer_File;

class MethodFlagParameterSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * The two token types we're looking for.
     * @var array
     */
    protected $booleans = ['T_FALSE', 'T_TRUE'];

    /**
     * Returns the token types that this sniff is interested in.
     * @return array
     */
    public function register(): array
    {
        return [T_FUNCTION];
    }

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param integer              $stackPtr  The position in the stack where
     *                                    the token was found.
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens           = $phpcsFile->getTokens();

        $token            = $tokens[$stackPtr];
        $openParenIndex   = $token['parenthesis_opener'];
        $closedParenIndex = $token['parenthesis_closer'];

        for ($index=$openParenIndex+1; $index <= $closedParenIndex; $index++) {
            if (in_array($tokens[$index]['type'], $this->booleans)) {
                $phpcsFile->addError("Function/method contains a flag parameter.", $stackPtr, __CLASS__);
                continue;
            }
        }
    }
}
