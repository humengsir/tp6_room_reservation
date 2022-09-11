<?php
namespace app\common;

class TicketLink
{
    protected $leader_uid = [];
    protected $point_uid = 0;
    protected $approve_uid = [];
    protected $status = 1;

    public function __construct(string $leader_uid = '', string $approve_uid = '', int $point_uid = 0, int $status = 1)
    {
        $this->leader_uid = array_filter(explode(",", $leader_uid));
        $this->approve_uid = array_filter(explode(",", $approve_uid));
        if ($point_uid == 0) {
            // 没有人审核通过
            $point_uid = current($this->leader_uid);
        }
        $this->point_uid = $point_uid;
        $this->status = $status;
    }

    public function forward()
    {
        $this->approve_uid[] = $this->point_uid;
        $length = count($this->approve_uid);
        $this->point_uid = isset($this->leader_uid[$length]) ? $this->leader_uid[$length] : 0;
        $this->status = 2;
        if ($this->point_uid == 0) {
            $this->status = 3;
        }
    }

    public function back()
    {
        $last_uid = array_pop($this->approve_uid);
        if (!$last_uid) {
            $last_uid = current($this->leader_uid);
        }
        $this->point_uid = $last_uid;
        $this->status = 2;
    }

    public function get_point_uid()
    {
        return (int)$this->point_uid;
    }

    public function get_approve_uid()
    {
        return implode(',', $this->approve_uid);
    }

    public function get_status()
    {
        return (int)$this->status;
    }
}
