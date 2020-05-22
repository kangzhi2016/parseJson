<?php
include "lexer.php";
include "parser.php";


function p($data = '')
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

function testLexer($input, $expect) {
    $lexer = new Lexer($input);
    $tokens = [];

    while (($tok = $lexer->nextToken())['type'] != 'eof') {
//        p($tok);
        $tokens[] = $tok;
    }

//    p($tokens);
    if ($tokens != $expect) {
        echo "expect token is:";
        p($expect);
        echo " but given:";
        p($tokens);
        exit();
    }
}


function testParse($input, $expect)
{
    $lexer = new Lexer($input);
    $parse = new Parser($lexer);
    $tokens = [];

    try{
        while (($tok = $parse->parse()) != array()) {
            $tokens = $tok;
        }
    }catch (Exception $e)
    {
        p($e->getMessage());
    }

//    p($tokens);
    if ($tokens != $expect) {
        echo "expect token is:";
        p($expect);
        echo " but given:";
        p($tokens);
        exit();
    }
}




//$json = '{"0":"1","a":"bcd"}';
//$exp_lexer = [
//    ['type' => '{', 'literal' => '{'],
//    ['type' => 'str', 'literal' => '0'],
//    ['type' => ':', 'literal' => ':'],
//    ['type' => 'str', 'literal' => '1'],
//    ['type' => ',', 'literal' => ','],
//    ['type' => 'str', 'literal' => 'a'],
//    ['type' => ':', 'literal' => ':'],
//    ['type' => 'str', 'literal' => 'bcd'],
//    ['type' => '}', 'literal' => '}']
//];
//testLexer($json, $exp_lexer);
//
//print "lexer test pass\n";


$json = '{"code":"success","msg":"","data":{"invitation_start_time":"2020/04/27 19:00","invitation_end_time":"2020/04/30 18:00","invitation_deadline":"2020/04/30 18:00","opening_time":"2020/04/30 19:00","open_lottery_time":"2020/04/30 19:00","prize_info":{"title":"荣耀30S","pic_url":"https://img.alicdn.com/imgextra/i1/285606272/O1CN01NjcWQZ1wCcimGrV35_!!285606272.png"},"rules":[{"title":"活动时间","content":"第一部分\n邀请时间 2020-04-27 19:00:00 ~ 2020-04-30 18:00:00\n邀请截止 2020-04-30 18:00:00\n开奖时间 2020-04-30 19:00:00\n\n第二部分\n开启抽奖时间 2020-04-30 19:00:00"},{"title":"如何参与瓜分？","content":"1.成功邀请1位好友帮忙助力，并且活动结束时最终排名在1000名以内，即可获得瓜分红包资格。\n2.邀请的好友数量越多，最后可瓜分的越多。\n3.每人最多可以为5名好友助力。"},{"title":"如何参与抽奖？","content":"1.在第一阶段邀请的好友数会按比例转化为抽奖次数，每邀请15个好友可抽1次奖，抽奖次数上不封顶。\n2.第一阶段结束后停止邀请，系统会自动将邀请的好友数转化为抽奖次数。"},{"title":"如何领取红包？","content":"1.活动发放的是支付宝红包，开奖后会自动发放到中奖用户的账户内。\n2.到开奖时间后，拥有瓜分资格的用户，进入活动页面后，即可看到自己瓜分到的红包金额以及第二阶段的抽奖次数。\n3.红包自开奖后24小时内会自动发放到账户内，无需手动领取。"},{"title":"其他细则","content":"1.如出现不可抗力或情势变更的情况（包括但不限于重大灾害事件、活动受政府机关指令需要停止举办或调整的、活动遭受严重网络攻击或因系统故障需要暂停举办的），主办方有权暂停或取消本次活动，并可依相关法律法规的规定主张免责。\n2.在本活动期间用户账号被淘宝识别为风险账号或用户存在违规行为（包括但不限于洗钱、虚假交易、赌博、恶意套现、作弊、刷信誉），主办方将取消用户的活动资格，并有权撤销相关违规交易、收回奖励（包括已消费金额）等利益，同时依照相关规则进行处罚。\n3.主办方可以根据本活动的实际举办情况对活动规则进行变动或调整，相关改动或调整将公布在活动页面上，并于公布时即时生效。"}]}}';
$exp_parse = ['code'=>'success','msg'=>'','data'=>['invitation_start_time'=>'2020/04/27 19:00','invitation_end_time'=>'2020/04/30 18:00','invitation_deadline'=>'2020/04/30 18:00','opening_time'=>'2020/04/30 19:00','open_lottery_time'=>'2020/04/30 19:00','prize_info'=>['title'=>'荣耀30S','pic_url'=>'https://img.alicdn.com/imgextra/i1/285606272/O1CN01NjcWQZ1wCcimGrV35_!!285606272.png'],'rules'=>[['title'=>'活动时间','content'=>'第一部分\n邀请时间 2020-04-27 19:00:00 ~ 2020-04-30 18:00:00\n邀请截止 2020-04-30 18:00:00\n开奖时间 2020-04-30 19:00:00\n\n第二部分\n开启抽奖时间 2020-04-30 19:00:00'],['title'=>'如何参与瓜分？','content'=>'1.成功邀请1位好友帮忙助力，并且活动结束时最终排名在1000名以内，即可获得瓜分红包资格。\n2.邀请的好友数量越多，最后可瓜分的越多。\n3.每人最多可以为5名好友助力。'],['title'=>'如何参与抽奖？','content'=>'1.在第一阶段邀请的好友数会按比例转化为抽奖次数，每邀请15个好友可抽1次奖，抽奖次数上不封顶。\n2.第一阶段结束后停止邀请，系统会自动将邀请的好友数转化为抽奖次数。'],['title'=>'如何领取红包？','content'=>'1.活动发放的是支付宝红包，开奖后会自动发放到中奖用户的账户内。\n2.到开奖时间后，拥有瓜分资格的用户，进入活动页面后，即可看到自己瓜分到的红包金额以及第二阶段的抽奖次数。\n3.红包自开奖后24小时内会自动发放到账户内，无需手动领取。'],['title'=>'其他细则','content'=>'1.如出现不可抗力或情势变更的情况（包括但不限于重大灾害事件、活动受政府机关指令需要停止举办或调整的、活动遭受严重网络攻击或因系统故障需要暂停举办的），主办方有权暂停或取消本次活动，并可依相关法律法规的规定主张免责。\n2.在本活动期间用户账号被淘宝识别为风险账号或用户存在违规行为（包括但不限于洗钱、虚假交易、赌博、恶意套现、作弊、刷信誉），主办方将取消用户的活动资格，并有权撤销相关违规交易、收回奖励（包括已消费金额）等利益，同时依照相关规则进行处罚。\n3.主办方可以根据本活动的实际举办情况对活动规则进行变动或调整，相关改动或调整将公布在活动页面上，并于公布时即时生效。']]]];
//p($json);
//p($exp_parse);
//testLexer($json, $exp_lexer);

testParse($json, $exp_parse);

print "parse test pass\n";