nfq task
===

1. composer install
2. php bin/console doctrine:database:create
3. php bin/console doctrine:schema:update --force
4. php bin/console fos:user:create  - create user and than fos:user:promote - set role admin
5. php bin/console oauth:client:create copy from output client id and secret
6. oauth/v2/token in this route generate token with client id, secret, username and password
7. All route access with oauth in Header Authorization expected Bearer your token
