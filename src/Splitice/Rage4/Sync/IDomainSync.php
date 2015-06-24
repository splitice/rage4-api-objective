<?php

namespace Splitice\Rage4\Sync;


use Splitice\Rage4\CreatedDomain;

interface IDomainSync {
    function sync_domain(CreatedDomain $domain, $doDelete = true);
}