<?php

$wgMemCachedServers = [];
$wgMemCachedPersistent = false;

$beta = preg_match( '/^(.*)\.betaheze\.org$/', $wi->server );

// mem141
$wgObjectCaches['memcached-mem-1'] = [
	'class'                => MemcachedPeclBagOStuff::class,
	'serializer'           => 'php',
	'persistent'           => false,
	'servers'              => [ '127.0.0.1:11212' ],
	// Effectively disable the failure limit (0 is invalid)
	'server_failure_limit' => 1e9,
	// Effectively disable the retry timeout
	'retry_timeout'        => -1,
	'loggroup'             => 'memcached',
	// 500ms, in microseconds
	'timeout'              => 1 * 1e6,
];

// mem131
$wgObjectCaches['memcached-mem-2'] = [
	'class'                => MemcachedPeclBagOStuff::class,
	'serializer'           => 'php',
	'persistent'           => false,
	'servers'              => [ '127.0.0.1:11214' ],
	// Effectively disable the failure limit (0 is invalid)
	'server_failure_limit' => 1e9,
	// Effectively disable the retry timeout
	'retry_timeout'        => -1,
	'loggroup'             => 'memcached',
	// 500ms, in microseconds
	'timeout'              => 1 * 1e6,
];

$wgObjectCaches['mysql-multiwrite'] = [
	'class' => MultiWriteBagOStuff::class,
	'caches' => [
		0 => [
			'factory' => [ 'ObjectCache', 'getInstance' ],
			'args' => [ $beta ? 'memcached-mem-test' : 'memcached-mem-1' ]
		],
		1 => [
			'class' => SqlBagOStuff::class,
			'servers' => [
				'pc1' => [
					'type'      => 'mysql',
					'host'      => $beta ? 'db121.miraheze.org' : 'db131.miraheze.org',
					'dbname'    => $beta ? 'testparsercache' : 'parsercache',
					'user'      => $wgDBuser,
					'password'  => $wgDBpassword,
					'ssl'       => true,
					'flags'     => 0,
					'sslCAFile' => '/etc/ssl/certs/Sectigo.crt',
				],
			],
			'purgePeriod' => 0,
			'tableName' => 'pc',
			'shards' => $beta ? 1 : 256,
			'reportDupes' => false
		],
	],
	'replication' => 'async',
	'reportDupes' => false
];

$wgSessionCacheType = 'memcached-mem-1';

// Same as $wgMainStash
$wgMWOAuthSessionCacheType = 'db-replicated';

$redisServerIP = '[2a10:6740::6:306]:6379';

$wgMainCacheType = 'memcached-mem-2';
$wgMessageCacheType = 'memcached-mem-2';

$wgParserCacheType = 'mysql-multiwrite';

$wgLanguageConverterCacheType = CACHE_ACCEL;

$wgQueryCacheLimit = 5000;

// 10 days
$wgParserCacheExpireTime = 86400 * 10;

// 3 days
$wgRevisionCacheExpiry = 86400 * 3;

// 1 day
$wgObjectCacheSessionExpiry = 86400;

$wgDLPQueryCacheTime = 120;
$wgDplSettings['queryCacheTime'] = 120;

// Disable sidebar cache for select wikis as a solution to T8732, T9699, and T9884
if ( $wgDBname !== 'solarawiki' && $wgDBname !== 'constantnoblewiki' && $wgDBname !== 'nonciclopediawiki' ) {
	$wgEnableSidebarCache = true;
}

$wgInvalidateCacheOnLocalSettingsChange = false;

if ( $beta ) {
	// test131 (only use on test131. No prod traffic should use this).
	$wgObjectCaches['memcached-mem-test'] = [
		'class'                => MemcachedPeclBagOStuff::class,
		'serializer'           => 'php',
		'persistent'           => false,
		'servers'              => [ '127.0.0.1:11215' ],
		// Effectively disable the failure limit (0 is invalid)
		'server_failure_limit' => 1e9,
		// Effectively disable the retry timeout
		'retry_timeout'        => -1,
		'loggroup'             => 'memcached',
		// 500ms, in microseconds
		'timeout'              => 1 * 1e6,
	];

	$redisServerIP = '[2a10:6740::6:406]:6379';

	$wgMainCacheType = 'memcached-mem-test';
	$wgMessageCacheType = 'memcached-mem-test';

	$wgSessionCacheType = 'memcached-mem-test';
	$wgMWOAuthSessionCacheType = 'memcached-mem-test';
}

$wgJobTypeConf['default'] = [
	'class' => JobQueueRedis::class,
	'redisServer' => $redisServerIP,
	'redisConfig' => [
		'connectTimeout' => 2,
		'password' => $wmgRedisPassword,
		'compression' => 'gzip',
	],
	'claimTTL' => 3600,
	'daemonized' => true,
];

if ( PHP_SAPI === 'cli' ) {
	// APC not available in CLI mode
	$wgLanguageConverterCacheType = CACHE_NONE;
}

unset( $redisServerIP );
