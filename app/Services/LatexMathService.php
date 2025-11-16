<?php

namespace App\Services;

use Exception;

class LatexMathService
{
    /**
     * Validate LaTeX mathematical expression
     */
    public static function validateLatexExpression(string $expression): array
    {
        $result = [
            'valid' => false,
            'normalized' => '',
            'error' => '',
            'suggestions' => []
        ];

        // Remove whitespace and normalize
        $expression = trim($expression);
        
        if (empty($expression)) {
            $result['error'] = 'Expression cannot be empty';
            return $result;
        }

        // Check for basic LaTeX math patterns
        if (!self::containsLatexMath($expression)) {
            $result['error'] = 'Expression must contain valid LaTeX mathematical notation';
            $result['suggestions'] = self::getLatexSuggestions($expression);
            return $result;
        }

        // Validate LaTeX syntax
        $validation = self::validateLatexSyntax($expression);
        if (!$validation['valid']) {
            return $validation;
        }

        // Normalize the expression
        $normalized = self::normalizeLatexExpression($expression);
        
        $result['valid'] = true;
        $result['normalized'] = $normalized;
        
        return $result;
    }

    /**
     * Check if expression contains LaTeX math patterns
     */
    private static function containsLatexMath(string $expression): bool
    {
        // Common LaTeX math patterns
        $patterns = [
            '/\\\\frac{.*?}{.*?}/',      // \frac{numerator}{denominator}
            '/\\\\sqrt{.*?}/',            // \sqrt{expression}
            '/\\\\sqrt\[.*?\]{.*?}/',      // \sqrt[n]{expression}
            '/\\\\[a-zA-Z]+\*?/',         // \function
            '/\^{.*?}/',                  // superscript
            '/_{.*?}/',                   // subscript
            '/\\\\begin{.*?}.*?\\\\end{.*?}/', // environments
            '/\\\\[{}]/',                 // escaped braces
            '/\\\\[\\\\&%$#_]/',          // escaped special chars
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $expression)) {
                return true;
            }
        }

        // Check for math symbols
        $mathSymbols = ['α', 'β', 'γ', 'δ', 'π', 'θ', 'λ', 'μ', 'σ', 'φ', 'ω', '∞', '∑', '∏', '∫', '∂', '∇', '±', '×', '÷', '≠', '≤', '≥', '≈', '∝'];
        foreach ($mathSymbols as $symbol) {
            if (strpos($expression, $symbol) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate LaTeX syntax
     */
    private static function validateLatexSyntax(string $expression): array
    {
        $result = ['valid' => false, 'error' => '', 'suggestions' => []];

        // Check for balanced braces using a stack
        $stack = [];
        $inEscape = false;
        $chars = str_split($expression);
        
        for ($i = 0; $i < count($chars); $i++) {
            $char = $chars[$i];
            
            if ($inEscape) {
                $inEscape = false;
                continue;
            }
            
            if ($char === '\\') {
                $inEscape = true;
                continue;
            }
            
            if ($char === '{') {
                array_push($stack, '{');
            } elseif ($char === '}') {
                if (empty($stack)) {
                    $result['error'] = 'Unbalanced braces: closing brace without opening brace';
                    $result['suggestions'][] = 'Check that all { have matching }';
                    return $result;
                }
                array_pop($stack);
            }
        }
        
        if (!empty($stack)) {
            $result['error'] = 'Unbalanced braces: missing closing brace(s)';
            $result['suggestions'][] = 'Check that all { have matching }';
            return $result;
        }

        // Check for balanced brackets using a stack
        $stack = [];
        $inEscape = false;
        
        for ($i = 0; $i < count($chars); $i++) {
            $char = $chars[$i];
            
            if ($inEscape) {
                $inEscape = false;
                continue;
            }
            
            if ($char === '\\') {
                $inEscape = true;
                continue;
            }
            
            if ($char === '[') {
                array_push($stack, '[');
            } elseif ($char === ']') {
                if (empty($stack)) {
                    $result['error'] = 'Unbalanced brackets: closing bracket without opening bracket';
                    $result['suggestions'][] = 'Check that all [ have matching ]';
                    return $result;
                }
                array_pop($stack);
            }
        }
        
        if (!empty($stack)) {
            $result['error'] = 'Unbalanced brackets: missing closing bracket(s)';
            $result['suggestions'][] = 'Check that all [ have matching ]';
            return $result;
        }

        // Validate common LaTeX commands
        $invalidCommands = self::findInvalidLatexCommands($expression);
        if (!empty($invalidCommands)) {
            $result['error'] = 'Invalid LaTeX commands: ' . implode(', ', $invalidCommands);
            $result['suggestions'] = self::getValidCommandSuggestions($invalidCommands);
            return $result;
        }

        $result['valid'] = true;
        return $result;
    }

    /**
     * Normalize LaTeX expression for comparison
     */
    private static function normalizeLatexExpression(string $expression): string
    {
        // Remove extra whitespace
        $normalized = preg_replace('/\s+/', ' ', $expression);
        
        // Normalize common patterns
        $replacements = [
            // Spaces around operators
            '/([+\-*=\/])/' => ' $1 ',
            // Multiple spaces to single space
            '/\s+/' => ' ',
            // Remove spaces after backslash
            '/\\\\(\s+)/' => '\\\\',
            // Normalize fractions
            '/\\\\frac\s*{\s*(.*?)\s*}\s*{\s*(.*?)\s*}/' => '\\\\frac{$1}{$2}',
            // Normalize square roots
            '/\\\\sqrt\s*{\s*(.*?)\s*}/' => '\\\\sqrt{$1}',
            '/\\\\sqrt\s*\[\s*(.*?)\s*\]\s*{\s*(.*?)\s*}/' => '\\\\sqrt[$1]{$2}',
            // Normalize subscripts and superscripts
            '/_\s*{\s*(.*?)\s*}/' => '_{$1}',
            '/\^\s*{\s*(.*?)\s*}/' => '^{$1}',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $normalized = preg_replace($pattern, $replacement, $normalized);
        }

        return trim($normalized);
    }

    /**
     * Find invalid LaTeX commands
     */
    private static function findInvalidLatexCommands(string $expression): array
    {
        // Extract all LaTeX commands
        preg_match_all('/\\\\([a-zA-Z]+)/', $expression, $matches);
        $commands = array_unique($matches[1]);

        // List of valid math commands
        $validCommands = [
            'frac', 'sqrt', 'sum', 'prod', 'int', 'lim', 'sin', 'cos', 'tan',
            'cot', 'sec', 'csc', 'arcsin', 'arccos', 'arctan', 'ln', 'log',
            'exp', 'abs', 'max', 'min', 'arg', 'Re', 'Im', 'mod', 'gcd',
            'lcm', 'binom', 'choose', 'partial', 'nabla', 'infty', 'pi',
            'alpha', 'beta', 'gamma', 'delta', 'epsilon', 'zeta', 'eta',
            'theta', 'iota', 'kappa', 'lambda', 'mu', 'nu', 'xi', 'omicron',
            'pi', 'rho', 'sigma', 'tau', 'upsilon', 'phi', 'chi', 'psi',
            'omega', 'Gamma', 'Delta', 'Theta', 'Lambda', 'Xi', 'Pi',
            'Sigma', 'Upsilon', 'Phi', 'Psi', 'Omega', 'left', 'right',
            'begin', 'end', 'text', 'mathrm', 'mathbf', 'mathit', 'mathsf',
            'mathtt', 'mathbb', 'mathcal', 'mathfrak', 'mathscr', 'mathrm',
            'times', 'div', 'pm', 'mp', 'leq', 'geq', 'neq', 'approx',
            'equiv', 'sim', 'simeq', 'cong', 'propto', 'parallel', 'perp',
            'cup', 'cap', 'setminus', 'complement', 'subset', 'supset',
            'subseteq', 'supseteq', 'in', 'notin', 'ni', 'not', 'land',
            'lor', 'neg', 'implies', 'iff', 'forall', 'exists', 'nexists',
            'angle', 'degree', 'prime', 'backslash', 'ldots', 'cdots',
            'vdots', 'ddots', 'over', 'under', 'acute', 'grave', 'check',
            'hat', 'tilde', 'bar', 'vec', 'dot', 'ddot'
        ];

        return array_diff($commands, $validCommands);
    }

    /**
     * Get suggestions for LaTeX expressions
     */
    private static function getLatexSuggestions(string $expression): array
    {
        $suggestions = [];
        
        // Check if it's a simple number
        if (is_numeric($expression)) {
            $suggestions[] = "For numbers, you can use: $expression";
            return $suggestions;
        }

        // Check for common patterns
        if (strpos($expression, '/') !== false) {
            $suggestions[] = "For fractions, use: \\frac{numerator}{denominator}";
        }

        if (strpos($expression, 'sqrt') !== false) {
            $suggestions[] = "For square roots, use: \\sqrt{expression}";
        }

        if (preg_match('/\^/', $expression)) {
            $suggestions[] = "For powers, use: base^{exponent}";
        }

        if (preg_match('/_/', $expression)) {
            $suggestions[] = "For subscripts, use: base_{subscript}";
        }

        // Common math functions
        $suggestions[] = "Common functions: \\sin{x}, \\cos{x}, \\tan{x}, \\log{x}, \\ln{x}";
        $suggestions[] = "Greek letters: \\alpha, \\beta, \\gamma, \\delta, \\pi";
        $suggestions[] = "Operators: \\times, \\div, \\pm, \\leq, \\geq, \\neq";

        return $suggestions;
    }

    /**
     * Get suggestions for invalid commands
     */
    private static function getValidCommandSuggestions(array $invalidCommands): array
    {
        $suggestions = [];
        $similarCommands = [
            'sinx' => '\\sin{x}',
            'cosx' => '\\cos{x}',
            'tanx' => '\\tan{x}',
            'logx' => '\\log{x}',
            'lnx' => '\\ln{x}',
            'sqrtx' => '\\sqrt{x}',
            'fraction' => '\\frac{numerator}{denominator}',
            'power' => 'base^{exponent}',
            'subscript' => 'base_{subscript}',
        ];

        foreach ($invalidCommands as $command) {
            if (isset($similarCommands[$command])) {
                $suggestions[] = "Did you mean: {$similarCommands[$command]}?";
            } else {
                $suggestions[] = "Invalid command: \\$command";
            }
        }

        return $suggestions;
    }

    /**
     * Convert LaTeX to display format (for rendering)
     */
    public static function latexToDisplay(string $expression): string
    {
        // This would integrate with MathJax or KaTeX for actual rendering
        // For now, return the expression as-is for display
        return $expression;
    }

    /**
     * Check if two LaTeX expressions are mathematically equivalent
     */
    public static function areEquivalent(string $expr1, string $expr2): bool
    {
        $norm1 = self::normalizeLatexExpression($expr1);
        $norm2 = self::normalizeLatexExpression($expr2);
        
        return $norm1 === $norm2;
    }
}
