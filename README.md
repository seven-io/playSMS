<p align="center">
  <img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" alt="seven logo" />
</p>

<h1 align="center">seven SMS Gateway for playSMS</h1>

<p align="center">
  Gateway plugin for <a href="https://playsms.org/">playSMS</a> that routes SMS through the seven gateway.
</p>

<p align="center">
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-GPLv3-teal.svg" alt="GPLv3" /></a>
  <img src="https://img.shields.io/badge/playSMS-1.4%2B-blue" alt="playSMS 1.4+" />
  <img src="https://img.shields.io/badge/PHP-7.2%2B-purple" alt="PHP 7.2+" />
</p>

---

## Features

- **SMS Gateway** - Adds *seven* as an outbound SMS gateway in playSMS
- **Custom Sender ID** - Set per-module sender via the gateway config
- **Module Timezone** - Per-module timezone setting

## Prerequisites

- A [playSMS](https://playsms.org/) installation
- A [seven account](https://www.seven.io/) with API key ([How to get your API key](https://help.seven.io/en/developer/where-do-i-find-my-api-key))

## Installation

1. Clone or extract the plugin into the playSMS gateway plugin directory:

   ```bash
   cd /path/to/playsms/web/plugin/gateway
   git clone https://github.com/seven-io/playSMS.git seven
   ```

2. Log in to playSMS as admin and go to **Settings > Manage gateway and groups**.
3. Click **seven** in the gateway list and configure it.

## Configuration

| Field | Description |
|-------|-------------|
| `APIKey` | Your seven API key |
| `module_sender` | Default sender ID for messages from this module |
| `datetime_timezone` | Timezone used for module timestamps |

Save your changes. The seven gateway is now selectable when creating SMS routes.

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/) or [open an issue](https://github.com/seven-io/playSMS/issues).

## License

[GPLv3](LICENSE) - matches the upstream playSMS license.
