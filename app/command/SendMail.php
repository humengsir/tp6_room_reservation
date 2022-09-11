<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Cache;

class SendMail extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('sendmail')
            ->setDescription('the sendmail command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $mail_list_key = 'need_send_email_list';
        while (true) {
            $result = json_decode((string)Cache::store('redis')->lpop($mail_list_key), true);
            if ($result) {
                // 真正的发邮件
                send_email($result['to_user'], $result['verify_code']);
                usleep(5000);
            } else {
                usleep(500000);
            }
        }
    }
}
