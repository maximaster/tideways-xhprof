# maximaster/tideways-xhprof

Use `tideways-xhprof` PHP extension and this library to save **xhprof** traces
as files and watch them later with some viewer like
[wodby/xhprof](https://github.com/wodby/xhprof).

```bash
composer require maximaster/tideways-xhprof
```

# Hot to use it in local scope?

Manually construct `TidewaysXhprofSession` then use `start()` and `stop()`
methods. Also, you can use `TidewaysXhprofSessionInterface` to inject the
service as dependency and configure it in your DI-container.

## How to use to profile everything happened on hit?

* configure environment variables (see section below);
* set `auto_prepend_file` to `vendor/maximaster/tideways-xhprof/inc/xhprof.php`
  or `include` it as much early as you can;
* make requests with configured trigger and analyze produced trace files in
  configured directory.

## Environment variables

### MAXIMASTER_TIDEWAYS_XHPROF_TRIGGER_NAME

Configure trigger name which should start profiling. Its value would be looked
for in all global variables.

Defaults to `XHPROF`.

### MAXIMASTER_TIDEWAYS_XHPROF_TRIGGER_VALUE

A secret string which would start profiling on being found in trigger variable.

Have no default value. Profiling won't start if it's empty.

### MAXIMASTER_TIDEWAYS_XHPROF_DIRECTORY

A directory to save trace files.

Defaults to `getcwd()`.

### MAXIMASTER_TIDEWAYS_XHPROF_FILENAME_FORMAT

How trace files should be named. You can use macroses:

* `#date#` - see `MAXIMASTER_TIDEWAYS_XHPROF_DATETIME_FORMAT` below;
* `#uniqid#` - result of `uniqid()`.

Defaults to `#date##uniqid#.default.xhprof`.

### MAXIMASTER_TIDEWAYS_XHPROF_DATETIME_FORMAT

Uses as [DateTime::format](https://www.php.net/manual/ru/datetime.format.php)
argument and result replaces `#date#` macros in
`MAXIMASTER_TIDEWAYS_XHPROF_FILENAME_FORMAT`.

Determines which will be replaced to `#date#` macros in .

Defaults to 'Ymdhis'.

### MAXIMASTER_TIDEWAYS_XHPROF_FLAGS

Any combination of `TIDEWAYS_XHPROF_FLAGS_*` constants. Used when
`tideways_xhprof_enable()` is called.

Defaults to no flags (`0`).
