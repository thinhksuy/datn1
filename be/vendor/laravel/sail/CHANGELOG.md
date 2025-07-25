# Release Notes

## [Unreleased](https://github.com/laravel/sail/compare/v1.43.1...1.x)

## [v1.43.1](https://github.com/laravel/sail/compare/v1.43.0...v1.43.1) - 2025-05-19

* Add missing rabbitmq volume by [@kostamilorava](https://github.com/kostamilorava) in https://github.com/laravel/sail/pull/798

## [v1.43.0](https://github.com/laravel/sail/compare/v1.42.0...v1.43.0) - 2025-05-13

* Fix rabbitmq volumes by [@kiani01lab](https://github.com/kiani01lab) in https://github.com/laravel/sail/pull/793
* Add the hostname for RabbitMQ by [@kiani01lab](https://github.com/kiani01lab) in https://github.com/laravel/sail/pull/796
* Add Laravel's official vscode extension to devcontainer stub by [@eamirgh](https://github.com/eamirgh) in https://github.com/laravel/sail/pull/797

## [v1.42.0](https://github.com/laravel/sail/compare/v1.41.1...v1.42.0) - 2025-04-29

* Add the RabbitMQ service by [@kiani01lab](https://github.com/kiani01lab) in https://github.com/laravel/sail/pull/790

## [v1.41.1](https://github.com/laravel/sail/compare/v1.41.0...v1.41.1) - 2025-04-22

* Update logo and socialcard by [@iamdavidhill](https://github.com/iamdavidhill) in https://github.com/laravel/sail/pull/781
* Fix `DB_DATABASE` replacement in `phpunit.xml` by [@choowx](https://github.com/choowx) in https://github.com/laravel/sail/pull/783
* Added configurable user for shell commands by [@fkrzski](https://github.com/fkrzski) in https://github.com/laravel/sail/pull/785
* fix: typesense healthcheck by [@Barbapapazes](https://github.com/Barbapapazes) in https://github.com/laravel/sail/pull/788

## [v1.41.0](https://github.com/laravel/sail/compare/v1.40.0...v1.41.0) - 2025-01-24

* Supports Laravel 12 by [@crynobone](https://github.com/crynobone) in https://github.com/laravel/sail/pull/771
* Add `sail run` command by [@rojtjo](https://github.com/rojtjo) in https://github.com/laravel/sail/pull/770

## [v1.40.0](https://github.com/laravel/sail/compare/v1.39.1...v1.40.0) - 2025-01-13

* enable swoole php 8.4 by [@ariaieboy](https://github.com/ariaieboy) in https://github.com/laravel/sail/pull/766
* Add Valkey support by [@ariaieboy](https://github.com/ariaieboy) in https://github.com/laravel/sail/pull/767
* Update Ondrej PPA key by [@binaryfire](https://github.com/binaryfire) in https://github.com/laravel/sail/pull/768

## [v1.39.1](https://github.com/laravel/sail/compare/v1.39.0...v1.39.1) - 2024-11-27

* [1.x] Remove the default `ubuntu` user by [@rojtjo](https://github.com/rojtjo) in https://github.com/laravel/sail/pull/762

## [v1.39.0](https://github.com/laravel/sail/compare/v1.38.0...v1.39.0) - 2024-11-25

* [1.x] Use Ubuntu 24.04 and Node 22 by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/758

## [v1.38.0](https://github.com/laravel/sail/compare/v1.37.1...v1.38.0) - 2024-11-11

* fix: Use xdg-open if open does not exist by [@rqpt](https://github.com/rqpt) in https://github.com/laravel/sail/pull/744
* Add MongoDB extension and service by [@GromNaN](https://github.com/GromNaN) in https://github.com/laravel/sail/pull/748
* fix: Sail share 504 timeout fix for linux hosts by [@rqpt](https://github.com/rqpt) in https://github.com/laravel/sail/pull/709
* Use equals sign (=) instead of space as ENV variable separator by [@jpkleemans](https://github.com/jpkleemans) in https://github.com/laravel/sail/pull/753

## [v1.37.1](https://github.com/laravel/sail/compare/v1.37.0...v1.37.1) - 2024-10-29

* Update typesense.stub to 27.1 by [@Braunson](https://github.com/Braunson) in https://github.com/laravel/sail/pull/741
* Update typesense.stub to correct version tag by [@Braunson](https://github.com/Braunson) in https://github.com/laravel/sail/pull/742

## [v1.37.0](https://github.com/laravel/sail/compare/v1.36.0...v1.37.0) - 2024-10-21

* Add php 8.4 to the list of runtimes by [@jobvink](https://github.com/jobvink) in https://github.com/laravel/sail/pull/740

## [v1.36.0](https://github.com/laravel/sail/compare/v1.35.0...v1.36.0) - 2024-10-10

* [1.x] Update Postgres client to v17 by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/737

## [v1.35.0](https://github.com/laravel/sail/compare/v1.34.0...v1.35.0) - 2024-10-08

* Upgrade to Postgres 17 by [@ziadoz](https://github.com/ziadoz) in https://github.com/laravel/sail/pull/735
* Use /data path for minio by [@francoism90](https://github.com/francoism90) in https://github.com/laravel/sail/pull/736

## [v1.34.0](https://github.com/laravel/sail/compare/v1.33.0...v1.34.0) - 2024-09-27

* M3 silicon support and fix 'Hash Sum Mismatch' by [@ConrDev](https://github.com/ConrDev) in https://github.com/laravel/sail/pull/734
* Update logo to support dark/light theme by [@milewski](https://github.com/milewski) in https://github.com/laravel/sail/pull/733

## [v1.33.0](https://github.com/laravel/sail/compare/v1.32.0...v1.33.0) - 2024-09-22

* Pass all command line arguments to wrapped executable by [@JoaquinTrinanes](https://github.com/JoaquinTrinanes) in https://github.com/laravel/sail/pull/728
* Use apt php8.3-swoole again by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/731

## [v1.32.0](https://github.com/laravel/sail/compare/v1.31.3...v1.32.0) - 2024-09-11

* [1.x] Add Docker Compose Tests by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/721
* Cleanup unneeded code by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/724
* Use selenium/standalone-chromium on ARM by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/723
* Use selenium/standalone-chromium on AMD and ARM by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/722

## [v1.31.3](https://github.com/laravel/sail/compare/v1.31.2...v1.31.3) - 2024-09-03

* fix: missing \ in dockerfile 8.3 by [@saullo](https://github.com/saullo) in https://github.com/laravel/sail/pull/718

## [v1.31.2](https://github.com/laravel/sail/compare/v1.31.1...v1.31.2) - 2024-09-03

* fix: fixed swoole extension that gets the SQLSTATE[08006] error by [@pedrovian4](https://github.com/pedrovian4) in https://github.com/laravel/sail/pull/715

## [v1.31.1](https://github.com/laravel/sail/compare/v1.31.0...v1.31.1) - 2024-08-02

* minio: health check using mc by [@francoism90](https://github.com/francoism90) in https://github.com/laravel/sail/pull/711

## [v1.31.0](https://github.com/laravel/sail/compare/v1.30.2...v1.31.0) - 2024-07-22

* [1.x] Only support MariaDB 11 by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/707
* Update EXPOSE port command by [@SamuelMwangiW](https://github.com/SamuelMwangiW) in https://github.com/laravel/sail/pull/706

## [v1.30.2](https://github.com/laravel/sail/compare/v1.30.1...v1.30.2) - 2024-07-05

* [1.x] Use Official MariaDB Healthcheck Script by [@a1383n](https://github.com/a1383n) in https://github.com/laravel/sail/pull/704

## [v1.30.1](https://github.com/laravel/sail/compare/v1.30.0...v1.30.1) - 2024-07-01

* Fixed undefined array key mariadb10|11 error on installation. by [@kursatcanciger](https://github.com/kursatcanciger) in https://github.com/laravel/sail/pull/703

## [v1.30.0](https://github.com/laravel/sail/compare/v1.29.3...v1.30.0) - 2024-06-18

* MariaDB 11 support by [@tomcoonen](https://github.com/tomcoonen) in https://github.com/laravel/sail/pull/698

## [v1.29.3](https://github.com/laravel/sail/compare/v1.29.2...v1.29.3) - 2024-06-12

* Fix meilisearch healthcheck gets to IPv6 instead IPv4 by [@Theprim0](https://github.com/Theprim0) in https://github.com/laravel/sail/pull/697

## [v1.29.2](https://github.com/laravel/sail/compare/v1.29.1...v1.29.2) - 2024-05-16

* [1.x] Install "mariadb-client" package for MariaDB users by [@staudenmeir](https://github.com/staudenmeir) in https://github.com/laravel/sail/pull/693

## [v1.29.1](https://github.com/laravel/sail/compare/v1.29.0...v1.29.1) - 2024-03-20

* [1.x] Make commands lazy by [@timacdonald](https://github.com/timacdonald) in https://github.com/laravel/sail/pull/683
* Preinstall nano, so default make tinker edit work out of the box by [@negoziator](https://github.com/negoziator) in https://github.com/laravel/sail/pull/685
* Revert opcache for CLI by [@driesvints](https://github.com/driesvints) in https://github.com/laravel/sail/pull/684

## [v1.29.0](https://github.com/laravel/sail/compare/v1.28.2...v1.29.0) - 2024-03-08

* Allow building sail to run PHP as root by [@vmsh0](https://github.com/vmsh0) in https://github.com/laravel/sail/pull/677
* Update MAILER config to use mailpit on L11 by [@SamuelMwangiW](https://github.com/SamuelMwangiW) in https://github.com/laravel/sail/pull/678

## [v1.28.2](https://github.com/laravel/sail/compare/v1.28.1...v1.28.2) - 2024-03-04

* [1.x] Switch from XDEBUG_SESSION to XDEBUG_TRIGGER for sail debug by [@GregMayes](https://github.com/GregMayes) in https://github.com/laravel/sail/pull/675
* Error calling command "sail mariadb" by [@halfbaked](https://github.com/halfbaked) in https://github.com/laravel/sail/pull/674

## [v1.28.1](https://github.com/laravel/sail/compare/v1.28.0...v1.28.1) - 2024-02-23

* [1.x] Use new MariaDB connection if possible by [@staudenmeir](https://github.com/staudenmeir) in https://github.com/laravel/sail/pull/672

## [v1.28.0](https://github.com/laravel/sail/compare/v1.27.4...v1.28.0) - 2024-02-20

* Changing pcov Directory by [@joaopalopes24](https://github.com/joaopalopes24) in https://github.com/laravel/sail/pull/670
* add ffmpeg to support videos, when using Spatie media-library for Videos by [@negoziator](https://github.com/negoziator) in https://github.com/laravel/sail/pull/671

## [v1.27.4](https://github.com/laravel/sail/compare/v1.27.3...v1.27.4) - 2024-02-08

* Fix open in browser with APP_PORT by [@ijpatricio](https://github.com/ijpatricio) in https://github.com/laravel/sail/pull/663

## [v1.27.3](https://github.com/laravel/sail/compare/v1.27.2...v1.27.3) - 2024-01-30

* [1.x] Improves console output by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/laravel/sail/pull/661

## [v1.27.2](https://github.com/laravel/sail/compare/v1.27.1...v1.27.2) - 2024-01-21

* Add Support for Typesense by [@jasonbosco](https://github.com/jasonbosco) in https://github.com/laravel/sail/pull/655
* Lint sail script by [@dimitriacosta](https://github.com/dimitriacosta) in https://github.com/laravel/sail/pull/656
* Make DB_CONNECTION replacement more robust by @taylorotwell in https://github.com/laravel/sail/commit/2276a8d9d6cfdcaad98bf67a34331d100149d5b6

## [v1.27.1](https://github.com/laravel/sail/compare/v1.27.0...v1.27.1) - 2024-01-13

* [1.x] [#651] Don't do anything if no phpunit files are present by [@zack6849](https://github.com/zack6849) in https://github.com/laravel/sail/pull/652

## [v1.27.0](https://github.com/laravel/sail/compare/v1.26.3...v1.27.0) - 2024-01-03

* [1.x] Allow easy customization of the command ran by supervisor's PHP process by [@bram-pkg](https://github.com/bram-pkg) in https://github.com/laravel/sail/pull/645
* [1.x] Default to PHP 8.3 by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/647

## [v1.26.3](https://github.com/laravel/sail/compare/v1.26.2...v1.26.3) - 2023-12-02

* [1.x] Add PHP 8.3 xdebug by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/642

## [v1.26.2](https://github.com/laravel/sail/compare/v1.26.1...v1.26.2) - 2023-11-27

* Add missing PHP 8.3 extensions by [@hebbet](https://github.com/hebbet) in https://github.com/laravel/sail/pull/640

## [v1.26.1](https://github.com/laravel/sail/compare/v1.26.0...v1.26.1) - 2023-11-20

- Update default user by [@taylorotwell](https://github.com/taylorotwell) in https://github.com/laravel/sail/commit/7a82f5aa364dbee3fd9c52fc464cf0bdd11150ed

## [v1.26.0](https://github.com/laravel/sail/compare/v1.25.0...v1.26.0) - 2023-10-18

- Fix: Allow postCreateCommand to fail silently in VS Code on Windows by [@seanburns326a](https://github.com/seanburns326a) in https://github.com/laravel/sail/pull/626
- Support Laravel 11 and update dependencies by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/629
- Use nodejs 20 by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/628

## [v1.25.0](https://github.com/laravel/sail/compare/v1.24.1...v1.25.0) - 2023-09-11

- Add Bun by [@punyflash](https://github.com/punyflash) in https://github.com/laravel/sail/pull/616
- Install bun from npm by [@punyflash](https://github.com/punyflash) in https://github.com/laravel/sail/pull/617

## [v1.24.1](https://github.com/laravel/sail/compare/v1.24.0...v1.24.1) - 2023-09-01

- Change node source repository by [@alexpado](https://github.com/alexpado) in https://github.com/laravel/sail/pull/613
- Add PHP 8.3 Runtime (missing extensions excluded) by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/sail/pull/614

## [v1.24.0](https://github.com/laravel/sail/compare/v1.23.4...v1.24.0) - 2023-08-27

- Make MEILISEARCH_NO_ANALYTICS environment variable available by [@mawnicat](https://github.com/mawnicat) in https://github.com/laravel/sail/pull/611
- Use Laravel Prompts when available by [@jessarcher](https://github.com/jessarcher) in https://github.com/laravel/sail/pull/612

## [v1.23.4](https://github.com/laravel/sail/compare/v1.23.3...v1.23.4) - 2023-08-17

- Adjust pnpm  to support Sail alias by [@SamuelMTeixeira](https://github.com/SamuelMTeixeira) in https://github.com/laravel/sail/pull/607

## [v1.23.3](https://github.com/laravel/sail/compare/v1.23.2...v1.23.3) - 2023-08-14

- Upgrade the Compose file format version to Compose specification by [@goodjack](https://github.com/goodjack) in https://github.com/laravel/sail/pull/601
- Add PNPM support to enhance dependency management efficiency by [@SamuelMTeixeira](https://github.com/SamuelMTeixeira) in https://github.com/laravel/sail/pull/605

## [v1.23.2](https://github.com/laravel/sail/compare/v1.23.1...v1.23.2) - 2023-08-07

- add fswatch for pest support by [@Thinkro](https://github.com/Thinkro) in https://github.com/laravel/sail/pull/600

## [v1.23.1](https://github.com/laravel/sail/compare/v1.23.0...v1.23.1) - 2023-06-28

- Also publish database init scripts by [@spasstiger23](https://github.com/spasstiger23) in https://github.com/laravel/sail/pull/592

## [v1.23.0](https://github.com/laravel/sail/compare/v1.22.0...v1.23.0) - 2023-06-16

- Add `a` as alias for artisan command by @5thmv in https://github.com/laravel/sail/pull/588

## [v1.22.0](https://github.com/laravel/sail/compare/v1.21.5...v1.22.0) - 2023-05-04

- Remove PHP 7.4 Support by @Jubeki in https://github.com/laravel/sail/pull/580

## [v1.21.5](https://github.com/laravel/sail/compare/v1.21.4...v1.21.5) - 2023-04-24

- Fix opening files from Ignition error page by @NiclasvanEyk in https://github.com/laravel/sail/pull/576
- Add librsvg2-bin package for SVG support by @Bottelet in https://github.com/laravel/sail/pull/575

## [v1.21.4](https://github.com/laravel/sail/compare/v1.21.3...v1.21.4) - 2023-03-30

- Speeds up CLI and tests by enabling OpCache by @lukeraymonddowning in https://github.com/laravel/sail/pull/569

## [v1.21.3](https://github.com/laravel/sail/compare/v1.21.2...v1.21.3) - 2023-03-13

- Enable Expose Global Server Infrastructure by @theutz in https://github.com/laravel/sail/pull/563
- feat: upgrade postgresql-client to 15 by @fedorvladimirov in https://github.com/laravel/sail/pull/564

## [v1.21.2](https://github.com/laravel/sail/compare/v1.21.1...v1.21.2) - 2023-03-06

- Use curl to download composer by @larsnystrom in https://github.com/laravel/sail/pull/561

## [v1.21.1](https://github.com/laravel/sail/compare/v1.21.0...v1.21.1) - 2023-03-01

- Added Imagick to the php runtimes by @ams-ryanolson in https://github.com/laravel/sail/pull/559

## [v1.21.0](https://github.com/laravel/sail/compare/v1.20.2...v1.21.0) - 2023-02-16

- Add `sail open` command. by @xiCO2k in https://github.com/laravel/sail/pull/551
- Update keyring path to new default recommendation by @binaryfire in https://github.com/laravel/sail/pull/552

## [v1.20.2](https://github.com/laravel/sail/compare/v1.20.1...v1.20.2) - 2023-02-08

### Fixed

- Fix `SAIL_SHARE_DOMAIN` default value by @gonzalom in https://github.com/laravel/sail/pull/546

## [v1.20.1](https://github.com/laravel/sail/compare/v1.20.0...v1.20.1) - 2023-02-07

### Fixed

- Fixed the path to devcontainer.stub by @gabrielgry in https://github.com/laravel/sail/pull/544

## [v1.20.0](https://github.com/laravel/sail/compare/v1.19.0...v1.20.0) - 2023-02-05

### Added

- Use symfony/yaml, new Soketi service, and new sail:add command by @tonysm in https://github.com/laravel/sail/pull/532

### Fixed

- Move settings into customizations.vscode by @Kyzegs in https://github.com/laravel/sail/pull/542

## [v1.19.0](https://github.com/laravel/sail/compare/v1.18.1...v1.19.0) - 2023-01-31

### Added

- Add custom domain config to sail share by @mojowill in https://github.com/laravel/sail/pull/531
- Add pest command to sail bin by @MortenDHansen in https://github.com/laravel/sail/pull/534

### Changed

- Replace mailhog with mailpit by @ankurk91 in https://github.com/laravel/sail/pull/533

## [v1.18.1](https://github.com/laravel/sail/compare/v1.18.0...v1.18.1) - 2023-01-12

### Changed

- Update devcontainer stub (vscode customizations) by @mojgit in https://github.com/laravel/sail/pull/528

## [v1.18.0](https://github.com/laravel/sail/compare/v1.17.0...v1.18.0) - 2023-01-10

### Added

- Laravel v10 Support by @driesvints in https://github.com/laravel/sail/pull/527

## [v1.17.0](https://github.com/laravel/sail/compare/v1.16.6...v1.17.0) - 2022-12-22

### Changed

- Upgrade to Postgres 15 by @Jubeki in https://github.com/laravel/sail/pull/519
- Install `dnsutils` package to use `dig` command by @buismaarten in https://github.com/laravel/sail/pull/520

## [v1.16.6](https://github.com/laravel/sail/compare/v1.16.5...v1.16.6) - 2022-12-19

### Changed

- Add PHP 8.2 pcov extension again by @Jubeki in https://github.com/laravel/sail/pull/515

## [v1.16.5](https://github.com/laravel/sail/compare/v1.16.4...v1.16.5) - 2022-12-14

### Changed

- Add Forward Memcached Port by @dammy001 in https://github.com/laravel/sail/pull/512

## [v1.16.4](https://github.com/laravel/sail/compare/v1.16.3...v1.16.4) - 2022-12-12

### Fixed

- Changing ubuntu keyserver to use curl by @jseitel in https://github.com/laravel/sail/pull/508

## [v1.16.3](https://github.com/laravel/sail/compare/v1.16.2...v1.16.3) - 2022-11-21

### Fixed

- Fix usage of none for services list by @jf-prevost in https://github.com/laravel/sail/pull/495

## [v1.16.2](https://github.com/laravel/sail/compare/v1.16.1...v1.16.2) - 2022-09-28

### Fixed

- Add extra hosts to Selenium by @nomnoms12 in https://github.com/laravel/sail/pull/485

## [v1.16.1](https://github.com/laravel/sail/compare/v1.16.0...v1.16.1) - 2022-09-26

### Fixed

- Script not loading all app env files by @LouisHaftmann in https://github.com/laravel/sail/pull/482

## [v1.16.0](https://github.com/laravel/sail/compare/v1.15.4...v1.16.0) - 2022-08-31

### Added

- PHP 8.2 Support by @Jubeki in https://github.com/laravel/sail/pull/473

## [v1.15.4](https://github.com/laravel/sail/compare/v1.15.3...v1.15.4) - 2022-08-17

### Fixed

- Don't error when docker is not available by @jessarcher in https://github.com/laravel/sail/pull/468

## [v1.15.3](https://github.com/laravel/sail/compare/v1.15.2...v1.15.3) - 2022-08-17

### Changed

- Build and pull images on install by @jessarcher in https://github.com/laravel/sail/pull/467

## [v1.15.2](https://github.com/laravel/sail/compare/v1.15.1...v1.15.2) - 2022-08-08

### Fixed

- Fix splitting SAIL_FILES into array by @mortenscheel in https://github.com/laravel/sail/pull/458

## [v1.15.1](https://github.com/laravel/sail/compare/v1.15.0...v1.15.1) - 2022-07-21

### Fixed

- Fix ubuntu versions for PHP 7.4 & 8.0 runtimes by @taylorotwell in https://github.com/laravel/sail/commit/2fe64c0b45a3af56cac0af638c8020a8adc860d7

## [v1.15.0](https://github.com/laravel/sail/compare/v1.14.11...v1.15.0) - 2022-06-24

### Added

- Adds `sail pint` by @nunomaduro in https://github.com/laravel/sail/pull/439

### Changed

- Publish the Vite port by @jessarcher in https://github.com/laravel/sail/pull/433

### Fixed

- Fixed devcontainer permissions by @GoodM4ven in https://github.com/laravel/sail/pull/438
- Update default PostgreSQL versions for PHP 8.0 and 7.4 runtimes by @driesvints in https://github.com/laravel/sail/pull/441

## [v1.14.11](https://github.com/laravel/sail/compare/v1.14.10...v1.14.11) - 2022-06-14

### Fixed

- Revert "Expose 8080 port for hot module replacement" by @jessarcher in https://github.com/laravel/sail/pull/432

## [v1.14.10](https://github.com/laravel/sail/compare/v1.14.9...v1.14.10) - 2022-06-09

### Fixed

- Fix testing DB creation by @jessarcher in https://github.com/laravel/sail/pull/429

## [v1.14.9](https://github.com/laravel/sail/compare/v1.14.8...v1.14.9) - 2022-06-06

### Changed

- Allow for creation of databases needed for parallel testing by @bram-pkg in https://github.com/laravel/sail/pull/424

## [v1.14.8](https://github.com/laravel/sail/compare/v1.14.7...v1.14.8) - 2022-05-31

### Changed

- Run supervisord with pid 1 by @ryoluo in https://github.com/laravel/sail/pull/419

## [v1.14.7](https://github.com/laravel/sail/compare/v1.14.6...v1.14.7) - 2022-05-21

### Changed

- Update meilisearch stub to reflect new data path by @tdondich in https://github.com/laravel/sail/pull/414

## [v1.14.6](https://github.com/laravel/sail/compare/v1.14.5...v1.14.6) - 2022-05-18

### Fixed

- Checks if docker compose or docker-compose is installed by @affektde in https://github.com/laravel/sail/pull/409

## [v1.14.5](https://github.com/laravel/sail/compare/v1.14.4...v1.14.5) - 2022-05-16

### Changed

- Updated sail helps section by @mehdirajabi59 in https://github.com/laravel/sail/pull/407
- Cleans up deprecated apt-key usage by @tbollinger in https://github.com/laravel/sail/pull/408
- use docker compose (GO) by @erfantkerfan in https://github.com/laravel/sail/pull/405

## [v1.14.4](https://github.com/laravel/sail/compare/v1.14.3...v1.14.4) - 2022-05-12

### Fixed

- Fixes incorrectly referenced distro https://github.com/laravel/sail/commit/0e0e51f19c758c79acbda11e3870641fbad5b7d9

## [v1.14.3](https://github.com/laravel/sail/compare/v1.14.2...v1.14.3) - 2022-05-10

### Changed

- Changed Ubuntu 21.10 to Ubuntu 22.04 LTS by @mehdirajabi59 in https://github.com/laravel/sail/pull/395

## [v1.14.2](https://github.com/laravel/sail/compare/v1.14.1...v1.14.2) - 2022-05-10

### Fixed

- Allow Sail to read from phpunit.xml and phpunit.xml.dist when running the install command by @kylemilloy in https://github.com/laravel/sail/pull/394
- Fix missing usage of POSTGRES_VERSION by @driesvints in https://github.com/laravel/sail/pull/398

## [v1.14.1](https://github.com/laravel/sail/compare/v1.14.0...v1.14.1) - 2022-05-02

### Changed

- Expose 8080 port for hot module replacement by @ryoluo in https://github.com/laravel/sail/pull/391

## [v1.14.0](https://github.com/laravel/sail/compare/v1.13.10...v1.14.0) - 2022-04-27

### Added

- Create a dedicated testing database by @jessarcher in https://github.com/laravel/sail/pull/388

### Fixed

- Fix apt-key for WSL by @Evertt in https://github.com/laravel/sail/pull/389

## [v1.13.10](https://github.com/laravel/sail/compare/v1.13.9...v1.13.10) - 2022-04-14

### Fixed

- Fix apt-key for WSL by @driesvints in https://github.com/laravel/sail/pull/384

## [v1.13.9](https://github.com/laravel/sail/compare/v1.13.8...v1.13.9) - 2022-04-04

### Changed

- Update default PostgreSQL version to v14 by @ariaieboy in https://github.com/laravel/sail/pull/373

## [v1.13.8](https://github.com/laravel/sail/compare/v1.13.7...v1.13.8) - 2022-03-23

### Changed

- Update ondrej/php Repository Details by @amayer5125 in https://github.com/laravel/sail/pull/360
- Shell - display available commands / help section by @WalterWoshid in https://github.com/laravel/sail/pull/359

### Fixes

- Fixes docker-compose not found in non-bash shells by @ribeirobreno in https://github.com/laravel/sail/pull/364

## [v1.13.7](https://github.com/laravel/sail/compare/v1.13.6...v1.13.7) - 2022-03-15

### Fixed

- The input device is not a TTY by @ribeirobreno in https://github.com/laravel/sail/pull/353
- `SAIL_FILE` environment variable prevents using docker-compose.override.yml by @ribeirobreno in https://github.com/laravel/sail/pull/355

## [v1.13.6](https://github.com/laravel/sail/compare/v1.13.5...v1.13.6) - 2022-03-08

### Changed

- Allow overriding docker-compose.yml path using ENV by @prageeth in https://github.com/laravel/sail/pull/352 & @taylorotwell in https://github.com/laravel/sail/commit/6205041336b09b965af1d6af29261584e787bf52

## [v1.13.5](https://github.com/laravel/sail/compare/v1.13.3...v1.13.5) - 2022-02-22

### Changed

- Revert "Install regular PHP packages instead of dev versions" by @taylorotwell in https://github.com/laravel/sail/pull/342

## [v1.13.4](https://github.com/laravel/sail/compare/v1.13.3...v1.13.4) - 2022-02-17

### Changed

- Install regular PHP packages instead of dev versions by @bramdevries in https://github.com/laravel/sail/pull/340
- Update Ubuntu by @taylorotwell in https://github.com/laravel/sail/commit/57d2942d5edd89b2018d0a3447da321fa35baac7

## [v1.13.3](https://github.com/laravel/sail/compare/v1.13.2...v1.13.3) - 2022-02-15

### Changed

- Support Newer Docker Compose Exit Statuses by @amayer5125 in https://github.com/laravel/sail/pull/331

### Fixed

- Typo in replace when checking for ARM for Seleium by @aprat84 in https://github.com/laravel/sail/pull/330

## [v1.13.2](https://github.com/laravel/sail/compare/v1.13.1...v1.13.2) - 2022-02-08

### Fixed

- Fix a typo in the "phpunit" command ([#329](https://github.com/laravel/sail/pull/329))

## [v1.13.1 (2022-01-20)](https://github.com/laravel/sail/compare/v1.13.0...v1.13.1)

### Changed

- Update for Meilisearch ARM support ([#315](https://github.com/laravel/sail/pull/315))

### Fixed

- Fix php8.0-dev depending on php8.1-cli ([#316](https://github.com/laravel/sail/pull/316))

## [v1.13.0 (2022-01-18)](https://github.com/laravel/sail/compare/v1.12.12...v1.13.0)

### Added

- Add phpunit alias to sail binary ([#310](https://github.com/laravel/sail/pull/310))

### Changed

- Add separator between volume names ([#312](https://github.com/laravel/sail/pull/312))

## [v1.12.12 (2021-12-16)](https://github.com/laravel/sail/compare/v1.12.11...v1.12.12)

### Fixed

- Revert "Set meilisearch data path" ([#301](https://github.com/laravel/sail/pull/301))

## [v1.12.11 (2021-12-14)](https://github.com/laravel/sail/compare/v1.12.10...v1.12.11)

### Added

- Set meilisearch data path ([#299](https://github.com/laravel/sail/pull/299))

## [v1.12.10 (2021-12-07)](https://github.com/laravel/sail/compare/v1.12.9...v1.12.10)

### Fixed

- ARM based container on Apple Silicon for Selenium ([#294](https://github.com/laravel/sail/pull/294))

## [v1.12.9 (2021-11-30)](https://github.com/laravel/sail/compare/v1.12.8...v1.12.9)

### Changed

- Make PHP 8.1 the default runtime ([#292](https://github.com/laravel/sail/pull/292))

## [v1.12.8 (2021-11-26)](https://github.com/laravel/sail/compare/v1.12.7...v1.12.8)

## Changed

- Revert "Switch to PHP 8.1" ([#291](https://github.com/laravel/sail/pull/291))

## [v1.12.7 (2021-11-26)](https://github.com/laravel/sail/compare/v1.12.6...v1.12.7)

### Changed

- Make PHP 8.1 the default runtime ([#289](https://github.com/laravel/sail/pull/289))

## [v1.12.6 (2021-11-23)](https://github.com/laravel/sail/compare/v1.12.5...v1.12.6)

### Changed

- Add npm update to Dockerfile ([#285](https://github.com/laravel/sail/pull/285))

## [v1.12.5 (2021-11-16)](https://github.com/laravel/sail/compare/v1.12.4...v1.12.5)

### Changed

- Re-enable previously disabled PHP 8.1 extensions ([#278](https://github.com/laravel/sail/pull/278))
- Add platform setting to Meilisearch config ([1286886](https://github.com/laravel/sail/commit/1286886ec04f9101b756221c90ec766741459db4))

## [v1.12.4 (2021-11-09)](https://github.com/laravel/sail/compare/v1.12.3...v1.12.4)

### Fixed

- Fix `NODE_VERSION` on build ([#274](https://github.com/laravel/sail/pull/274))

## [v1.12.3 (2021-11-05)](https://github.com/laravel/sail/compare/v1.12.2...v1.12.3)

### Changed

- Update MySQL stub for Apple Silicon ([#272](https://github.com/laravel/sail/pull/272))

## [v1.12.2 (2021-10-26)](https://github.com/laravel/sail/compare/v1.12.1...v1.12.2)

### Fixed

- Revert "Adds a check and error for APP_SERVICE being accurate." ([#264](https://github.com/laravel/sail/pull/264))

## [v1.12.1 (2021-10-26)](https://github.com/laravel/sail/compare/v1.12.0...v1.12.1)

### Changed

- Adds a check and error for `APP_SERVICE` being accurate ([#258](https://github.com/laravel/sail/pull/258))
- Allow `NODE_VERSION` variable ([#261](https://github.com/laravel/sail/pull/261))

## [v1.12.0 (2021-10-12)](https://github.com/laravel/sail/compare/v1.11.0...v1.12.0)

### Added

- PHP 8.1 support ([#254](https://github.com/laravel/sail/pull/254))

## [v1.11.0 (2021-10-01)](https://github.com/laravel/sail/compare/v1.10.1...v1.11.0)

### Added

- Added support for "docker compose" command syntax

## [v1.10.2 (2021-09-28)](https://github.com/laravel/sail/compare/v1.10.1...v1.10.2)

### Changed

- Environment variable for share subdomain ([#239](https://github.com/laravel/sail/pull/239))

## [v1.10.1 (2021-08-24)](https://github.com/laravel/sail/compare/v1.10.0...v1.10.1)

### Changed

- Adding extra_hosts to the compose file stubs ([#222](https://github.com/laravel/sail/pull/222))
- Allow skip of sail checks ([#224](https://github.com/laravel/sail/pull/224))

## [v1.10.0 (2021-08-17)](https://github.com/laravel/sail/compare/v1.9.0...v1.10.0)

### Added

- Add devcontainer to install command ([#218](https://github.com/laravel/sail/pull/218))

### Changed

- Removes hardcoded service name from `APP_URL` in `dusk` and `dusk:fails` command ([#219](https://github.com/laravel/sail/pull/219))

## [v1.9.0 (2021-08-03)](https://github.com/laravel/sail/compare/v1.8.6...v1.9.0)

### Added

- Xdebug 3.0 support ([#209](https://github.com/laravel/sail/pull/209))

### Changed

- Make sail script publishable ([#201](https://github.com/laravel/sail/pull/201), [#202](https://github.com/laravel/sail/pull/202))
- Pass additional arguments to shell / root-shell commands ([#208](https://github.com/laravel/sail/pull/208))

### Fixed

- Call source `.env` before exporting bash environment variables ([#207](https://github.com/laravel/sail/pull/207))

## [v1.8.6 (2021-07-15)](https://github.com/laravel/sail/compare/v1.8.5...v1.8.6)

### Fixed

- Fixes missing backslash ([#196](https://github.com/laravel/sail/pull/196))

## [v1.8.5 (2021-07-13)](https://github.com/laravel/sail/compare/v1.8.4...v1.8.5)

### Changed

- Minio Console Port ([#188](https://github.com/laravel/sail/pull/188))

## [v1.8.4 (2021-07-06)](https://github.com/laravel/sail/compare/v1.8.3...v1.8.4)

### Changed

- Update to Ubuntu 21.04 ([#177](https://github.com/laravel/sail/pull/177))
- Add pcov to php 8.0 runtime ([#183](https://github.com/laravel/sail/pull/183))

### Fixed

- Append random subdomain by default ([#175](https://github.com/laravel/sail/pull/175))

### Removed

- Remove Unused SEDCMD ([#179](https://github.com/laravel/sail/pull/179))

## [v1.8.3 (2021-06-29)](https://github.com/laravel/sail/compare/v1.8.2...v1.8.3)

### Fixed

- Revert Ubuntu 21.04 changes ([#174](https://github.com/laravel/sail/pull/174))

## [v1.8.2 (2021-06-29)](https://github.com/laravel/sail/compare/v1.8.1...v1.8.2)

### Changed

- Share/Expose options and cleanup on exit ([#168](https://github.com/laravel/sail/pull/168), [44c7087](https://github.com/laravel/sail/commit/44c7087026a0637471e544237d608a2e1173dc77))
- Update to Ubuntu 21.04 ([#169](https://github.com/laravel/sail/pull/169), [0df641d](https://github.com/laravel/sail/commit/0df641dd2d7f2f42d24aef638e2e579f6ac7e57c), [484b928](https://github.com/laravel/sail/commit/484b9284d46bfe3e1e6a2ed71477bb4b70166070))

## [v1.8.1 (2021-06-08)](https://github.com/laravel/sail/compare/v1.8.0...v1.8.1)

### Fixed

- Fix if statement in `sail` binary ([414fd19](https://github.com/laravel/sail/commit/414fd19858379fd3c0277194904ffb95617d7ee6)

## [v1.8.0 (2021-06-08)](https://github.com/laravel/sail/compare/v1.7.0...v1.8.0)

### Added

- Add proxy to vendor binaries ([#154](https://github.com/laravel/sail/pull/154))

### Changed

- Use node.js v16.x ([#155](https://github.com/laravel/sail/pull/155))
- Update Sail script to only exit if Main Exits ([#156](https://github.com/laravel/sail/pull/156))

### Fixed

- Append MeiliSearch and MinIO to depends ([#151](https://github.com/laravel/sail/pull/151))
- Append MeiliSearch HealthCheck ([#150](https://github.com/laravel/sail/pull/150))

## [v1.7.0 (2021-05-25)](https://github.com/laravel/sail/compare/v1.6.0...v1.7.0)

### Added

- Add Redis CLI command ([#140](https://github.com/laravel/sail/pull/140))

### Fixed

- Add retries & timeout to healthcheck ([#143](https://github.com/laravel/sail/pull/143))

## [v1.6.0 (2021-05-18)](https://github.com/laravel/sail/compare/v1.5.1...v1.6.0)

### Added

- Add MinIO to sail:install Command ([#128](https://github.com/laravel/sail/pull/128))

### Changed

- Clear pecl caches & tmp files during Swoole extension install ([#134](https://github.com/laravel/sail/pull/134))

### Fixed

- Fix mariaDB Health check ([#126](https://github.com/laravel/sail/pull/126))

## [v1.5.1 (2021-05-11)](https://github.com/laravel/sail/compare/v1.5.0...v1.5.1)

### Changed

- Use MySQL shell when running mariadb ([#119](https://github.com/laravel/sail/pull/119))

### Fixed

- Fix mysql health check ([#125](https://github.com/laravel/sail/pull/125))

## [v1.5.0 (2021-04-20)](https://github.com/laravel/sail/compare/v1.4.12...v1.5.0)

### Added

- MariaDB support ([#111](https://github.com/laravel/sail/pull/111))

## [v1.4.12 (2021-04-13)](https://github.com/laravel/sail/compare/v1.4.11...v1.4.12)

### Fixed

- Load missing PECL package index before installing Swoole ([#94](https://github.com/laravel/sail/pull/94))

## [v1.4.11 (2021-04-06)](https://github.com/laravel/sail/compare/v1.4.10...v1.4.11)

### Changed

- Add Swoole ([9cf7a28](https://github.com/laravel/sail/commit/9cf7a289fbae184f8468188c582ea5a604ac1012), [0706de0](https://github.com/laravel/sail/commit/0706de0c6a80e6f04861ffb875f9e13c63568ccb))

## [v1.4.10 (2021-03-30)](https://github.com/laravel/sail/compare/v1.4.9...v1.4.10)

### Changed

- Database default user name and password ([#84](https://github.com/laravel/sail/pull/84))

### Fixed

- Patch issue with environment database password replacement ([#87](https://github.com/laravel/sail/pull/87))

## [v1.4.9 (2021-03-23)](https://github.com/laravel/sail/compare/v1.4.8...v1.4.9)

### Fixed

- Use different DB user & password for Sail ([#75](https://github.com/laravel/sail/pull/75))

## [v1.4.8 (2021-03-16)](https://github.com/laravel/sail/compare/v1.4.7...v1.4.8)

### Fixed

- Update the publish command to consider PHP 7.4 ([#68](https://github.com/laravel/sail/pull/68))

## [v1.4.7 (2021-03-09)](https://github.com/laravel/sail/compare/v1.4.6...v1.4.7)

### Fixed

- Add missing PostgreSQL clients ([#64(https://github.com/laravel/sail/pull/64))
- Use latest expose container ([cebaebc](https://github.com/laravel/sail/commit/cebaebc0bb3806f4cf7bc71564acbfe8c12a8923))

## [v1.4.6 (2021-03-03)](https://github.com/laravel/sail/compare/v1.4.5...v1.4.6)

### Fixed

- Update share command ([59ee7e2](https://github.com/laravel/sail/commit/59ee7e2b2efeb644eabea719186db91d11666733))

## [v1.4.5 (2021-03-03)](https://github.com/laravel/sail/compare/v1.4.4...v1.4.5)

### Fixes

- Replace `DB_PORT` and `DB_CONNECTION` for pgsql ([#63](https://github.com/laravel/sail/pull/63))
- Update share command ([0348ec8](https://github.com/laravel/sail/commit/0348ec8c13fedc4bafc917b9d65721cd475390bf))

## [v1.4.4 (2021-03-02)](https://github.com/laravel/sail/compare/v1.4.3...v1.4.4)

### Changed

- Re-add memcached ([#62](https://github.com/laravel/sail/pull/62))

### Fixed

- Fix pgsql.stub volumes typo ([#60](https://github.com/laravel/sail/pull/60))

## [v1.4.3 (2021-02-22)](https://github.com/laravel/sail/compare/v1.4.2...v1.4.3)

### Changed

- Update flag name ([0200ce6](https://github.com/laravel/sail/commit/0200ce6e0f697699bce036c42d91f1daab8039a8))

## [v1.4.2 (2021-02-22)](https://github.com/laravel/sail/compare/v1.4.1...v1.4.2)

### Changed

- Removed comments ([a317a1a](https://github.com/laravel/sail/commit/a317a1af337ffc07c63ea5a4e04784fdb58ea9df))

## [v1.4.1 (2021-02-23)](https://github.com/laravel/sail/compare/v1.4.0...v1.4.1)

### Changed

- Back out feature ([87c63c2](https://github.com/laravel/sail/commit/87c63c2956749f66e43467d4a730b917ef7428b7))

## [v1.4.0 (2021-02-23)](https://github.com/laravel/sail/compare/v1.3.1...v1.4.0)

### Added

- Implement interactive choice and Meilisearch ([#58](https://github.com/laravel/sail/pull/58), [b78093b](https://github.com/laravel/sail/commit/b78093b02c328d82e27cdacfb20568c49cd980c4))

### Changed

- Display message after installing Sail ([#56](https://github.com/laravel/sail/pull/56))

### Fixed

- Change supervisord logfile and pidfile settings ([#57](https://github.com/laravel/sail/pull/57))

### Removed

- Remove memcached stub ([3a4fac1](https://github.com/laravel/sail/commit/3a4fac159b92424d2ff3472ce182be14fc1cb080))

## [v1.3.1 (2021-02-09)](https://github.com/laravel/sail/compare/v1.3.0...v1.3.1)

### Changed

- Inform user when running docker-compose down ([#52](https://github.com/laravel/sail/pull/52))
- Cleanup supervisord warnings on start ([#53](https://github.com/laravel/sail/pull/53))

## [v1.3.0 (2021-01-26)](https://github.com/laravel/sail/compare/v1.2.0...v1.3.0)

### Added

- Add support for `dusk:fails` ([#43](https://github.com/laravel/sail/pull/43))

### Fixed

- Append PostgreSQL HealthCheck ([#41](https://github.com/laravel/sail/pull/41))
- Use non-root MySQL password for `sail mysql` ([#45](https://github.com/laravel/sail/pull/45))

## [v1.2.0 (2021-01-19)](https://github.com/laravel/sail/compare/v1.1.0...v1.2.0)

### Added

- PostgreSQL Support ([#28](https://github.com/laravel/sail/pull/28))

### Changed

- Add healthcheck for mysql and redis service in docker-compose ([#36](https://github.com/laravel/sail/pull/36))
- Update Mailhog env variables ([bf10c80](https://github.com/laravel/sail/commit/bf10c804057f8d0be615c71acbc46c7328cd652c))

## [v1.1.0 (2021-01-05)](https://github.com/laravel/sail/compare/v1.0.1...v1.1.0)

### Added

- Yarn Support ([#29](https://github.com/laravel/sail/pull/29))
- root-shell added to bin/sail ([#33](https://github.com/laravel/sail/pull/33))

### Changed

- Add sail bash to Initiate a Bash shell within the application container ([#30](https://github.com/laravel/sail/pull/30))

### Fixed

- Send error messages to STDERR ([#32](https://github.com/laravel/sail/pull/32))

## [v1.0.1 (2020-12-22)](https://github.com/laravel/sail/compare/v1.0.0...v1.0.1)

### Fixed

- Fix a bug with memcached ([7457004](https://github.com/laravel/sail/commit/7457004969dd62fa727fbc596bb2accccb1409a5))

## v1.0.0 (2020-12-22)

Initial stable release.
