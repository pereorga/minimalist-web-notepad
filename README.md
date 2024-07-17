<p align="center">
    [![minimalist-web-notepad](https://github.com/Its4Nik/minimalist-web-notepad/actions/workflows/action-master.yaml/badge.svg)](https://github.com/Its4Nik/minimalist-web-notepad/actions/workflows/action-master.yaml)

    [![DEV-minimalist-web-notepad](https://github.com/Its4Nik/minimalist-web-notepad/actions/workflows/action-dev.yml/badge.svg)](https://github.com/Its4Nik/minimalist-web-notepad/actions/workflows/action-dev.yml)
</p>

# Minimalist Web Notepad

This is an open-source clone of the now-defunct notepad.cc: "a piece of paper in the cloud".

See demo at https://notes.orga.cat or https://notes.orga.cat/whatever.

## Installation

### Option 1

Use [docker](https://docker.com) or docker compose.

```docker
docker run -p 1234:80 ghcr.io/Its4Nik/:latest
```

or

```yaml
######## File: ########
# docker-compose.yaml #
#######################

services:
    minimalist-web-notepad:
        image: ghcr.io/Its4Nik/:latest
        ports:
            - 1234:80 # Change the first value to change the port on which minimalist-web-notepad should be reachable
```

### Option 2

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
