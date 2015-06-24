<?php

namespace Splitice\Rage4\Sync;


interface IDomainSync {
    function sync_domain(CreatedDomain $domain, Rage4Api $api, $doDelete = true);
}