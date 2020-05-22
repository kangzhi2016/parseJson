<?php

class Lexer
{
    private $input; // 输入的字符串

    private $pos = 0;  // point to c char

    private $readPos = 0; // point to next char

    private $c; // current char


    const EOF = -1;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->readChar();
    }

    //  {"0":1,"a":"bcd"}
    public function nextToken(): array
    {
        $this->skipBlank();
//var_dump($this->c);
        switch ($this->c)
        {
//            case '':
            case self::EOF:
                return $this->makeToken('eof', 'EOF');
            case '[':
            case ']':
            case '}':
            case '{':
            case ':':
            case ',':
                $token = $this->makeToken($this->c, $this->c);
                $this->readChar();
                return $token;
            case '"':
                $this->readChar();
                $token = $this->makeToken('str', $this->matchStr());

                $exp = $this->expectChar('"');
                if (!$exp)
                {
                    return $this->makeToken('unexpect', $this->c);
                }

                $this->readChar();
                return $token;
            default:
//                if (is_numeric($this->c))
//                {
//
//                }
//                elseif (is_bool($this->c))
//                {
//
//                }

                var_dump(1111);
                exit();
                return $this->makeToken('unknown', $this->c);
        }
    }

    private function matchStr(): string
    {
        $str = '';
        while( $this->c != '"' && $this->c != self::EOF)
        {
            $str .= $this->c;
            $this->readChar();
        }
        return $str;
    }

    // 期望拿到什么字符
    private function expectChar($char)
    {
        if($this->c != $char)
        {
            return false;
        }

        return true;
    }

    private function readChar()
    {
        $this->pos = $this->readPos;
        $this->c = $this->input[$this->readPos] ?? self::EOF;
        $this->readPos++;
    }

//    private function peekChar(): string
//    {
//        return $this->input[$this->pos];
//    }

    private function skipBlank()
    {
        while( $this->c == " " || $this->c == "\t" || $this->c == "\n" || $this->c == "\r")
        {
            $this->readChar();
        }
    }

    private function makeToken($type, $literal): array
    {
        return ['type' => $type, 'literal' => $literal];
    }
}