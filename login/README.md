# PoC migrate AngularJS to Angular

This is a PoC to validate if it is possible to migrate the openITCOCKPIT Frontend from AngularJS to Angular.

With that said, this will not mean that Angular is the way to go.
We are currently playing around with other Frameworks such as Vue or React as well.

There is also no ETA (estimated time of arrival) yet

---

The Login-Screen of openITCOCKPIT is a separate AngularJS (and hopefully soon an Angular) application.

1. Install dependencies

```sh
cd /opt/openitc/frontend/login
npm install
```

2. Fix file permissions

````sh
mkdir -p "/var/www/.npm"
chown -R www-data:www-data "/var/www/.npm"
````

3. Switch to the `www-data` user and DO **NOT** USE `root` anymore!

```
sudo -u www-data /bin/bash
```

4. Build the required files (whenever you change something)

```
npm run build
```



