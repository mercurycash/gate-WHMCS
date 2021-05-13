## Mercury Payment WHMCS Module

1.Run this command from the main directory of the repo or Copy all files to your WHMСS root directory.
        
    sudo rsync -avr --exclude='.git'  --exclude='.md'  --exclude='.gitignore'  --exclude='.gitmodules'   *  /var/www/html/whmcs/
        
2.Open gateways plugins page at admin part of WHMCS, activate a plugin,set up Public key and Secret key.