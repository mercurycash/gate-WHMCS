## Mercury Payment WHMCS Module

 1. Clone repo with --recursive flag
 
    `git clone git@github.com:mercurycash/gate-WHMCS.git --recursive`
      
 2. Open in terminal

        1. `cd /modules/gateways/Mercury/mercury-cash-sdk` 

        2. `composer install --no-dev`
        
 3. Go back to the main directory of repo.Copy all files to your WHMСS root directory.
        
    sudo rsync -avr --exclude='.git'  --exclude='.md'  --exclude='.gitignore'  --exclude='.gitmodules'   *  /var/www/html/whmcs/

        
 4. Open gateways plugins page at admin part of WHMCS, activate a plugin,set up Public key and Secret key.



