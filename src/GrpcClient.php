<?php
namespace Wanshi\GrpcClient;
use Wanshi\MonologFacade\MonologFacade as Log;
class GrpcClient {
	private $config;
	private $errMap;
	public function __construct($config) {
		$this->config = $config;
		$this->connect();
	}
	public function connect(){
		$client = '\\Protobuf\\' . ucfirst($this->config['name']) . 'Client';
		$addr 	= $this->config['host'] . ':' . $this->config['port'];
		$opts 	= ['credentials'=>\Grpc\ChannelCredentials::createInsecure()];
		$this->client = new $client($addr, $opts);
	}
	public function disconnect(){
		$this->client->close();
	}
	public function __destruct(){
		$this->disconnect();
	}
	public function __call($func, $args) {
		$req = $args[0];
		list($res, $status) = $this->client->$func($req)->wait();
		$logName = 'grpc-' . strtolower($this->config['name']) . '-' . strtolower($func);
		$logMsg  = json_encode([
			'request' 	=> $req ? json_decode($req->serializeToJsonString()) : '',
			'status' 	=> $status,
			'response'	=> $res ? json_decode($res->serializeToJsonString()) : '',
		], JSON_PRETTY_PRINT);
		if($status->code != \Protobuf\Code::OK) {
			Log::error($logName, $logMsg);
			return [$status->code, null];
		}
		Log::info($logName, $logMsg);
		return [$status->code, $res];
	}
}
