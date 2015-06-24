<?php
namespace Splitice\Rage4;

use Splitice\Rage4\Sync\IDomainSync;

class Domain
{
    /**
     * Enum value for a regular domain
     */
    const TYPE_DOMAIN = 0;

    /**
     * Enum value for a IPv4 Reverse domain
     */
    const TYPE_IPv4 = 1;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var integer
     */
    public $type;

    /**
     * @var Record[]|CreatedRecord[]
     */
    public $records = array();

    /**
     * @var string|null
     */
    public $nsdomain;

    /**
     * @param string $name
     * @param int $type
     * @param string $email
     * @param array $records
     * @param string|null $nsdomain
     */
    function __construct($name, $type, $email, array $records = array(), $nsdomain = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->email = $email;
        $this->records = $records;
        $this->nsdomain = $nsdomain;
    }

    /**
     * Create the domain at Rage4.com
     *
     * @param Rage4Api $api
     * @return CreatedDomain|string
     */
    function create(Rage4Api $api)
    {
        $ret = $api->createDomain($this->name, $this->email, $this->nsdomain);
        if (!is_array($ret)) {
            return $ret;
        }
        $id = $ret['id'];
        return new CreatedDomain($this->name, $this->type, $this->email, $id, array(), null, $this->nsdomain);
    }

    /**
     * Get a created domain instance from Rage4
     *
     * @param Rage4Api $api
     * @return null|CreatedDomain
     */
    function get(Rage4Api $api)
    {
        $domain = self::fromName($api, $this->name);
        if (!$domain) $domain = $this->create($api);
        return $domain;
    }

    /**
     * Get all domains from Rage4
     *
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
     * Get a domain by name from Rage4
     *
     * @param Rage4Api $api
     * @param string $name
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

    /**
     * Get a domain by id from Rage4
     *
     * @param Rage4Api $api
     * @param integer $id
     * @return null|CreatedDomain
     * @throws Rage4Exception
     */
    static function fromId(Rage4Api $api, $id)
    {
        $ret = $api->getDomain($id);

        if (is_string($ret)) {
            throw new Rage4Exception($ret);
        }
        if (!$ret)
            return null;
        return new CreatedDomain($ret['name'], $ret['type'], $ret['owner_email'], $ret['id']);
    }
}