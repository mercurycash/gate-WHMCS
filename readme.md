##Mercury Payment WHMCS Module

#####1.Clone repo with --recursive flag

git clone git@github.com:mercurycash/gate-WHMCS.git --recursive

#####2. Open in terminal

* cd /modules/Mercury/mecury-cach-sdk directory
* composer install --no-dev

##### 3. Go back to the main directory of repo
######Copy all files to your WHMСS root directory  

cp -R * /var/www/html/whmcs
 
#####4. Open gateways plugins page at admin part of WHMCS and activate plugin. 



