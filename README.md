```diff
- RESELLER PANEL HAS BEEN DEPRECATED AND NO LONGER AN ACTIVE SERVICE
```

# WHMCS-Reseller-Panel
The official WHMCS Reseller Panel module.

We will update this module with the latest features of the Reseller Panel as quickly as possible. If you wish to fork the repo and add your own features please feel free too. If you would like to request them to be merged into our official repo we will happily review and consider them for offical release.

Installation and setup
------
We have made installing the WHMCS Reseller Panel module as simple as possible. First download this repo and upload the folder structure into your WHMCS install directory.

Once the module has been uploaded following these steps:

1. Create a new server in WHMCS and select the Reseller Panel from the drop down list of server types.
2. Enter your username and password into the server login fields.
3. Enter your API key code which you can find in your my profile page in the Reseller Panel and enter it into the 'Hash' field.
4. Now create your first product just like any other product and on the module page select the Reseller Panel server you created and add the package ID from your Reseller Panel into this field.
5. Under the custom fields tab add a new drop down field for your location. Name it 'location|Location' and fetch the latest list from within your Reseller Panel account.
6. That is it, the package ID will be looked up via the API to apply all limits to the new account.

Common Issues
------
* Getting 'There were errors or missing values in the data you provided!' when trying to create a new account is normally due to your username not meeting the basic cPanel requirements. Usernames need to be 3-8 characters long. Make sure all fields are named correctly and a location has been selected.
