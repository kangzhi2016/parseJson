<?php

class Parser
{
    private $lexer;

    // 当前token
    private $curToken;

    // 下一个token
    private $nextToken;

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
        $this->nextToken();
        $this->nextToken();
    }

    // 主方法
    public function parse()
    {

        switch ($this->curTokenType())
        {
            case 'unknown':
            case 'unexpect':
                $this->throw_error('格式不正确');
            case 'eof':
                return array();
            case '[':
                return $this->parseArr();
            case '{':
                return $this->parseObj();
            default:
//                p($this->curToken);
//                p($this->nextToken);
                $this->throw_error('不知道啥情况');
        }
    }

//    private function parseJson()
//    {
//        switch ($this->curTokenType())
//        {
//            case '[':
//                return $this->parseArr();
//            case '{':
//                return $this->parseObj();
//            case ',':
//                $this->nextToken();
//                $this->parse();
//            default:
//                throw new Exception('不知道啥情况');
//        }
//    }

    private function parseArr()
    {
        $arr = array();

        $this->expectCurTokenType('[');

//        if ($this->curTokenType() == '{')
//        {
//            $arr = $this->parseObj();
//        }
//        elseif ($this->curTokenType() == 'str')
//        {
//            $this->parseKvPair($arr);
//        }

        $this->parseKvPair($arr);

        $this->expectCurTokenType(']');

        return $arr;
    }

    private function parseObj()
    {
        $arr = array();
//p(1);
        $this->expectCurTokenType('{');
//p(2);
        if ($this->curTokenTypeIs('str'))
        {
            $this->parseKvPair($arr);
        }

        $this->expectCurTokenType('}');

        return $arr;
    }

    private function parseKvPair(&$arr)
    {
        if ($this->curTokenTypeIs('str'))
        {
            $key = $this->curTokenLiteral();
            $this->expectNextTokenType(':');
            $this->nextToken();
            $arr[$key] = $this->parseVal();
//            p($arr);
//            $this->nextToken();
        }
        elseif ($this->curTokenTypeIs('{'))
        {
            $arr[] = $this->parseObj();
        }
        elseif ($this->curTokenTypeIs('['))
        {
            $arr[] = $this->parseArr();
        }
        else
        {
            $this->throw_error_info('parseKvPair', 'str', json_encode($this->curToken));
        }

//        p($this->curToken);

//        $this->nextToken();

        if ($this->curTokenTypeIs(','))
        {
//            $this->nextToken();
            $this->nextToken();
            return $this->parseKvPair($arr);
        }

        return $arr;
    }

    private function parseVal()
    {
        switch ($this->curTokenType())
        {
            case '[':
                return $this->parseArr();
            case '{':
                return $this->parseObj();
            case 'str':
//                return $this->curTokenLiteral();
                $val = $this->curTokenLiteral();
                $this->nextToken();
                return $val;
            default:
                $this->throw_error_info('parseVal', '[、{、str', $this->curTokenType());
        }
    }

    // 当前token的 类型
    private function curTokenType()
    {
        return $this->curToken['type'];
    }

    private function curTokenLiteral()
    {
        return $this->curToken['literal'];
    }

    private function curTokenTypeIs($tokenType)
    {
        return $this->curToken['type'] == $tokenType;
    }

    private function nextTokenTypeIs($tokenType)
    {
        return $this->nextToken['type'] == $tokenType;
    }

    // 下一个token的 type是不是期望的type，如果是，吃掉，如果不是，报错
    private function expectNextTokenType($tokenType)
    {
        if ($this->nextToken['type'] == $tokenType)
        {
            $this->nextToken();
            return;
        }

        $this->throw_error_info('expectNextTokenType', $tokenType, json_encode($this->nextToken));
    }

    // 当前token的 type是不是期望的type，如果是，吃掉，如果不是，报错
    private function expectCurTokenType($tokenType)
    {
        if ($this->curTokenType() == $tokenType)
        {
            $this->nextToken();
            return;
        }

        $this->throw_error_info('expectCurTokenType', $tokenType, json_encode($this->curToken));
    }

    private function nextToken()
    {
        // {"a":"b"}
        // curtoken = null，{
        // curtoken = { nextToken =  a
        $this->curToken = $this->nextToken;
//        p('$this->curToken');
//        p($this->curToken);
        $this->nextToken = $this->lexer->nextToken();
//        p('$this->nextToken');
//        p($this->nextToken);
    }

    private function throw_error($msg, $func='')
    {
        throw new Exception($func.' '.$msg);
    }
    private function throw_error_info($curFunc, $exceptType, $curType)
    {
        throw new Exception($curFunc.' error, expect type is '.$exceptType.', but give type is '.$curType);
    }
}