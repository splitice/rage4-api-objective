<?php

namespace Splitice\Rage4\Sync;


use Splitice\Rage4\CreatedDomain;

interface IDomainSync {
    /**
     * @param CreatedDomain $domain
     * @param bool $doDelete
     * @return void
     */
    function sync_domain(CreatedDomain $domain, $doDelete = true);
}
