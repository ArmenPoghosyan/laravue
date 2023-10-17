# Getting Started

## Installation

Ensure you have the following installed:

-   [Node.js](https://nodejs.org/en/download/) >=16
-   [Open Server](https://ospanel.io/download/) >= 5.4.3
-   [Yarn](https://yarnpkg.com/en/docs/install)
-   [Quasar CLI](https://quasar.dev/start/quasar-cli)

Then run the following script which will install php and quasar dependencies.

```bash
./scripts/install.sh
```

## Development

It's recommended to use [Visual Studio Code](https://code.visualstudio.com/) as your IDE.

To start the frontend development server, run the following script or run "Run" task in VSCode.

```bash
./scripts/run.sh
```

To build the frontend, run the following script or run "Build" task in VSCode.

```bash
./scripts/build.sh
```

To clean the frontend build, run the following script or run "Clean" task in VSCode.

```bash
./scripts/clean.sh
```

## Fake data

To generate fake data, run the following script.

```bash
php artisan db:seed
```

# Project Structure

The project is split into two parts: backend and frontend.

The backend is located in the root directory.

The frontend is located in the [resources/js/quasar](resources/js/quasar) directory.
