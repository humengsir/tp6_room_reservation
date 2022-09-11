<?php
namespace app\common;

use think\paginator\driver\Bootstrap;

class MyPaginator extends Bootstrap 
{
    /**
     * 渲染分页html
     * @return mixed
     */
    public function render()
    {
        if ($this->hasPages()) {
            if ($this->simple) {
                return sprintf(
                    '<div class="pagination"><ul>%s %s</ul></div>',
                    $this->getPreviousButton(),
                    $this->getNextButton()
                );
            } else {
                return sprintf(
                    '<div class="pagination"><ul>%s %s %s</ul></div>',
                    $this->getPreviousButton(),
                    $this->getLinks(),
                    $this->getNextButton()
                );
            }
        }
    }
}
