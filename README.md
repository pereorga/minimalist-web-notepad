# Minimalist Web Notepad

This is an open-source clone of the now-defunct notepad.cc: "a piece of paper in the cloud".

See demo at https://notes.orga.cat or https://notes.orga.cat/whatever.

## Installation

Make sure the web server is allowed to write to the `_tmp` directory.

### On Apache

You may need to enable mod_rewrite and allow `.htaccess` files in your site configuration.
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

If parameters need to be passed in Nginx (such as `?raw`), then `&$args` needs to be added to the end of the `$1` match in the rewrite rule:
```
location ~* ^/notes/([a-zA-Z0-9_-]+)$ {
    try_files $uri /notes/index.php?note=$1&$args;
}
```

## Usage (CLI)

Using the command-line interface you can both save and retrieve notes. Here are some examples using `curl`:

Retrieve a note's content and save it to a local file:

```
curl https://example.com/notes/test > test.txt
```

Save specific text to a note:

```
curl https://example.com/notes/test -d 'hello,

welcome to my pad!
'
```

Save the content of a local file (e.g., `/etc/hosts`) to a note:

```
cat /etc/hosts | curl https://example.com/notes/hosts --data-binary @-
```

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
