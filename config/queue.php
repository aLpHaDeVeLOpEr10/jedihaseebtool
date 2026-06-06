<?php

return [
    'default'     => env('QUEUE_CONNECTION', 'database'),
    'connections' => [
        'sync'     => ['driver' => 'sync'],
        'database' => ['driver' => 'database', 'connection' => env('DB_QUEUE_CONNECTION'), 'table' => env('DB_QUEUE_TABLE', 'jobs'), 'queue' => env('DB_QUEUE', 'default'), 'retry_after' => env('DB_QUEUE_RETRY_AFTER', 90), 'after_commit' => false],
        'beanstalkd' => ['driver' => 'beanstalkd', 'host' => env('BEANSTALKD_QUEUE_HOST', 'localhost'), 'queue' => env('BEANSTALKD_QUEUE', 'default'), 'retry_after' => env('BEANSTALKD_QUEUE_RETRY_AFTER', 90), 'block_for' => 0, 'after_commit' => false],
        'redis'    => ['driver' => 'redis', 'connection' => env('REDIS_QUEUE_CONNECTION', 'default'), 'queue' => env('REDIS_QUEUE', 'default'), 'retry_after' => env('REDIS_QUEUE_RETRY_AFTER', 90), 'block_for' => null, 'after_commit' => false],
    ],
    'batching'    => ['database' => env('DB_DATABASE', 'sqlite'), 'table' => 'job_batches'],
    'failed'      => ['driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'), 'database' => env('DB_DATABASE', 'sqlite'), 'table' => 'failed_jobs'],
];
