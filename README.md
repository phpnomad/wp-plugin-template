# WordPress Plugin Template

A GitHub template repo for starting a WordPress plugin built on PHPNomad. It ships with a wired PHPNomad Application (DI container, WordPress integration, event bindings) and a PHPScoper configuration that prefixes vendor and source namespaces at build time.

The PHPScoper step matters because WordPress loads every active plugin into the same PHP process. Two plugins that both ship `phpnomad/core` would otherwise collide on shared class names. This template rewrites your plugin's dependencies into a plugin-specific namespace, so your code can coexist with any other plugin that happens to use the same libraries.

## How to use this template

From the GitHub UI, click the **Use this template** button at the top of the repository page and create a new repo under your account or organization.

From the command line, use the `gh` CLI:

```bash
gh repo create my-plugin --template phpnomad/wp-plugin-template --public --clone
cd my-plugin
composer install
```

Once the repo is cloned and dependencies are installed, customize the placeholder namespaces and plugin metadata.

1. Replace the `PluginNameReplaceMe` namespace throughout `src/` and `composer.json` with your plugin's own namespace.
2. Replace the `PluginNameReplaceMeDependency` namespace in `scoper.inc.php` with a unique prefix for your plugin's scoped dependencies.
3. Update the plugin header in `plugin.php` (Plugin Name, Description, Author, Version).
4. Set the `name` field in `composer.json` to your package name.

If your plugin does not need the WooCommerce Action Scheduler, remove the `require_once 'libraries/action-scheduler/action-scheduler.php';` line from `plugin.php` and delete the submodule entry from `.gitmodules`.

## What's included

- `plugin.php`: WordPress plugin entry file. Calls `init()` on every load and `install()` when the plugin is activated.
- `src/Application.php`: DI container and Bootstrapper wiring for PHPNomad Core, the WordPress integration initializer, and your plugin's own Core initializer.
- `src/Core/Initializer.php`: Example event binding that fires a PHPNomad `Ready` event when WordPress runs its `init` action.
- `scoper.inc.php`: PHPScoper configuration that prefixes `vendor/` and `src/` with a plugin-specific namespace.
- `composer.json`: Declares the PHPNomad dependencies (`phpnomad/core`, `phpnomad/wordpress-integration`) and `humbug/php-scoper` for the prefixing build step.
- `setup.sh`: Helper script that runs `composer install` and registers the Action Scheduler submodule.
- `libraries/action-scheduler`: Git submodule pointing at WooCommerce's Action Scheduler, included for plugins that need deferred work.

## License

Released under the MIT license. See [LICENSE.txt](LICENSE.txt).
