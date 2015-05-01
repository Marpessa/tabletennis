<?php

namespace Base\ForumBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BaseForumBundle extends Bundle
{
    public function getParent()
    {
        return 'HerzultForumBundle';
    }
}
