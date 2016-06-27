# Simple usage
~~~
[php]
return [
	'<language:vi>/tin-tuc/<slug>-<id>' => 'post/view',
	'<language:en>/news/<slug>-<id>'    => 'post/view',
	'<language:vi>/tin-tuc'             => 'post/index',
	'<language:en>/news'                => 'post/index',
	'<language:vi>'                     => 'site/index',
	'<language:en>'                     => 'site/index',
	'/'                                 => 'site/index',
];
~~~
