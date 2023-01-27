# CloudSearch Build #

This directory is updating the AWS SDK and dependencies in the parent's vendor directory only and should be ignored when just using the plugin.

It is recommended that you do the update via the docker build script so more variables are controlled.

To build, have docker installed and then run:

```
docker-compose up --build
```