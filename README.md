# Minimalist Web Notepad

This is an open source clone of notepad.cc, which is now defunct.

See demo at https://notes.orga.cat or https://notes.orga.cat/whatever.

## Installation

At the top of `index.php` file, change `$base_url` variable to point to your
site.

Make sure the web server is allowed to write to the `_tmp` directory.

### On Apache

You may need to enable mod_rewrite and set up `.htaccess` files in your site configuration.
See [How To Set Up mod_rewrite for Apache](https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite-for-apache-on-ubuntu-14-04).

### On Nginx

To enable URL rewriting, put something like this in your configuration file:

If the project resides in the root directory:
```
location / {
    rewrite ^/([a-zA-Z0-9_-]+)$ /index.php?note=$1;
}
```

If the project resides in a subdirectory:
```
location ~* ^/notes/([a-zA-Z0-9_-]+)$ {
    try_files $uri /notes/index.php?note=$1;
}
```

## Branches

To install it with Docker see the [docker](https://github.com/pereorga/minimalist-web-notepad/tree/docker) branch.

A version that allows for encryption using the Web Crypto API is available at the [encryption](https://github.com/pereorga/minimalist-web-notepad/tree/encryption) branch.


## Copyright and license

Copyright 2012 Pere Orga <pere@orga.cat>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this work except in compliance with the License.
You may obtain a copy of the License at:

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
