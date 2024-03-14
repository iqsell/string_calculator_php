<?php

$expression = readline('Введите выражение: ');
validateAndCalculate($expression);

function validateAndCalculate($expression)
{
    $error = false;

    if (!preg_match('/^[\d\s\(\)\+\-\*\/\.]+$/', $expression)) {
        echo "Error: Неверные символы в выражении\n";
        $error = true;
    }

    if (strpos($expression, '\\') !== false) {
        echo "Error: Неверный символ\n";
        $error = true;
    }

    if (strpos($expression, '/0') !== false) {
        echo "Error: Деление на ноль\n";
        $error = true;
    }

    if ($error) {
        return;
    }

    $tokens = str_split($expression);
    $stack = [];
    $num = '';

    foreach ($tokens as $token) {
        if (is_numeric($token) || $token == '.') {
            $num .= $token;
        } elseif (!empty($num)) {
            $stack[] = $num;
            $num = '';
        }

        if (in_array($token, ['+', '-', '*', '/'])) {
            $stack[] = $token;
        } elseif ($token == '(') {
            $stack[] = $token;
        } elseif ($token == ')') {
            $temp = [];
            while (($top = array_pop($stack)) != '(') {
                array_unshift($temp, $top);
            }

            $postfix = [];
            $operators = ['+', '-', '*', '/'];
            $precedence = ['+' => 1, '-' => 1, '*' => 2, '/' => 2];
            $tempStack = [];

            foreach ($temp as $token) {
                if (is_numeric($token)) {
                    $postfix[] = $token;
                } elseif (in_array($token, $operators)) {
                    while (!empty($tempStack) && current($tempStack) != '(' && $precedence[current($tempStack)] >= $precedence[$token]) {
                        $postfix[] = array_pop($tempStack);
                    }
                    $tempStack[] = $token;
                }
            }

            while (!empty($tempStack)) {
                $postfix[] = array_pop($tempStack);
            }

            $resultStack = [];

            foreach ($postfix as $token) {
                if (is_numeric($token)) {
                    $resultStack[] = $token;
                } elseif (in_array($token, $operators)) {
                    $b = array_pop($resultStack);
                    $a = array_pop($resultStack);

                    switch ($token) {
                        case '+':
                            $result = $a + $b;
                            break;
                        case '-':
                            $result = $a - $b;
                            break;
                        case '*':
                            $result = $a * $b;
                            break;
                        case '/':
                            if ($b == 0) {
                                echo "Error: Деление на ноль\n";
                                return;
                            }
                            $result = $a / $b;
                            break;
                    }
                    $resultStack[] = $result;
                }
            }

            $stack[] = array_pop($resultStack);
        }
    }

    if (!empty($num)) {
        $stack[] = $num;
    }

    $postfix = [];
    $operators = ['+', '-', '*', '/'];
    $precedence = ['+' => 1, '-' => 1, '*' => 2, '/' => 2];
    $tempStack = [];

    foreach ($stack as $token) {
        if (is_numeric($token)) {
            $postfix[] = $token;
        } elseif (in_array($token, $operators)) {
            while (!empty($tempStack) && current($tempStack) != '(' && $precedence[current($tempStack)] >= $precedence[$token]) {
                $postfix[] = array_pop($tempStack);
            }
            $tempStack[] = $token;
        }
    }

    while (!empty($tempStack)) {
        $postfix[] = array_pop($tempStack);
    }

    $resultStack = [];

    foreach ($postfix as $token) {
        if (is_numeric($token)) {
            $resultStack[] = $token;
        } elseif (in_array($token, $operators)) {
            $b = array_pop($resultStack);
            $a = array_pop($resultStack);

            switch ($token) {
                case '+':
                    $result = $a + $b;
                    break;
                case '-':
                    $result = $a - $b;
                    break;
                case '*':
                    $result = $a * $b;
                    break;
                case '/':
                    if ($b == 0) {
                        echo "Error: Деление на ноль\n";
                        return;
                    }
                    $result = $a / $b;
                    break;
            }
            $resultStack[] = $result;
        }
    }

    echo "Результат: " . array_pop($resultStack) . "\n";
}


