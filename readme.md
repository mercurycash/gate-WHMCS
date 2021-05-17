## Mercury Payment WHMCS Module

1.Open in terminal:

        1. `cd modules/gateways/Mercury/gate-plugins-sdk` 

        2. `composer install --no-dev`

2.Run this command from the main directory of the repo or Copy all files to your WHMСS root directory.
        
    sudo rsync -avr --exclude='.git'  --exclude='.md'  --exclude='.gitignore'  --exclude='.gitmodules'   *  /var/www/html/whmcs/
        
3.Open gateways plugins page at admin part of WHMCS, activate a plugin,set up Public key and Secret key.