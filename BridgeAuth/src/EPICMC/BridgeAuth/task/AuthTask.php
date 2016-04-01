<?php

namespace EPICMC\BridgeAuth\task;

use EPICMC\BridgeAuth\BridgeAuth;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;

class AuthTask extends AsyncTask{
	
    protected $name;
    protected $ip;

    public function __construct($name, $ip, $token, $api_url){
        $this->name = $name;
        $this->ip = $ip;
		$this->token = $token;
		$this->api_url = $api_url;
    }

    /**
     * Actions to execute when run
     *
     * @return void
     */
    public function onRun(){
		$data = json_decode(Utils::getURL($this->api_url . '?player=' . $this->name . '&ip=' . $this->ip . '&token=' . $this->token), true);
		$this->setResult($data['login']);
    }

    public function onCompletion(Server $server){
        $plugin = $server->getPluginManager()->getPlugin("BridgeAuth");
        if($plugin instanceof BridgeAuth && $plugin->isEnabled()){
            $plugin->authComplete($this->name, $this->getResult());
        }
    }
}
