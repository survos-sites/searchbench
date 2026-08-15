#!/usr/bin/env bash
#
# Load every demo dataset, then index it.
#
# The loaders are castor tasks, not console commands: `castor load <code>` downloads the raw
# dataset if needed, runs import:convert to produce JSONL + a profile, then import:entities to
# import into Doctrine. `castor load` with no argument lists the available codes.
#
# This script used to call `bin/console app:load-data`, which does not exist -- the only trace
# of it left in the repo was a string in LoadCongressCommand's success message. Anyone
# following the script got a "command not found" and no data.
#
# Usage:
#   bin/load-data.sh                 # every dataset, unlimited
#   bin/load-data.sh --limit 500     # every dataset, capped per dataset
#   bin/load-data.sh --reset         # drop and reimport
set -euo pipefail

castor load:all "$@"

bin/console grid:index

# Elasticsearch indexes are separate from grid:index -- create the mapping, then bulk load.
# Skipped silently when survos/elastic-bundle is not installed.
if bin/console list elastic --raw >/dev/null 2>&1; then
    bin/console elastic:index:create --drop
    bin/console elastic:index:populate
fi

symfony server:start -d
symfony open:local
