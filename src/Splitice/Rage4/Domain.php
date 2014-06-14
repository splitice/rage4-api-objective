<?php
namespace SplitIce\Rage4;

class Domain
{
    const TYPE_DOMAIN = 0;
    const TYPE_IPv4 = 1;

    public $name;
    public $email = 'x4borg@gmail.com';
    public $type;
    /**
     * @var Record[]|CreatedRecord[]
     */
    public $records = array();
    public $nsdomain;

    function __construct($name, $type, $email, array $records = array(), $nsdomain = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->email = $email;
        $this->records = $records;
        $this->nsdomain = $nsdomain;
    }

    function create(Rage4Api $api)
    {
        $ret = $api->createDomain($this->name, $this->email, $this->nsdomain);
        if (!is_array($ret)) {
            return $ret;
        }
        $id = $ret['id'];
        return new CreatedDomain($this->name, $this->type, $this->email, $id, array(), null, $this->nsdomain);
    }

    function get(Rage4Api $api)
    {
        $domain = self::fromName($api, $this->name);
        if (!$domain) $domain = $this->create($api);
        return $domain;
    }

    /**
     * @param Rage4Api $api
     * @return CreatedDomain[]
     * @throws \Splitice\Rage4\Rage4Exception
     */
    static function getAll(Rage4Api $api)
    {
        $ret = array();
        $domains = $api->getDomains();
        if (!is_array($domains)) {
            throw new Rage4Exception($domains);
        }
        foreach ($domains as $domain) {
            $ret[] = new CreatedDomain($domain['name'], $domain['type'], $domain['owner_email'], $domain['id'], array(), $domain['subnet_mask']);
        }
        return $ret;
    }

    /**
     * @param Rage4Api $api
     * @param $name
     * @return null|CreatedDomain
     * @throws \Splitice\Rage4\Rage4Exception
     */
    static function fromName(Rage4Api $api, $name)
    {
        $ret = $api->getDomainByName($name);
        /*if(!is_array($ret)){
            throw new Rage4Exception($ret);
        }*/
        if (is_string($ret)) {
            throw new Rage4Exception($ret);
        }
        if (!$ret)
            return null;
        return new CreatedDomain($ret['name'], $ret['type'], $ret['owner_email'], $ret['id']);
    }

    function sync(Rage4Api $api, $doDelete)
    {
        $rDomain = self::fromName($api, $this->name);
        if ($rDomain) {
            $rDomain->name = $this->name;
            $rDomain->email = $this->email;
            $rDomain->records = $this->records;
        } else {
            $rDomain = $this->create($api);
        }
        $rDomain->sync($api, $doDelete);
        return $rDomain;
    }
}