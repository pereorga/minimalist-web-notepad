Minimalist Web Notepad
======================

This is an open source clone of notepad.cc, which is now defunct.

For a demo, see https://orga.cat/notes or https://orga.cat/notes/whatever.


Installation
------------

At the top of `index.php` file, change `$base_url` variable to point to your
site.

Make sure the web server is allowed to write to the `_tmp` directory.

### On Apache

You may need to enable mod_rewrite and set up `.htaccess` files in your site configuration.
See [How To Set Up mod_rewrite for Apache](https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite-for-apache-on-ubuntu-14-04).

### On Nginx

To enable URL rewriting, put something like this in your configuration file:

```
location ~* ^/notes/(\w+)$ {
    try_files $uri $uri/ /notes/index.php?f=$1;
}
```


Copyright and license
---------------------

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
